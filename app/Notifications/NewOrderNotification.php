<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'orderer' => $this->order->user->name,
            'tour' => $this->order->tour->name,
            'createdAt' => date_format($this->order->created_at, 'Y-m-d H:i:s'),
        ];
    }

    public function toBroadcast($notifiable)
    {
        $data = [
            'orderer' => $this->order->user->name,
            'tour' => $this->order->tour->name,
            'createdAt' => date_format($this->order->created_at, 'Y-m-d H:i:s'),
        ];

        return new BroadcastMessage($data);
    }
}
