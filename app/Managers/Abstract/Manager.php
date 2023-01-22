<?php

namespace App\Managers\Abstract;

use App\Actions\DoublePhrasesAction;
use App\Actions\JokeAction;
use App\Classes\Telegram;

/**
 * Абстрактный класс менджера работы бота
 */
abstract class Manager {
	protected Telegram $telegram;
	protected int $chat_id;
	protected string $stats_path;
	protected int $timeout;
	
	/**
	 * __construct
	 *
	 * @param  Telegram $telegram объект класса для работы с Telegram
	 * @param  int $chat_id id чата с которым работает бот
	 * @param  string $stats_path путь до файла состояния бота
	 * @param  int $timeout задержка ответа telegram(long polling)
	 * @return void
	 */
	public function __construct(Telegram $telegram, int $chat_id, string $stats_path, int $timeout) {
		$this->telegram = $telegram;
		$this->chat_id = $chat_id;
		$this->stats_path = $stats_path;
		$this->timeout = $timeout;

		// Создаём файл состояния бота
		if (!\file_exists($stats_path)) {
			$this->statsInit();
		}
	}
	
	/**
	 * Абстратный метод обработки обновлений бота
	 *
	 * @param  object $update Обновление бота
	 * @return void
	 */
	abstract protected function processUpdate($update);
	
	/**
	 * Абстрактаный метод запуска бота(Работа через Long Polling)
	 *
	 * @return void
	 */
	abstract public function start();
	
	/**
	 * Абстрактный метод остановки бота(Работа через Long Polling)
	 *
	 * @return void
	 */
	abstract public function stop();
	
	/**
	 * Абстрактный метод обработки обновления через хуки
	 *
	 * @param  object $update Обновление бота
	 * @return void
	 */
	abstract public function hookProcess($update);

	/**
	 * Cоздаёт файла состояния бота
	 *
	 * @return void
	 */
	protected function statsInit() {
		$data = [
			'offset' => 0, // id последнего обновления
			'work' => true, // параметр работы бота(не имеет значения при работе через хуки)
			'date' => 0, // дата работы, обновляется каждый цикл
			'hook_mode' => false, // Вариант работы через хуки
		];

		\file_put_contents($this->stats_path, \json_encode($data));
	}
	
	/**
	 * Возвращает массив состояния бота
	 *
	 * @return object
	 */
	protected function statsGet() {
		return \json_decode(\file_get_contents($this->stats_path));
	}

	/**
	 * Обновляет файл состояния бота
	 *
	 * @param  array $data параметры сотояния бота
	 * @return void
	 */
	protected function statsUpdate($data) {
		\file_put_contents($this->stats_path, \json_encode($data));
	}
	
	/**
	 * Action, который отправляет в чат фразу составленную из таблицы double_phrases
	 *
	 * @return void
	 */
	public function doublePhrasesAction() {
		DoublePhrasesAction::action($this->telegram, $this->chat_id);
	}
	
	/**
	 * Action, который отправляет в чат рандомный анекдот
	 *
	 * @return void
	 */
	public function jokeAction() {
		JokeAction::action($this->telegram, $this->chat_id);
	}
}