<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Classes\Telegram;
use App\Managers\BotManager;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Команда запуска бота
Artisan::command('bot-start', function () {
	$telegram = new \App\Classes\Telegram(env('TELEGRAM_BOT_TOKKEN'));
	$bot_manager = new \App\Managers\BotManager($telegram, env('TELEGRAM_CHAT_ID'));
	$work = true;

	while ($work) {
		$data = [
			'timeout' => env('UPDATES_TIMEOUT'),
		];

		$stats_json = file_get_contents(base_path() . "/public/temp/stats.json");
		$stats = json_decode($stats_json);

		if ($stats->offset) {
			$data['offset'] = intval($stats->offset);
		}

		$work = $stats->work;

		$updates = $telegram->getUpdates($data);

		if (!empty($updates)) {
			foreach ($updates as $update) {
				$bot_manager->processUpdate($update);
			}

			$stats->offset = end($updates)->update_id + 1;
			file_put_contents(base_path() . "/public/temp/stats.json", json_encode($stats));
		}

		// $telegram->sendMessage(env('TELEGRAM_OWNER_ID'), "запускаю следующий этап. Индекс: {$stats->offset} Время:" . date('g:i:s'));
	}
})->purpose('Запускает бота, выполняется мониторинг и обработка обновлений');