<?php

namespace App\Managers\Abstract;
use App\Classes\Telegram;
use App\Models\Phrases;

abstract class Manager {
	protected Telegram $telegram;

	public function __construct(Telegram $telegram) {
		$this->telegram = $telegram;	
	}

	abstract public function processUpdate();

	public function doublePhrasesAction() {
		//
	}
}