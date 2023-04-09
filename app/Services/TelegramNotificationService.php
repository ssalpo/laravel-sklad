<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Notifications\OrderDeleted;
use App\Notifications\OrderStatusChanged;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class TelegramNotificationService
{
    private Collection $users;

    public function forSubscribedUsers(): static
    {
        $this->users = User::whereNotNull('telegram_user_id')->get();

        return $this;
    }

    public function newOrder(Order $order): void
    {
        Notification::send(
            $this->users,
            new NewOrder($order)
        );
    }

    public function orderDeleted(int $id, User $user): void
    {
        Notification::send(
            $this->users,
            new OrderDeleted($id, $user)
        );
    }

    public function orderStatusChanged(int $id, int $status, ?User $user = null): void
    {
        Notification::send(
            $this->users,
            new OrderStatusChanged($id, $status, $user ?? auth()->user())
        );
    }
}
