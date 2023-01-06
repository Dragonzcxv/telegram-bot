<?php

namespace App\Managers;
use App\Models\Phrases;
use App\Models\Categories;
use App\Managers\Abstract\Manager;

/**
 * Класс менеджера бота
 */
class BotManager extends Manager {	
	/**
	 * Обработка обновления бота
	 *
	 * @param  object $update Обновление бота
	 * @return void
	 */
	protected function processUpdate($update) {
		if ($update->message) {
			$phrase = Phrases::where('active', true)->where('name', mb_strtolower($update->message->text))->first();

			if ($phrase) {
				$category = Categories::where('id', $phrase->category)->first();
				$action = $category->action;

				// Вызываем метод привязанный к катеории
				parent::$action();
			}
		}
	}
	
	/**
	 * Запускает бота
	 *
	 * @return void
	 */
	public function start() {
		// Обновляем параметр работы бота
		$stats = parent::statsGet();
		$stats->work = true;
		parent::statsUpdate($stats);

		while ($stats->work) {
			$stats = parent::statsGet();
			$updates = $this->telegram->getUpdates($stats->offset, $this->timeout);

			// Повторно берём состояние на случай смены work
			$stats = parent::statsGet();
	
			// Бот обработает последние обновления в случае смены work
			if (!empty($updates)) {
				foreach ($updates as $update) {
					$this->processUpdate($update);
				}
	
				// Записываем id следующего обновления
				$stats->offset = end($updates)->update_id + 1;
				parent::statsUpdate($stats);
			}
		}
	}
	
	/**
	 * Останавливает работу бота
	 *
	 * @return void
	 */
	public function stop() {
		// Обновляем параметр работы бота
		$stats = parent::statsGet();
		$stats->work = false;
		parent::statsUpdate($stats);
	}
}