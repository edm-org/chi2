<?php

/*
 * Chi2 is a php library for storing and retrieving pre-aggregated statistics in Redis.
 * It consists of two major parts, the client library for directly interacting with the statistics in Redis,
 * and the REST-like server for retrieving bulk data in csv or json.
 * Detailed documentation on EDM Wiki.
 */


/**
 * Class Chi2
 */
class Chi2 {

  /**
  * Default options array
  * Options to be passed by default, like Redi's connection details (Host, Port) and System calling the API
  * @var array
  */
  private static $__options = array(
    'host'  => "127.0.0.1",
    'port'  => 6379, //Redis default port: 6379
    'system'  =>  'search-site-management',
  );

  /**
   * Property that will hold the Redis object
   * @var __connection
   */
  private static $__connection;

  /**
   * @var __makeKey
   */
  private static $__makeKey;

  /**
   * @var __roundedTime
   */
  private static $__roundedTime;

  /**
   *
   * @var __token
   */
  private static $__token;

  public static $hkey;
  public static $add;
  public static $increment;
  public static $stats;

  /**
   * Connects to Redis using an existing Redis object $redis
   * or tries to create a new Redis object reading the this->__options
   * @param $redis Redis object
   * @throws Exception if can't connect to redis
   * @return boolean True if connected with Redis
   */
  public static function setConnection(\Redis $redis=null){
    // If $redis is sent then is assigned to $__connection

    // If it doesn't, tries to create the connection with Redis by using $this->__options
  }

  /**
   * Merge the passed options in the current options array
   * @param array $options
   *
   */
  public static function setOptions(array $options)
  {
    //array_merge self::$__options and $options
  }

  /**
   * Chi2 interacts directly with a Redis Server to store stats of many varieties,
   * Function increment(), increments a statistic given the provided parameters
   *
   * @param   $system     The system calling Chi2. eg: 'search-site-management'
   * @param   $keyType    The type of statistic being stored. Multikeys should be stored using dot notation.
   * @param   $key        The key to store against, multiple keys should be merged with dot notation. eg: 'sg.ph'
   * @param   $statistic  The name of the statistic you want to store. eg: 'clicks'
   * @param   $timestamp
   * @param   int $inc
   * @param   int $ttl
   * @return  boolean     Returns True if it's a new field in the Redis hash and value was set,
   */
  public static function increment($system, $keyType, $key, $statistic, $timestamp, $inc=1, $ttl=2592000)
  {

    // Before doing anything else check that the Redis connection is present

    // Round the hour __roundTime()

    // Get hashKey created by __makeKey()

    // Add the values to the hashKey in Redis.

    //$increment = self::$__connection->hset($hkey, $timestamp, $inc);

  }

  /**
   * Generate the hashKey to be used when interacting with the stats (create or query)
   *
   * @param $system
   * @param $keyType
   * @param $key
   * @param $statistic
   * @param $timestamp
   * @return string
   */
  private static function __makeKey($system, $keyType, $key, $statistic, $timestamp){
    // Generate hashKey
    return;
  }

  /**
   * Use to round any time day/hour/minute
   * @param $grain int
   * @param $time_start int
   * * @param $time_end int
   * @return  int[]
   */
  private static function __roundTime($grain=null, $time_end=null, $time_start) {
    //If the $grain and the $time_end are given, round the time to get the range

    // Else Round to hour by default, if no $grain is given
  }
  /**
   * Chi2 allows retrieve statistics on demand. It gets a statistic array (timestamps) based on provided parameters
   * and the date range, using $grain to determine the granularity returned
   *
   * @param   $system       System that requests the stats
   * @param   $keyType      The aggregation key types  Eg. $keyTypes = array('feed_id', 'subid')
   * @param   $key          The aggregation values
   * @param   $statistic    The kind of statistic that will be queried
   * @param   $time_start   Starting point (From)
   * @param   $time_end     Ending point (To)
   * @param   int $grain    Used to determine the granularity returned
   * @return  array         return stats results, depending on the date range and granularity specified in the query
   */
  public static function getStatistic($system, $keyType, $key, $statistic, $time_start, $time_end, $grain=60)
  {
    // Check Redis $connection if not present, try to connect

    // Round the hour __roundTime()

    // Get hashKey created by __makeKey()

    // Check if the hashKey exists in Redis.

    // Get values from Redis

  }

  /**
   * All queries to Chi2 must be accompanied by a X-token header
   * Set the authentication token, which shall be calculated as follows
   * $token = md5( $system . floor( time() / 3600 ) * 3600);
   * @param $system string The name of the system calling Chi2
   */
  private static function __setToken($system)
  {
    //$this->__token = md5( $system . floor( time() / 3600 ) * 3600);
  }

  /**
   * Read the X-token header sent by the browser
   * @param $system
   */
  private static function __getToken($system){

  }

  /**
   * Validates token based on the name of the system and the $token
   * @param $system
   * @param $token\
   * @return boolean Returns true if the token matches with the one in the System.
   */
  private static function __verifyToken($system, $token){

  }

}