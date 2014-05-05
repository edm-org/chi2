<?php

/**
 * Chi2 is a php library for storing and retrieving pre-aggregated statistics in Redis.
 * It consists of two major parts, the client library for directly interacting with the statistics in Redis,
 * and the REST-like server for retrieving bulk data in csv or json.
 * Detailed documentation on EDM Wiki.
 */
class Chi2
{
	public static $hkey;
	public static $add;
	public static $increment;
	public static $stats;

	/**
	 * Default options array
	 * Options to be passed by default, like Redis' connection details (host and port) and System calling Chi2
	 *
	 * @var array
	 */
	private static $_options
		= array(
			'host'   => '127.0.0.1',
			'port'   => 6379, // Redis default port: 6379
			'system' => 'search-site-management',
		);

	/**
	 * Property that will hold the Redis object
	 *
	 * @var \Redis _connection
	 */
	private static $_connection = null;

	private function __construct()
	{
		// Can't construct
	}

	/**
	 * Connects to Redis using an existing Redis object $redis
	 * or tries to create a new Redis object reading the this->_options
	 *
	 * @param \Redis|null $redis Redis object
	 *
	 * @throws Exception if can't connect to redis
	 * @return boolean True if connected with Redis
	 */
	public static function setConnection(\Redis $redis = null)
	{
		if (is_null($redis)) {
			$redis = new \Redis();
			$result = $redis->connect(
				self::$_options['host'],
				self::$_options['port']
			);

			self::$_connection =& $redis;

			return $result;
		}

		self::$_connection = $redis;
		return true;
	}

	public static function ensureConnection()
	{
		if (is_null(self::$_connection)) {
			self::setConnection();
		}
	}

	/**
	 * Merge the passed options in the current options array
	 *
	 * @param array $options
	 *
	 */
	public static function setOptions(array $options)
	{
		self::$_options = array_merge(self::$_options, $options);
	}

	/**
	 * Chi2 interacts directly with a Redis Server to store stats of many varieties,
	 * Function increment(), increments a statistic given the provided parameters
	 *
	 * @param string $keyType The type of statistic being stored. Multikeys should be stored using dot notation.
	 * @param string $key The key to store against, multiple keys should be merged with dot notation. eg: 'sg.ph'
	 * @param string $statistic The name of the statistic you want to store. eg: 'clicks'
	 * @param int|float  $inc
	 * @param int|null $timestamp
	 * @param int  $granularity
	 * @param string|null $system The system calling Chi2. eg: 'search-site-management'
	 *
	 * @return float The new value
	 */
	public static function increment($keyType, $key, $statistic, $inc = 1, $timestamp = null, $granularity = 60, $system = null)
	{
		self::ensureConnection();

		$timestamp = self::_roundTime($timestamp, $granularity);

		$hashKey = self::_makeKey($keyType, $key, $statistic, $system);

		return self::$_connection->hIncrByFloat($hashKey, $timestamp, $inc);
	}

	/**
	 * Chi2 allows retrieve statistics on demand. It gets a statistic array (timestamps) based on provided parameters
	 * and the date range, using $grain to determine the granularity returned
	 *
	 * @param       $system       System that requests the stats
	 * @param       $keyType      The aggregation key types  Eg. $keyTypes = array('feed_id', 'subid')
	 * @param       $key          The aggregation values
	 * @param       $statistic    The kind of statistic that will be queried
	 * @param       $time_start   Starting point (From)
	 * @param       $time_end     Ending point (To)
	 * @param   int $granularity  Used to determine the granularity returned
	 *
	 * @return  array         return stats results, depending on the date range and granularity specified in the query
	 */
	public static function getStatistic($keyType, $key, $statistic, $time_start, $time_end = null, $granularity = 60, $system = null)
	{
		self::ensureConnection();

		if (is_null($system)) {
			$system = self::$_options['system'];
		}

		//self::_roundTimespan($)

		// Get hashKey created by _makeKey()

		// Check if the hashKey exists in Redis.

		// Get values from Redis

	}

	/**
	 * Generate the hashKey to be used when interacting with the stats (create or query)
	 *
	 * @param $system
	 * @param $keyType
	 * @param $key
	 * @param $statistic
	 *
	 * @return string
	 */
	private static function _makeKey($keyType, $key, $statistic, $system = null)
	{
		if (is_null($system)) {
			$system = self::$_options['system'];
		}

		return 'stats:' . $system . ':' . $keyType . ':' . $key . ':' . $statistic;
	}

	/**
	 * Use to round any time day/hour/minute
	 *
	 * @param $timestamp int
	 * @param $granularity int
	 *
	 * @return int
	 */
	private static function _roundTime($timestamp, $granularity)
	{
		if (is_null($timestamp)) {
			$timestamp = time();
		}

		$grain = floor($timestamp / $granularity);
		$timestamp = $grain * $granularity;

		return $timestamp;
	}

	private static function _getTimeSlices($startTime, $endTime, $granularity)
	{
		$timestamps = array();

		if (is_null($startTime)) {
			$startTime = time();
		}

		$grain = floor($startTime / $granularity);
		$startTime = $grain * $granularity;

		$time = $startTime;
		while (($time + $granularity) <= $endTime) {
			$timestamps[] = $time;
		}

		return $timestamps;
	}

	/**
	 * All queries to Chi2 must be accompanied by a X-token header
	 * Set the authentication token, which shall be calculated as follows
	 * $token = md5( $system . floor( time() / 3600 ) * 3600);
	 *
	 * @param $system string The name of the system calling Chi2
	 */
	private static function _setToken($system)
	{
		//$this->_token = md5( $system . floor( time() / 3600 ) * 3600);
	}

	/**
	 * Read the X-token header sent by the browser
	 *
	 * @param $system
	 */
	private static function _getToken($system)
	{

	}

	/**
	 * Validates token based on the name of the system and the $token
	 *
	 * @param $system
	 * @param $token\
	 *
	 * @return boolean Returns true if the token matches with the one in the System.
	 */
	private static function _verifyToken($system, $token)
	{

	}

}