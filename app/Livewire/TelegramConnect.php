<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TelegramConnect extends Component
{
    public ?string $code = null;
    public ?string $expiresAt = null;
    public bool $isConnected = false;
    public ?string $telegramUsername = null;
    public bool $showCode = false;

    public function mount()
    {
        $this->checkStatus();
    }

    public function checkStatus()
    {
        $user = Auth::user();
        $this->isConnected = !empty($user->telegram_chat_id);
        $this->telegramUsername = $user->telegram_username;
    }

    public function generateCode()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
                'Accept' => 'application/json',
            ])->get(url('/api/v1/telegram/generate-code'));

            if ($response->successful()) {
                $data = $response->json();
                $this->code = $data['code'];
                $this->expiresAt = $data['expires_at'];
                $this->showCode = true;
            } else {
                $this->dispatch('notify', type: 'error', message: 'Ошибка генерации кода');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Ошибка: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
                'Accept' => 'application/json',
            ])->post(url('/api/v1/telegram/disconnect'));

            if ($response->successful()) {
                $this->isConnected = false;
                $this->telegramUsername = null;
                $this->dispatch('notify', type: 'success', message: 'Telegram отключен');
            } else {
                $this->dispatch('notify', type: 'error', message: 'Ошибка отключения');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Ошибка: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.telegram-connect');
    }
}
