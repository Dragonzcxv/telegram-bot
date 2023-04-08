<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Classes\Telegram;
use App\Managers\BotManager;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// $schedule->command('inspire')->hourly();
		$schedule->call(function () {
			$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
			$stats_path = base_path() . "/public/temp/stats.json";
			$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
			$bot_manager->pushDayImage(\mb_strtolower(date('l')));
		})->dailyAt('9:00');

		$schedule->call(function () {
			$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
			$stats_path = base_path() . "/public/temp/stats.json";
			$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
			$bot_manager->pushWeatherWithImage();
		})->dailyAt('7:30');
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
