<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Team\Models\InviteLink;
use Modules\Team\Models\Team;
use App\Notifications\TeamInviteNotification;
use Illuminate\Support\Facades\Notification;

class InviteModal extends Component
{
    public ?int $teamId = null;

    // Tabs
    public string $activeTab = 'email'; // 'email' | 'link'

    // Email invite
    public string $email = '';
    public string $emailRole = 'player';

    // Share link
    public string $linkRole = 'player';
    public ?string $generatedLink = null;

    // Flash messages
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function mount(?int $teamId = null)
    {
        $this->teamId = $teamId;
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetMessages();
    }

    public function sendEmailInvite()
    {
        $this->resetMessages();

        $this->validate([
            'email' => 'required|email',
            'emailRole' => 'required|in:player,coach,parent',
        ], [
            'email.required' => 'Введите email адрес.',
            'email.email' => 'Введите корректный email адрес.',
        ]);

        if (!$this->teamId) {
            $this->errorMessage = 'Команда не выбрана.';
            return;
        }

        $team = Team::find($this->teamId);
        if (!$team) {
            $this->errorMessage = 'Команда не найдена.';
            return;
        }

        // Create invite link
        $invite = InviteLink::create([
            'token'         => Str::random(32),
            'team_id'       => $this->teamId,
            'role'          => $this->emailRole,
            'created_by_id' => Auth::id(),
            'max_uses'      => 1,
            'used_count'    => 0,
            'expires_at'    => now()->addDays(7),
        ]);

        // Send email notification
        $joinUrl = url('/join/' . $invite->token);

        try {
            Notification::route('mail', $this->email)
                ->notify(new TeamInviteNotification($team, $this->emailRole, $joinUrl));

            $this->successMessage = 'Приглашение отправлено на ' . $this->email;
            $this->email = '';
        } catch (\Exception $e) {
            $this->errorMessage = 'Ошибка отправки. Попробуйте позже.';
        }
    }

    public function generateShareLink()
    {
        $this->resetMessages();
        $this->generatedLink = null;

        if (!$this->teamId) {
            $this->errorMessage = 'Команда не выбрана.';
            return;
        }

        $team = Team::find($this->teamId);
        if (!$team) {
            $this->errorMessage = 'Команда не найдена.';
            return;
        }

        $invite = InviteLink::create([
            'token'         => Str::random(32),
            'team_id'       => $this->teamId,
            'role'          => $this->linkRole,
            'created_by_id' => Auth::id(),
            'max_uses'      => null, // unlimited
            'used_count'    => 0,
            'expires_at'    => now()->addDays(30),
        ]);

        $this->generatedLink = url('/join/' . $invite->token);
    }

    public function copyLink()
    {
        $this->dispatch('copy-to-clipboard', link: $this->generatedLink);
    }

    public function closeModal()
    {
        $this->dispatch('close-invite-modal');
    }

    private function resetMessages()
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    private function getRoleLabel(string $role): string
    {
        return match ($role) {
            'coach'  => 'Тренер',
            'parent' => 'Родитель',
            default  => 'Игрок',
        };
    }

    private function getRoleDescription(string $role): string
    {
        return match ($role) {
            'coach'  => 'Может управлять составом и тренировками',
            'parent' => 'Может просматривать расписание и информацию о ребёнке',
            default  => 'Может просматривать информацию команды и отвечать на события',
        };
    }

    public function render()
    {
        $team = $this->teamId ? Team::find($this->teamId) : null;

        return view('livewire.invite-modal', [
            'team' => $team,
        ]);
    }
}
