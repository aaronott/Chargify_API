<?php


require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Customer.php';

class Chargify_CustomerTest extends PHPUnit_Framework_TestCase
{

  protected $Customer;
  
  public function setUp()
  {
    $this->Customer = Chargify::factory('Customer');
    
  }
  
  public function testListCustomers()
  {
    $this->Customer->listCustomers();
    $list = $this->Customer->lastResponse;
    
    $customers = json_decode($list);
    $this->assertTrue(is_array($customers));
  }
}
?>