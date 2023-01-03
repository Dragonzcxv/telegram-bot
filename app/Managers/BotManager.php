<?php

namespace App\Managers\;
use App\Classes\Telegram;
use App\Models\Phrases;

class BotManager {
	public static function processUpdate(Telegram $telegram, $update) {
		if ($update->message) {
			$category = Phrases::where('name', $update->message->text)->first();

			if ($category) {
				dd($category);
			}
		}
	}
}