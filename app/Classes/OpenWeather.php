<?php

namespace App\Classes;
use Illuminate\Support\Facades\Http;


/**
 * Класс работы с OpenWeather
 */
class OpenWeather {
	protected string $api_url = 'https://api.openweathermap.org/data/2.5/weather';
	protected string $tokken;
	protected string $lang;
	
	/**
	 * __construct
	 *
	 * @param  mixed $tokken api токен OpenWeather
	 * @param  mixed $lang язык ответов сервиса
	 * @return void
	 */
	public function __construct (string $tokken, string $lang)
	{
		$this->tokken = $tokken;
		$this->lang = $lang;
	}
	
	/**
	 * Возвращает сведения о погоде по переданным координатам
	 *
	 * @param  string $lat широта
	 * @param  string $lon долгота
	 * @return array
	 */
	public function getWeather(string $lat, string $lon) : array
	{
		$response = Http::get($this->api_url, [
			'lat' => $lat,
			'lon' => $lon,
			'appid' => $this->tokken,
			'units' => 'metric',
			'lang' => $this->lang,
		]);

		if ($response->successful()) {
			return $response->json();
		} else {
			throw new \Exception("getWeather запрос был не успешен. Статус запроса:{$response->status()}");
		}
	}
}