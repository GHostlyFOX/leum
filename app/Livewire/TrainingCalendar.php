<?php

declare(strict_types=1);

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;

#[Layout('layouts.app')]
class TrainingCalendar extends Component
{
    public ?int $clubId = null;
    public int $currentMonth;
    public int $currentYear;
    public array $trainings = [];
    public array $calendarDays = [];

    public function mount()
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // admin or coach
            ->first();

        if (!$membership) {
            return redirect()->route('home')->with('error', 'Нет доступа');
        }

        $this->clubId = $membership->club_id;
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        $this->loadCalendarData();
    }

    public function previousMonth()
    {
        if ($this->currentMonth === 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->loadCalendarData();
    }

    public function nextMonth()
    {
        if ($this->currentMonth === 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->loadCalendarData();
    }

    private function loadCalendarData()
    {
        // Get trainings for the current month
        $startDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfDay();
        $endDate = $startDate->clone()->endOfMonth()->endOfDay();

        $this->trainings = Training::where('club_id', $this->clubId)
            ->whereBetween('training_date', [$startDate, $endDate])
            ->with(['team', 'venue', 'coach'])
            ->get()
            ->groupBy(fn($t) => $t->training_date->format('Y-m-d'))
            ->toArray();

        $this->generateCalendarDays();
    }

    private function generateCalendarDays()
    {
        $this->calendarDays = [];
        $firstDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->clone()->endOfMonth();

        // Add empty days for days before month starts (Monday = 0)
        $startingDayOfWeek = ($firstDay->dayOfWeek + 6) % 7; // Convert: Mon=0, Tue=1, ..., Sun=6
        for ($i = 0; $i < $startingDayOfWeek; $i++) {
            $this->calendarDays[] = [
                'date' => null,
                'day' => null,
                'trainings' => [],
                'isCurrentMonth' => false,
            ];
        }

        // Add days of the month
        for ($day = 1; $day <= $lastDay->day; $day++) {
            $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            $dateString = $date->format('Y-m-d');

            $this->calendarDays[] = [
                'date' => $dateString,
                'day' => $day,
                'trainings' => $this->trainings[$dateString] ?? [],
                'isCurrentMonth' => true,
                'isToday' => $date->isToday(),
            ];
        }

        // Add empty days for days after month ends
        $remainingDays = 42 - count($this->calendarDays); // 6 rows * 7 days
        for ($i = 0; $i < $remainingDays; $i++) {
            $this->calendarDays[] = [
                'date' => null,
                'day' => null,
                'trainings' => [],
                'isCurrentMonth' => false,
            ];
        }
    }

    public function render()
    {
        $months = [
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
            5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
            9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь',
        ];

        return view('livewire.training-calendar', [
            'monthName' => $months[$this->currentMonth] ?? '',
        ]);
    }
}
