<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use NotificationChannels\Telegram\TelegramUpdates;

class TelegramNotificationController extends Controller
{
    public function toggle(User $user): RedirectResponse
    {
        if($user->hasTelegramChannel()) {
            $user->update(['telegram_user_id' => null]);

            Toast::error('Уведомления отключены.');

            return back();
        }

        $updates = TelegramUpdates::create()->latest()->get();

        if ($updates['ok']) {
            $chat = Arr::get($updates, 'result.0.message.chat');

            $user->update(['telegram_user_id' => $chat['id']]);

            Toast::success(sprintf('Уведомления телеграм для %s включены', $chat['first_name'] . ' ' . $chat['last_name']));
        } else {
            Toast::error('Ошибка подключения уведомлений.');
        }

        return back();
    }
}
