<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;

class NotificationDropdown extends Component
{

    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Using fake static data for now
        $this->notifications = [
            [
                'id' => 1,
                'title' => 'New Order Placed',
                'message' => 'Order #1234 has been placed successfully!',
                'time' => '2 mins ago',
                'type' => 'order',
                'read' => false,
            ],
            [
                'id' => 2,
                'title' => 'Payment Received',
                'message' => 'Payment of $50 has been successfully received.',
                'time' => '10 mins ago',
                'type' => 'payment',
                'read' => true,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
            [
                'id' => 3,
                'title' => 'New Message',
                'message' => 'You have received a new message from Alex.',
                'time' => '30 mins ago',
                'type' => 'message',
                'read' => false,
            ],
        ];

        // Count unread notifications
        $this->unreadCount = collect($this->notifications)->where('read', false)->count();
    }

    // public function markAsRead($notificationId)
    // {
    //     foreach ($this->notifications as &$notification) {
    //         if ($notification['id'] == $notificationId) {
    //             $notification['read'] = true;
    //         }
    //     }

    //     // Recalculate unread count
    //     $this->unreadCount = collect($this->notifications)->where('read', false)->count();
    // }
    public function render()
    {
        return view('livewire.notification.notification-dropdown');
    }
}
