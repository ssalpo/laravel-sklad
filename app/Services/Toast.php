<?php

namespace App\Services;

use Illuminate\Http\Request;

class Toast
{
    public static function addToast(string $message, string $type = 'default')
    {
        $toast = [
            'message' => $message,
            'type' => $type
        ];

        session()->flash('toast', $toast);
    }

    public static function success(string $message)
    {
        self::addToast($message, 'success');
    }

    public static function error(string $message)
    {
        self::addToast($message, 'error');
    }

    public static function info(string $message)
    {
        self::addToast($message, 'info');
    }

    public static function warning(string $message)
    {
        self::addToast($message, 'warning');
    }
}
