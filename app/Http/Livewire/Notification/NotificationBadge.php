<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBadge extends Component
{

    public $count = 2;

    protected $listeners = ['refreshNotificationBadge' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        // $this->count = Auth::user()->unreadNotifications->count();
    }

    public function render()
    {
        return view('livewire.notification.notification-badge');
    }
}
