<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AllNotifications extends Component
{
    use WithPagination;

    protected $listeners = ['markAsRead', 'markAllAsRead', 'refreshNotifications'];

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->emit('refreshNotifications'); // Refresh dropdown too
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('refreshNotifications'); // Refresh dropdown too
    }

    public function refreshNotifications()
    {
        $this->resetPage();
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('livewire.notification.all-notifications', compact('notifications'));
    }
}
