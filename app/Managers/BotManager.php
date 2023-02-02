<?php

namespace App\Managers;
use App\Models\Phrases;
use App\Models\Categories;
use App\Models\DayPictures;
use App\Managers\Abstract\Manager;
use App\Models\Gallery;
use App\Classes\OpenWeather;

/**
 * Класс менеджера бота
 */
class BotManager extends Manager {	
	/**
	 * Обработка обновления бота
	 *
	 * @param  object $update Обновление бота
	 * @return void
	 */
	protected function processUpdate($update) {
		if (
			isset($update->message)
			&& isset($update->message->text)
			&& $update->message->chat->id == $this->chat_id )
		{
			$phrase = Phrases::where('active', true)->where('name', mb_strtolower($update->message->text))->first();

			if ($phrase) {
				$category = Categories::where('id', $phrase->category)->first();
				$action = $category->action;

				// Вызываем метод привязанный к катеории
				parent::$action();
			}
		}
	}
	
	/**
	 * Обрабатывает обновления бота в режиме hook_mode
	 *
	 * @param  object $update
	 * @return void
	 */
	public function hookProcess($update) {
		$stats = parent::statsGet();

		if ($stats->hook_mode && !empty($update)) {
		    $this->processUpdate($update);

		    // Записываем id следующего обновления
		    $stats->offset = $update->update_id + 1;
		} else {
			$this->telegram->sendMessage(env('TELEGRAM_OWNER_ID'), 'Бот работает не через хуки');
		}
	}
	
	/**
	 * Запускает бота(Работа через Long Polling)
	 *
	 * @return void
	 */
	public function start() {
		// Обновляем параметр работы бота
		$stats = parent::statsGet();
		$stats->work = true;
		parent::statsUpdate($stats);

		while ($stats->work) {
			$stats = parent::statsGet();
			$updates = $this->telegram->getUpdates($stats->offset, $this->timeout);

			// Повторно берём состояние на случай смены work
			$stats = parent::statsGet();
	
			// Бот обработает последние обновления в случае смены work
			if (!empty($updates)) {
				foreach ($updates as $update) {
					$this->processUpdate($update);
				}
	
				// Записываем id следующего обновления
				$stats->offset = end($updates)->update_id + 1;
			}

			// Обновляем состояние
			$stats->date = date("F j, Y, g:i a");
			parent::statsUpdate($stats);
		}
	}
	
	/**
	 * Останавливает работу бота(Работа через Long Polling)
	 *
	 * @return void
	 */
	public function stop() {
		// Обновляем параметр работы бота
		$stats = parent::statsGet();
		$stats->work = false;
		parent::statsUpdate($stats);
	}
	
	/**
	 * Отправляет рандомную картинку привязанную к текущему дню недели
	 *
	 * @param  stirng $day день недели
	 * @return void
	 */
	public function pushDayImage(string $day) {
		$image_path = DayPictures::where('active', true)->where('day', $day)->inRandomOrder()->first()->image;
		$this->telegram->pushImage($this->chat_id, \Storage::disk('public')->get($image_path), basename($image_path));
	}
	
	/**
	 * Отправляет сведения о погоде вместе с рандомной картинкой
	 *
	 * @return void
	 */
	public function pushWeatherWithImage() {
		$image_path = Gallery::where('active', true)->inRandomOrder()->first()->image;
		$weather_api = new OpenWeather(env('OPEN_WEATHER_TOKKEN'), env('WEATHER_LANG'));
		$weather = $weather_api->getWeather(env('CITY_LAT'), env('CITY_LON'));
		$temp = floor($weather['main']['temp']);
		$feels_like = floor($weather['main']['feels_like']);

		$this->telegram->sendMessage($this->chat_id, "
			Время погоды на сегодня!\n
			На улице сейчас {$weather['weather'][0]['description']} \n
			Температура: {$temp}°C\n
			Ощущается как {$feels_like}°C\n
			На этом все, берегите себя и своих близких
		");
		$this->telegram->pushImage($this->chat_id, \Storage::disk('public')->get($image_path), basename($image_path));
	}
}