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
	 * Provides test data for testCreateCustomer()
	 *
	 * @return array
	 */
  function providerCreateCustomer()
	{
    return array(
      // bad parameter
      array(
            array(
              'first_name'  => 'first2',
              'last_name'   => 'last2',
              'email'       => 'first.last@example.com',
              'bad_param'   => 'badData',
            ), 201
      ),
      // all good
      array(
            array(
              'first_name'    => 'first3',
              'last_name'     => 'last3',
              'email'         => 'first3.last3@example.com',
              'organization'  => 'org3',
            ), 201
      ),
    );
	}
  
  /**
	 * Provides test data for testCreateCustomer()
	 *
	 * @return array
	 */
  function providerUpdateCustomer()
	{
    return array(
      // bad parameter
      array( 'id' => 87688),
    );
  }
  
  /**
	 * Provides test data for testDeleteCustomer()
	 *
	 * @return array
	 */
  function providerDeleteCustomer()
	{
    return array(
      // bad parameter
      array( 'id' => 87694),
    );
  }
  
  /**
	 * Tests Chargify_Customer::listCustomers
	 * 
	 * @test
	 * @dataProvider providerCustomer
	 * @covers Chargify_Customer::listCustomers
	 * @param array $value
	 */
  public function testListCustomers($value)
  {
    $customers = $this->Customer->listCustomers();
    
    $this->assertSame($value, $customers[0]->customer->reference);
    $this->assertTrue(is_array($customers));
  }
  
  /**
	 * Tests Chargify_Customer::getCustomerById
	 *
	 * Get a customer by reference
	 * 
	 * @test
	 * @dataProvider providerCustomerId
	 * @covers Chargify_Customer::getCustomerById
	 * @param array $value
	 */
  public function testGetCustomerById($value)
  {
    $customer = $this->Customer->getCustomerById($value);
    $this->assertSame($value, $customer->customer->id);
  }
  
  /**
	 * Tests Chargify_Customer::getCustomerByReference
	 *
	 * Get a customer by id
	 * 
	 * @test
	 * @dataProvider providerCustomer
	 * @covers Chargify_Customer::getCustomerByReference
	 * @param array $value
	 */
  public function testGetCustomerByReference($value)
  {
    $customer = $this->Customer->getCustomerByReference($value);
    $this->assertSame($value, $customer->customer->reference);
  }
  
  /**
   * Tests Chargify_Customer::createCustomer
   *
   * @test
   * @dataProvider providerCreateCustomer
   * @covers        Chargify_Customer::createCustomer
   * @param         array      $customer
   * @param         int        $code      HTTP_reponse code
   */
  public function testCreateCustomer($customer, $code)
  {
    // Commented out because I don't want to go over
    // my customer limit while testing
    /**
    $newcustomer = $this->Customer->createCustomer($customer);
    $this->assertSame($customer['first_name'], $newcustomer->customer->first_name);
    $info = $this->Customer->callInfo;
    $this->assertSame($info['http_code'], $code);
    **/
  }
  
  /**
   * Tests Chargify_Customer::updateCustomer
   *
   * @test
   * @dataProvider  providerUpdateCustomer
   * @covers        Chargify_Customer::updateCustomer
   * @param         int       $id
   */
  public function testUpdateCustomer($id)
  {
    $update = array( 'customer' => array('first_name' => 'updated'));
    $updatedcustomer = $this->Customer->updateCustomer($id, $update);
    $this->assertSame($update['customer']['first_name'], $updatedcustomer->customer->first_name);
  }
  
  /**
   * Tests Chargify_Customer::deleteCustomer
   *
   * @test
   * @dataProvider  providerDeleteCustomer
   * @covers        Chargify_Customer::deleteCustomer
   * @param         int     $id   Chargify Id
   */
  public function testDeleteCustomer($id)
  {
    // Commented out because Chargify doesn't currenty
    // support delete through the API
    /**
    $deleted = $this->Customer->deleteCustomer($id);
    $this->assertTrue($deleted);
    **/
  }
}
?>