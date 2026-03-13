<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\User\Models\CoachAchievement;
use Modules\User\Models\CoachProfile;

#[Layout('layouts.app')]
class CoachAchievements extends Component
{
    public ?int $coachId = null;
    public bool $isOwnProfile = false;
    
    // Form fields
    public string $title = '';
    public string $description = '';
    public int $year;
    public string $category = '';
    public ?int $editingId = null;
    
    // UI states
    public bool $showForm = false;
    public bool $showDeleteConfirm = false;
    public ?int $deletingId = null;
    
    // Categories
    public array $categories = [
        'championship' => 'Чемпионат',
        'cup' => 'Кубок',
        'tournament' => 'Турнир',
        'personal' => 'Личное достижение',
        'other' => 'Другое',
    ];

    public function mount(?int $coachId = null)
    {
        $this->year = (int) date('Y');
        
        if ($coachId) {
            $this->coachId = $coachId;
            $this->isOwnProfile = $coachId === Auth::id();
        } else {
            $this->coachId = Auth::id();
            $this->isOwnProfile = true;
        }
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEditForm(int $id)
    {
        $achievement = CoachAchievement::find($id);
        if (!$achievement) {
            $this->dispatch('notify', type: 'error', message: 'Достижение не найдено');
            return;
        }

        $this->title = $achievement->title;
        $this->description = $achievement->description ?? '';
        $this->year = $achievement->year;
        $this->category = $achievement->category ?? '';
        $this->editingId = $id;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'category' => 'nullable|string|max:100',
        ], [
            'title.required' => 'Введите название достижения',
            'year.required' => 'Укажите год',
        ]);

        $profile = CoachProfile::firstOrCreate(
            ['user_id' => $this->coachId],
            [
                'sport_type_id' => 1,
                'career_start' => null,
            ]
        );

        if ($this->editingId) {
            $achievement = CoachAchievement::find($this->editingId);
            if ($achievement) {
                $achievement->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'year' => $this->year,
                    'category' => $this->category,
                ]);
                $this->dispatch('notify', type: 'success', message: 'Достижение обновлено');
            }
        } else {
            CoachAchievement::create([
                'coach_profile_id' => $profile->id,
                'title' => $this->title,
                'description' => $this->description,
                'year' => $this->year,
                'category' => $this->category,
            ]);
            $this->dispatch('notify', type: 'success', message: 'Достижение добавлено');
        }

        $this->closeForm();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->year = (int) date('Y');
        $this->category = '';
        $this->editingId = null;
    }

    public function confirmDelete(int $id)
    {
        $this->deletingId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->deletingId = null;
    }

    public function delete()
    {
        if ($this->deletingId) {
            $achievement = CoachAchievement::find($this->deletingId);
            if ($achievement) {
                $achievement->delete();
                $this->dispatch('notify', type: 'success', message: 'Достижение удалено');
            }
        }
        $this->cancelDelete();
    }

    public function render()
    {
        $profile = CoachProfile::with(['achievementsList', 'user'])
            ->where('user_id', $this->coachId)
            ->first();

        $achievements = $profile?->achievementsList ?? collect();
        $coach = $profile?->user;

        return view('livewire.coach-achievements', [
            'achievements' => $achievements,
            'coach' => $coach,
        ]);
    }
}
