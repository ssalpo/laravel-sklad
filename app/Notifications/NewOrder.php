<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\UnitConvertor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewOrder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public Order $order
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $m = TelegramMessage::create()
            ->to($notifiable->telegram_user_id);

        $m->line(sprintf('*Новая заявка №%s*', $this->order->id));
        $m->line('*Заявщик:* ' . $this->order->user->name);
        $m->line('*Клиент:* ' . $this->order->client->name);
        $m->line('*Сумма заказа:* ' . $this->order->amount);
        $m->line('------------------------');

        $m->line('*Состав заявки:*');
        $m->line('----');
        foreach ($this->order->orderItems as $orderItem) {
            $m->line(
                sprintf(
                    '%s | *%s %s, %s сом.* |',
                    $orderItem->nomenclature->name,
                    $orderItem->quantity,
                    UnitConvertor::UNIT_LABELS[$orderItem->nomenclature->unit],
                    $orderItem->price_for_sale * $orderItem->quantity
                )
            );

            $m->line('----');
        }

        $m->button('Просмотреть', url()->route('orders.show', $this->order->id));

        return $m;
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
            //
        ];
    }
}
