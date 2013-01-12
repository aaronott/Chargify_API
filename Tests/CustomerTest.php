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
   * Tests Chargify_Customer::all
   *
   * @test
   * @dataProvider providerCustomer
   * @covers Chargify_Customer::all
   * @param array $value
   */
  public function testCustomersAll($value)
  {
    $customers = $this->Customer->all();

    $this->assertSame($value, $customers[0]->customer->reference);
    $this->assertTrue(is_array($customers));
  }

  /**
   * Tests Chargify_Customer::by_id
   *
   * Get a customer by reference
   *
   * @test
   * @dataProvider providerCustomerId
   * @covers Chargify_Customer::by_id
   * @param array $value
   */
  public function testById($value)
  {
    $customer = $this->Customer->by_id($value);
    $this->assertSame($value, $customer->customer->id);
  }

  /**
   * Tests Chargify_Customer::by_reference
   *
   * Get a customer by id
   *
   * @test
   * @dataProvider providerCustomer
   * @covers Chargify_Customer::by_reference
   * @param array $value
   */
  public function testByReference($value)
  {
    $customer = $this->Customer->by_reference($value);
    $this->assertSame($value, $customer->customer->reference);
  }

  /**
   * Tests Chargify_Customer::create
   *
   * @test
   * @dataProvider providerCreateCustomer
   * @covers        Chargify_Customer::create
   * @param         array      $customer
   * @param         int        $code      HTTP_reponse code
   */
  public function testCreate($customer, $code)
  {
    // Commented out because I don't want to go over
    // my customer limit while testing
    /**
    $newcustomer = $this->Customer->create($customer);
    $this->assertSame($customer['first_name'], $newcustomer->customer->first_name);
    $info = $this->Customer->callInfo;
    $this->assertSame($info['http_code'], $code);
    **/
  }

  /**
   * Tests Chargify_Customer::update
   *
   * @test
   * @dataProvider  providerUpdateCustomer
   * @covers        Chargify_Customer::update
   * @param         int       $id
   */
  public function testUpdate($id)
  {
    $update = array( 'customer' => array('first_name' => 'updated'));
    $updatedcustomer = $this->Customer->update($id, $update);
    $this->assertSame($update['customer']['first_name'], $updatedcustomer->customer->first_name);
  }

  /**
   * Tests Chargify_Customer::delete
   *
   * @test
   * @dataProvider  providerDeleteCustomer
   * @covers        Chargify_Customer::delete
   * @param         int     $id   Chargify Id
   */
  public function testDelete($id)
  {
    // Commented out because Chargify doesn't currenty
    // support delete through the API
    /**
    $deleted = $this->Customer->delete($id);
    $this->assertTrue($deleted);
    **/
  }
}
?>
