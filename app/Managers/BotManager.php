<?php

namespace App\Managers;
use App\Models\Phrases;
use App\Models\Categories;
use App\Managers\Abstract\Manager;

class BotManager extends Manager {
	public function processUpdate($update) {
		if ($update->message) {
			$phrase = Phrases::where('active', true)->where('name', $update->message->text)->first();

			if ($phrase) {
				$category = Categories::where('id', $phrase->category)->first();
				$action = $category->action;

				// Вызываем метод привязанный к катеории
				parent::$action();
			}
		}
	}
}