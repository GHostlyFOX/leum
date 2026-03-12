<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\JoinRequest;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class JoinRequests extends Component
{
    public ?int $selectedRequestId = null;
    public string $adminNotes = '';
    public bool $showRejectModal = false;

    public function mount()
    {
        $user = Auth::user();
        
        // Проверяем, что пользователь админ
        $membership = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7)
            ->first();

        if (!$membership && $user->global_role !== 'admin') {
            return redirect()->route('home');
        }
    }

    public function approve(int $requestId)
    {
        $request = JoinRequest::find($requestId);
        if (!$request || $request->status !== 'pending') {
            return;
        }

        // Проверяем, что админ имеет доступ к этому клубу
        if (!$this->canManageRequest($request)) {
            $this->dispatch('notify', type: 'error', message: 'Нет доступа к этой заявке');
            return;
        }

        $request->approve(Auth::id(), $this->adminNotes);
        
        $this->dispatch('notify', type: 'success', message: 'Заявка одобрена');
        $this->adminNotes = '';
    }

    public function openRejectModal(int $requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->selectedRequestId = null;
        $this->showRejectModal = false;
        $this->adminNotes = '';
    }

    public function reject()
    {
        if (!$this->selectedRequestId) {
            return;
        }

        $request = JoinRequest::find($this->selectedRequestId);
        if (!$request || $request->status !== 'pending') {
            return;
        }

        if (!$this->canManageRequest($request)) {
            $this->dispatch('notify', type: 'error', message: 'Нет доступа к этой заявке');
            return;
        }

        $request->reject(Auth::id(), $this->adminNotes);
        
        $this->dispatch('notify', type: 'info', message: 'Заявка отклонена');
        $this->closeRejectModal();
    }

    private function canManageRequest(JoinRequest $request): bool
    {
        $user = Auth::user();
        
        // Супер-админ может управлять всеми заявками
        if ($user->global_role === 'admin') {
            return true;
        }

        // Проверяем, что админ клуба имеет доступ к заявке этого клуба
        $membership = TeamMember::where('user_id', $user->id)
            ->where('club_id', $request->club_id)
            ->where('role_id', 7)
            ->first();

        return $membership !== null;
    }

    public function render()
    {
        $user = Auth::user();
        
        // Получаем club_id админа
        $adminMembership = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7)
            ->first();

        $query = JoinRequest::with(['user', 'club', 'team'])
            ->orderBy('created_at', 'desc');

        // Если не супер-админ, показываем только заявки своего клуба
        if ($user->global_role !== 'admin' && $adminMembership) {
            $query->where('club_id', $adminMembership->club_id);
        }

        $requests = $query->get();

        return view('livewire.join-requests', [
            'requests' => $requests,
            'pendingCount' => $requests->where('status', 'pending')->count(),
        ]);
    }
}
