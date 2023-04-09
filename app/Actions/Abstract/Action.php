<?php

namespace App\Actions\Abstract;

use App\Classes\Telegram;

/**
 * Абстрактный класс Экшенов
 */
abstract class Action
{
    abstract public static function action(Telegram $telegram, int $chat_id);
}
