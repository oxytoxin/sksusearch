<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
        'markAllAsRead' => 'markAllAsRead'
    ];

    public function mount()
    {
        $this->loadNotifications();
         $this->dispatchBrowserEvent('emitToAllNotifications');
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->notifications()->latest()->take(20)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        $this->loadNotifications();

        // ✅ Fire event once so the JS listener refreshes other dropdowns if open
        $this->dispatchBrowserEvent('emitToAllNotifications');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
            $this->dispatchBrowserEvent('emitToAllNotifications');
        }
    }

    public function render()
    {
        return view('livewire.notification.notification-dropdown');
    }
}
