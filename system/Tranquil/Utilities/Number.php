<?php namespace Tranquil\Utilities;

/**
*
*/
class Number
{
	public static function isNumeric($number)
	{
		return is_numeric(self::stripNumber($number));
	}

	public static function isInteger($number)
	{
		return is_int(self::stripNumber($number));
	}

	public static function isDecimal($number)
	{
		return is_float(self::stripNumber($number));
	}

	public static function compareFloat($alpha, $beta, $delta = 0.00001)
	{
		if (abs($alpha - $beta) < $delta) return true;

		return false;
	}

	public static function weightedRandom($numbers)
	{
		$total = 0;

		foreach ($numbers as $number => $weight)
		{
			$total += $weight;
			$distribution[$number] = $total;
		}

		$rand = mt_rand(0, $total - 1);

		foreach ($distribution as $number => $weights)
		{
			if ($rand < $weights) return $number;
		}
	}

	public static function stripNumber($number)
	{
		return str_replace(',', '', $number);
	}
}