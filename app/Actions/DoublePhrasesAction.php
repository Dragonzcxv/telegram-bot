<?php

namespace App\Actions;

use App\Actions\Abstract\Action;
use App\Classes\Telegram;
use App\Models\DoublePhrases;

class DoublePhrasesAction extends Action
{
    /**
     * Отправляет в указанный чат фразу составленную из таблицы double_phrases
     *
     * @param  Telegram $telegram объект класса Telegram
     * @param  int $chat_id id чата
     * @return void
     */
    public static function action(Telegram $telegram, int $chat_id)
    {
        $left = DoublePhrases::where('active', true)
            ->where('type', 'left')
            ->inRandomOrder()
            ->first()->name;
        $right = DoublePhrases::where('active', true)
            ->where('type', 'right')
            ->inRandomOrder()
            ->first()->name;

        $telegram->sendMessage($chat_id, "{$left} {$right}");
    }
}
