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
	$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
	$stats_path = "temp/stats.json";
	$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
	$bot_manager->start();
})->purpose('Запускает бота, выполняется мониторинг и обработка обновлений');

// Команда остановки бота
Artisan::command('bot-stop', function () {
	$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
	$stats_path = "temp/stats.json";
	$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
	$bot_manager->stop();
})->purpose('Останавливает работу бота');