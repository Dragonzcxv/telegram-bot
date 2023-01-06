<?php

namespace App\Classes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;


/**
 * Класс работы с Telegram
 */
class Telegram {
	protected string $tokken;
	
	/**
	 * __construct
	 *
	 * @param  string $tokken Токен бота
	 * @return void
	 */
	public function __construct(string $tokken)
	{
		$this->tokken = $tokken;
	}
	
	/**
	 * Отправка собщения по указаному id беседы
	 *
	 * @param  int $chat_id id беседы
	 * @param  string $message сообщение
	 * @param  string $parse_mode метод парсинга(html\Markdown\text)
	 * @return void
	 */
	public function sendMessage(int $chat_id, string $message, string $parse_mode = "html") {
		Http::post("https://api.tlgr.org/bot{$this->tokken}/sendMessage", [
			'chat_id' => $chat_id,
			'text' => $message,
			'parse_mode' => $parse_mode,
		]);
	}
	
	/**
	 * Возвращает массив обновлений
	 *
	 * @param  int $offset id обновления с которого нужно вернуть результат
	 * @param  int $timeout задержка ответа(long polling)
	 * @return array
	 */
	public function getUpdates($offset, $timeout) {
		return Http::timeout($offset + 20)->post("https://api.tlgr.org/bot{$this->tokken}/getUpdates", [
			'offset' => $offset,
			'timeout' => $timeout,
		])->object()->result;
	}
	
	/**
	 * Отправляет картинку в указаный чат
	 *
	 * @param  int $chat_id id чата
	 * @param  string $image raw string image
	 * @param  string $image_name имя картинки
	 * @return void
	 */
	public function pushImage(int $chat_id, string $image, string $image_name) {
		Http::attach('photo', $image, $image_name)
			->post("https://api.tlgr.org/bot{$this->tokken}/sendPhoto", [
				'chat_id' => $chat_id,
			]);
	}
}