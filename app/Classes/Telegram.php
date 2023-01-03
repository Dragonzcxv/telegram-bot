<?php

namespace App\Classes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;


class Telegram {
	protected string $tokken;
	protected $index=0;

	public function __construct(string $tokken)
	{
		$this->tokken = $tokken;
	}

	public function sendMessage($chat_id, $message) {
		Http::post("https://api.tlgr.org/bot{$this->tokken}/sendMessage", [
			'chat_id' => $chat_id,
			'text' => $message,
			'parse_mode' => 'html',
		]);
	}

	public function getUpdates($data) {
		return Http::timeout(100)->post("https://api.tlgr.org/bot{$this->tokken}/getUpdates", $data)->object()->result;
	}
}