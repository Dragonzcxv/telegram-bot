<?php

namespace App\Managers\Abstract;
use App\Classes\Telegram;
use App\Models\DoublePhrases;
use Illuminate\Support\Facades\Http;

abstract class Manager {
	protected Telegram $telegram;
	protected int $chat_id;

	public function __construct(Telegram $telegram, int $chat_id) {
		$this->telegram = $telegram;
		$this->chat_id = $chat_id;
	}

	abstract public function processUpdate($update);

	public function doublePhrasesAction() {
		$left = DoublePhrases::where('active', true)
			->where('type', 'left')
			->inRandomOrder()
			->first()->name;
		$right = DoublePhrases::where('active', true)
			->where('type', 'right')
			->inRandomOrder()
			->first()->name;

		$this->telegram->sendMessage($this->chat_id, "{$left} {$right}");
	}

	public function jokeAction() {
		$content = Http::get('https://www.anekdot.ru/rss/randomu.html')->body();
		preg_match('/\'\[.+\]\'/', $content, $match);
	
		$text = explode('\",\\' , $match[0])[0];
	
		$text = str_replace('\",\\', '', $text);
		$text = str_replace('<br>', "\n", $text);
		$text = ltrim($text, '\'[\\"');

		$this->telegram->sendMessage($this->chat_id, $text);
	}
}