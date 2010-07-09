<?php
/**
 * Tests Chargify Common
 *
 * @group chargify
 *
 * @package    Chargify
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2010 Aaron Ott
 */
require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Customer.php';

class Chargify_CommonTest extends PHPUnit_Framework_TestCase
{
  
  protected $Customer;
  
  public function setUp()
  {
    $this->Customer = Chargify::factory('Customer');
    echo "Hit er";
  }
  
  /**
	 * Tests Connect
	 *
	 * Tests connection by sending through a call
	 *
	 * @test
	 */
  public function testConnect()
  {
        echo "Hit er2";

    $this->Customer->listCustomers();
    $connect = $this->Customer->callInfo;
     
    // test a simple connect
    $this->assertTrue($connect['http_code'] === 200);
  }
  
  /**
	 * Tests Connect
	 *
	 * Tests connection by sending through a call with an invalid key
	 *
	 * @test
	 */
  public function testFailedConnect()
  {
    $apikey = Chargify::$apikey;
    Chargify::$apikey .= 'x';
    $this->Customer->listCustomers();
    $connect = $this->Customer->callInfo;
    
    // test a simple connect
    $this->assertTrue($connect['http_code'] === 401);
    
    Chargify::$apikey = $apikey;
  }
}
?>