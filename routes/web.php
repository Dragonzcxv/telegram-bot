<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

Route::post('/bot', function (Request $request) {
	$result = json_decode(json_encode($request->post()));
	$telegram = new Telegram(env('TELEGRAM_BOT_TOKKEN'));
	$stats_path = base_path() . "/public/temp/stats.json";
	$bot_manager = new BotManager($telegram, env('TELEGRAM_CHAT_ID'), $stats_path, env('UPDATES_TIMEOUT'));
	$bot_manager->hookProcess($result);
});
