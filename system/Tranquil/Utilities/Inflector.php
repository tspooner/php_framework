<?php namespace Tranquil\Utilities;

/**
 * undocumented class
 *
 * @package default
 **/
class Inflector
{
	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_plural = array(
		'/(quiz)$/i' => "$1zes",
		'/^(ox)$/i' => "$1en",
		'/([m|l])ouse$/i' => "$1ice",
		'/(matr|vert|ind)ix|ex$/i' => "$1ices",
		'/(x|ch|ss|sh)$/i' => "$1es",
		'/([^aeiouy]|qu)y$/i' => "$1ies",
		'/(hive)$/i' => "$1s",
		'/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
		'/(shea|lea|loa|thie)f$/i' => "$1ves",
		'/sis$/i' => "ses",
		'/([ti])um$/i' => "$1a",
		'/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
		'/(bu)s$/i' => "$1ses",
		'/(alias)$/i' => "$1es",
		'/(octop)us$/i' => "$1i",
		'/(ax|test)is$/i' => "$1es",
		'/(us)$/i' => "$1es",
		'/s$/i' => "s",
		'/$/' => "s"
	);

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_singular = array(
		'/(quiz)zes$/i' => "$1",
		'/(matr)ices$/i' => "$1ix",
		'/(vert|ind)ices$/i' => "$1ex",
		'/^(ox)en$/i' => "$1",
		'/(alias)es$/i' => "$1",
		'/(octop|vir)i$/i' => "$1us",
		'/(cris|ax|test)es$/i' => "$1is",
		'/(shoe)s$/i' => "$1",
		'/(o)es$/i' => "$1",
		'/(bus)es$/i' => "$1",
		'/([m|l])ice$/i' => "$1ouse",
		'/(x|ch|ss|sh)es$/i' => "$1",
		'/(m)ovies$/i' => "$1ovie",
		'/(s)eries$/i' => "$1eries",
		'/([^aeiouy]|qu)ies$/i' => "$1y",
		'/([lr])ves$/i' => "$1f",
		'/(tive)s$/i' => "$1",
		'/(hive)s$/i' => "$1",
		'/(li|wi|kni)ves$/i' => "$1fe",
		'/(shea|loa|lea|thie)ves$/i' => "$1f",
		'/(^analy)ses$/i' => "$1sis",
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
		'/([ti])a$/i' => "$1um",
		'/(n)ews$/i' => "$1ews",
		'/(h|bl)ouses$/i' => "$1ouse",
		'/(corpse)s$/i' => "$1",
		'/(us)es$/i' => "$1",
		'/(us|ss)$/i' => "$1",
		'/s$/i' => ""
	);

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_uncountable = array(
		'Amoyese', 'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus',
		'carp', 'chassis', 'clippers', 'cod', 'coitus', 'Congoese', 'contretemps', 'corps',
		'debris', 'diabetes', 'djinn', 'eland', 'elk', 'equipment', 'Faroese', 'flounder',
		'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
		'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings',
		'jackanapes', 'Kiplingese', 'Kongoese', 'Lucchese', 'mackerel', 'Maltese', '.*?media',
		'mews', 'moose', 'mumps', 'Nankingese', 'news', 'nexus', 'Niasese',
		'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese',
		'proceedings', 'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors',
		'sea[- ]bass', 'series', 'Shavese', 'shears', 'siemens', 'species', 'swine', 'testes',
		'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese', 'whiting', 'wildebeest',
		'Yengeese'
	);

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_irregular = array(
		'atlas' => 'atlases',
		'beef' => 'beefs',
		'brother' => 'brothers',
		'cafe' => 'cafes',
		'child' => 'children',
		'corpus' => 'corpuses',
		'cow' => 'cows',
		'die' => 'dice',
		'ganglion' => 'ganglions',
		'genie' => 'genies',
		'genus' => 'genera',
		'graffito' => 'graffiti',
		'hoof' => 'hoofs',
		'loaf' => 'loaves',
		'man' => 'men',
		'money' => 'monies',
		'mongoose' => 'mongooses',
		'move' => 'moves',
		'mythos' => 'mythoi',
		'niche' => 'niches',
		'numen' => 'numina',
		'occiput' => 'occiputs',
		'octopus' => 'octopuses',
		'opus' => 'opuses',
		'ox' => 'oxen',
		'penis' => 'penises',
		'person' => 'people',
		'sex' => 'sexes',
		'soliloquy' => 'soliloquies',
		'testis' => 'testes',
		'trilby' => 'trilbys',
		'turf' => 'turfs'
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function pluralize($singular)
	{
		if (in_array(strtolower($singular), static::$_uncountable))
			return $singular;

		foreach (static::$_irregular as $rule => $val)
		{
			$rule = "/{$rule}\$/i";

			if (preg_match($rule, $singular))
				return preg_replace($rule, $val, $singular);
		}

		foreach (static::$_plural as $rule => $val)
		{
			if (preg_match($rule, $singular))
				return preg_replace($rule, $val, $singular);
		}

		return $singular;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function singularize($plural)
	{
		if (in_array(strtolower($plural), static::$_uncountable))
			return $plural;

		foreach (static::$_irregular as $val => $rule)
		{
			$rule = "/{$rule}\$/i";

			if (preg_match($rule, $plural))
				return preg_replace($rule, $val, $plural);
		}

		foreach (static::$_singular as $rule => $val)
		{
			if (preg_match($rule, $plural))
				return preg_replace($rule, $val, $plural);
		}

		return $plural;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function pluralizeIf($count, $string)
	{
		if ($count == 1)
			return "1 $string";
		else
			return "$count " . self::$pluralize($string);
	}
} // END class Inflector