<?php

declare(strict_types=1);

namespace Modules\Training\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Announcement;

#[Layout('layouts.app')]
class AnnouncementCreate extends Component
{
    public ?int $clubId = null;
    public array $teams = [];

    // Form fields
    public string $title = '';
    public string $message = '';
    public string $priority = 'normal';
    public ?int $selectedTeamId = null;
    public bool $isDraft = false;
    public string $expiresAt = '';

    public function mount(): void
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // admin or coach
            ->first();

        if (!$membership) {
            redirect()->route('home')->with('error', 'Нет доступа');
            return;
        }

        $this->clubId = $membership->club_id;

        $this->teams = Team::where('club_id', $this->clubId)
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'priority' => 'required|in:low,normal,high,urgent',
            'selectedTeamId' => 'nullable|exists:teams,id',
            'expiresAt' => 'nullable|date|after:today',
        ], [
            'title.required' => 'Введите заголовок объявления',
            'message.required' => 'Введите текст объявления',
            'title.max' => 'Заголовок не должен превышать 255 символов',
            'message.max' => 'Текст не должен превышать 5000 символов',
        ]);

        // Verify team belongs to club
        if ($this->selectedTeamId) {
            $team = Team::find($this->selectedTeamId);
            if (!$team || $team->club_id !== $this->clubId) {
                $this->dispatch('notify', type: 'error', message: 'Команда не найдена');
                return;
            }
        }

        $announcement = Announcement::create([
            'title' => $this->title,
            'message' => $this->message,
            'priority' => $this->priority,
            'club_id' => $this->clubId,
            'team_id' => $this->selectedTeamId,
            'author_id' => Auth::id(),
            'is_draft' => $this->isDraft,
            'published_at' => $this->isDraft ? null : now(),
            'expires_at' => !empty($this->expiresAt) ? $this->expiresAt : null,
        ]);

        $statusMessage = $this->isDraft ? 'Объявление сохранено как черновик' : 'Объявление опубликовано';
        $this->dispatch('notify', type: 'success', message: $statusMessage);

        redirect()->route('home');
    }

    public function render()
    {
        return view('training::livewire.announcement-create');
    }
}
