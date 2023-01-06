<?php

use Illuminate\Support\Facades\Route;
use App\Classes\Telegram;
use App\Managers\BotManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
	$stats_path = base_path() . "/public/temp/stats.json";
	$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
	$bot_manager->pushDayImage(\mb_strtolower(date('l')));
});