<?php

namespace App\Actions;

use App\Actions\Abstract\Action;
use App\Classes\Telegram;
use Illuminate\Support\Facades\Http;

/**
 * Класс экшена отправляющий рандомный анегдот
 */
class JokeAction extends Action {	
	/**
	 * Отправляет рандомный анегдот на указанный id
	 *
	 * @param  Telegram $telegram объект класса Telegram
	 * @param  int $chat_id id чата
	 * @return void
	 */
	public static function action(Telegram $telegram, int $chat_id) {
		$telegram->sendMessage($chat_id, self::getJoke());
	}
	
	/**
	 * Возвращает рандомный анегдот с anekdot.ru
	 *
	 * @return string
	 */
	private static function getJoke() {
		$content = Http::get('https://www.anekdot.ru/rss/randomu.html')->body();
		preg_match('/\'\[.+\]\'/', $content, $match);
	
		$text = explode('\",\\' , $match[0])[0];
	
		$text = str_replace('\",\\', '', $text);
		$text = str_replace('<br>', "\n", $text);
		$text = str_replace('\\\\\\', "", $text);
		$text = ltrim($text, '\'[\\"');

		return $text;
	}
}