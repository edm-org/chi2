<?php
/**
 * Created by PhpStorm.
 * User: smedina
 * Date: 28/11/13
 * Time: 11:12 AM
 */

class Chi2Test extends PHPUnit_Framework_TestCase {

  protected $chi2;
  protected $_redis;
  protected $_options = array(
    'host'  => "127.0.0.1",
    'port'  => 6379, //Redis default port: 6379
    'system'  =>  'search-site-management',
  );
  /**
   *
   */
  public function setUp()
  {
    $redis = new Redis();
    $redis->connect($this->_options['host'], $this->_options['port']);
    $this->_redis = $redis;
    Chi2::setConnection($redis);
  }

  public function tearDown()
  {

  }
  /**
   * @covers Chi2::setConnection
   * Checks that a connection to Redis is present
   */
  public function testSetConnection()
  {
    $this->hasFailed();
  }

  /**
   * @covers Chi2::increment Method,
   * Checks if the increment gets stored into Redis
   * @param $system
   * @param $keyType
   * @param $key
   * @param $statistic
   * @param $timestamp
   * @dataProvider providerTestChi2Increment
   */
  public function testChi2Increment($system,$keyType, $key, $statistic, $timestamp)
  {
    Chi2::increment($system,$keyType, $key, $statistic, $timestamp);
    $this->assertTrue(Chi2::$increment);
  }

  /**
   * @covers Chi2::increment
   * Checks that the value incremented is equal to the value expected
   * @depends testChi2Increment
   */
  //public function testChi2IncrementCheck($system,$keyType, $key, $statistic, $timestamp)
  public function testChi2IncrementCheck()
  {
    $check = $this->_redis->hget(Chi2::$timestamp,Chi2::$hkey);
    $this->assertEquals(Chi2::$increment, $check);
  }

  /**
   * @covers Chi2::getStatistic method
   * @param $system
   * @param $keyType
   * @param $key
   * @param $statistic
   * @param $time_start
   * @param $time_end
   * @param int $grain
   * @dataProvider providerTestChi2GetStatistic
   */

  public function testChi2GetStatistic($system, $keyType, $key, $statistic, $time_start, $time_end, $grain)
  {
    Chi2::getStatistic($system, $keyType, $key, $statistic, $time_start, $time_end, $grain);
    $this->assertNotNull(Chi2::$stats);
  }

  /**
   * @covers Result returned by Chi2::getStatistic
   * @depends testChi2GetStatistics
   */
  public function testChi2GetStatisticsResult()
  {
    $this->assertContains('Count', Chi2::$stats['count']);
    $this->assertAttributeNotEmpty('Time', Chi2::$stats['time']);
  }
  /**
   * Provides different values to testChi2Increment
   * @return array
   */
  public function providerTestChi2Increment()
  {
    return array(
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050513),
      /*
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050513),
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050513),
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050660),
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050660),
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050660),
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1385050660),
      */
    );
  }

  /**
   * Provides different values to testChi2GetStatistic
   * @return array
   */
  public function providerTestChi2GetStatistic()
  {
    return array(
      array('search-site-management', 'market.country', 'latam.co', 'clicks', 1384963200, 1385481600, $grain=86400),
      //array('search-site-management', 'market.country', 'latam.co', 'clicks', 1384963200, 1385481600, $grain=60),
    );
  }

}
 