<?php
namespace morphos\Russian;

trait RussianLanguage {
	static public $vowels = array(
		'А',
		'Е',
		'Ё',
		'И',
		'О',
		'У',
		'Ы',
		'Э',
		'Ю',
		'Я',
	);

	static public $consonants = array(
		'Б',
		'В',
		'Г',
		'Д',
		'Ж',
		'З',
		'Й',
		'К',
		'Л',
		'М',
		'Н',
		'П',
		'Р',
		'С',
		'Т',
		'Ф',
		'Х',
		'Ц',
		'Ч',
		'Ш',
		'Щ',
	);

	static public $pairs = array(
		'Б' => 'П',
		'В' => 'Ф',
		'Г' => 'К',
		'Д' => 'Т',
		'Ж' => 'Ш',
		'З' => 'С',
	);

	static public function isHissingConsonant($consonant) {
		return in_array(lower($consonant), array('ж', 'ш', 'ч', 'щ'));
	}

	private function isVelarConsonant($consonant) {
		return in_array(lower($consonant), array('г', 'к', 'х'));
	}

	static private function isConsonant($consonant) {
		return in_array(upper($consonant), self::$consonants);
	}

	static private function isVowel($char) {
		return in_array(upper($char), self::$vowels);
	}

	public function countSyllables($string) {
		return chars_count($string, array_map(__NAMESPACE__.'\\lower', self::$vowels));
	}

	public function isPaired($consonant) {
		$consonant = lower($consonant);
		return array_key_exists($consonant, self::$pairs) || (array_search($consonant, self::$pairs) !== false);
	}

	public function checkLastConsonantSoftness($word) {
		if (($substring = last_position_for_one_of_chars(lower($word), array_map('lower', self::$consonants))) !== false) {
			if (in_array(slice($substring, 0, 1), ['й', 'ч', 'щ'])) // always soft consonants
				return true;
			else if (length($substring) > 1 && in_array(slice($substring, 1, 2), ['е', 'ё', 'и', 'ю', 'я', 'ь'])) // consonants are soft if they are trailed with these vowels
				return true;
		}
		return false;
	}

	public function choosePrepositionByFirstLetter($word, $prepositionWithVowel, $preposition) {
		if (in_array(upper(slice($word, 0, 1)), array('А', 'О', 'И', 'У', 'Э')))
			return $prepositionWithVowel;
		else
			return $preposition;
	}

	public function chooseVowelAfterConsonant($last, $soft_last, $after_soft, $after_hard) {
		if ((RussianLanguage::isHissingConsonant($last) && !in_array($last, array('ж', 'ч'))) || $this->isVelarConsonant($last) || $soft_last) {
			return $after_soft;
		} else {
			return $after_hard;
		}
	}
}
