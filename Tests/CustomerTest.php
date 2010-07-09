<?php
/**
 * Tests Customer class
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

class Chargify_CustomerTest extends PHPUnit_Framework_TestCase
{

  protected $Customer;
  
  public function setUp()
  {
    $this->Customer = Chargify::factory('Customer');
  }
  
  /**
	 * Provides test data for testListCustomers()
	 *
	 * @return array
	 */
  function providerCustomer()
	{
    return array(
      array(
      'reference' => 'test',
      ),
    );
	}
  
  /**
	 * Provides test data for getCustomerById()
	 *
	 * @return array
	 */
  function providerCustomerId()
	{
    return array(
      array(
      'id' => 87058,
      ),
    );
	}
  
  /**
	 * Tests Customer::listCustomers
	 * 
	 * @test
	 * @dataProvider providerCustomer
	 * @covers Customer::listCustomers
	 * @param array $value
	 */
  public function testListCustomers($value)
  {
    $customers = $this->Customer->listCustomers();
    //$list = $this->Customer->lastResponse;
    
    //$customers = json_decode($list);
    
    $this->assertSame($value, $customers[0]->customer->reference);
    $this->assertTrue(is_array($customers));
  }
  
  /**
	 * Tests Customer::getCustomer
	 *
	 * Get a customer by reference
	 * 
	 * @test
	 * @dataProvider providerCustomerId
	 * @covers Customer::getCustomer
	 * @param array $value
	 */
  public function testGetCustomerById($value)
  {
    $customer = $this->Customer->getCustomer($value);
    
    //$customer = json_decode($response);
    
    $this->assertSame($value, $customer->customer->reference);
  }
  
  /**
	 * Tests Customer::getCustomer
	 *
	 * Get a customer by id
	 * 
	 * @test
	 * @dataProvider providerCustomer
	 * @covers Customer::getCustomer
	 * @param array $value
	 */
  public function testGetCustomerByReference($value)
  {
    
  }
}
?>