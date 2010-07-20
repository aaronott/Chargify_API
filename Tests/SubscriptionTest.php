<?php
/**
 * Tests Product class
 *
 * @group chargify
 *
 * @package    Chargify
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2010 Aaron Ott
 */

require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Subscription.php';

class Chargify_SubscriptionTest extends PHPUnit_Framework_TestCase
{
  protected $Subscription;
  
  public function setUp()
  {
    $this->Subscription = Chargify::factory('Subscription');
  }
  
  /**
   * provider for listSubscriptionsByCustomer
   *
   */
  public function providerSubscriptionCustomers()
  {
    return array(
      array('id' => 87058,),
    );
  }
  
  /**
   * provider for getSubscription
   *
   */
  public function providerSubscriptionIds()
  {
    return array(
      array('id' => 88957,),
    );
  }
  
  /**
   * provider for createSubscription
   */
  public function providerCreateSubscription()
  {
    /*
      {"subscription":{
        "product_handle":"[@product.handle]",
        "customer_attributes":{
          "first_name":"Joe",
          "last_name":"Blow",
          "email":"joe@example.com"
        },
        "credit_card_attributes":{
          "full_number":"1",
          "expiration_month":"10",
          "expiration_year":"2020"
        }
      }}
      
      
      {"subscription":{
        "product_handle":"[@product.handle]",
        "customer_id":"[@customer.id]",
        "credit_card_attributes":{
          "full_number":"1",
          "expiration_month":"10",
          "expiration_year":"2020"
        }
      }}
      
      {"subscription":{
        "product_handle":"[@product.handle]",
        "customer_reference":"[@customer.reference]",
        "credit_card_attributes":{
          "full_number":"1",
          "expiration_month":"10",
          "expiration_year":"2020"
        }
      }}
    */    
    return array(
      array(
        array(
            'product_handle'      => 'level-1',
            'customer_attributes' => array(
                    'first_name' => 'updated',
                    'last_name'  => 'last2',
                    'email'      => 'first.last@example.com'
                  ),
            'credit_card_attributes' => array (
                                               'full_number' => '1',
                                               'expiration_month' => '10',
                                               'expiration_year'  => '2011'
                                               ),
            ),
      )
    );
  }
  
  /**
   * provider for updateSubscription
   */
  public function providerUpdateSubscription()
  {
    return array(
      array( 88957,
        array(
            'credit_card_attributes' => array (
                                               'full_number' => '1',
                                               'expiration_month' => '12',
                                               'expiration_year'  => '2011'
                                               ),
            ),
      )
    );
  }
  
  /**
   * provider for deleteSubscription
   */
  public function providerDeleteSubscription()
  {
    return array(
                  array(89738)
                 );
  }

  /**
   * provider for resetBalance
   */
  public function providerResetBalance()
  {
    return array(
                  array(88957)
                 );
  }
  
  /**
   * Tests Chargify_Subscription::all
   *
   * @test
   * @covers  Chargify_Subscription::all
   */
  public function testSubscriptionsAll()
  {
    $list = $this->Subscription->all();
    $this->assertTrue(is_array($list));
  }
  
  /**
   * Tests Chargify_Subscription::by_customer
   *
   * @test
   * @dataProvider  providerSubscriptionCustomers
   * @param         int     $id     Chargify Customer id
   * @covers        Chargify_Subscription::by_customer
   */
  public function testByCustomer($id)
  {
    $subscriptions = $this->Subscription->by_customer($id);
    $this->assertTrue(is_array($subscriptions));
  }
  
  /**
   * Tests Chargify_Subscription::by_id
   *
   * @test
   * @dataProvider  providerSubscriptionIds
   * @param         int     $id     Chargify Subscription ID
   * @covers        Chargify_Subscription::by_id
   */
  public function testById($id)
  {
    $subscription = $this->Subscription->by_id($id);
    $this->assertTrue(is_object($subscription));
  }
  
  /**
   * Tests Chargify_Subscription::create
   *
   * @test
   * @dataProvider  providerCreateSubscription
   * @param   array  $subscription  Subscription information
   * @covers  Chargify_Subscription::create
   */
  
  public function testCreate($subscription)
  {
    $result = $this->Subscription->create($subscription);
    $this->assertFalse(isset($result->errors));
    $this->assertTrue(isset($result->subscription));
  }
  
  /**
   * Tests Chargify_Subscription::update
   *
   * @test
   * @dataProvider  providerUpdateSubscription
   * @param   int   $subscription_id    Chargify Subscription ID
   * @param   array $subscription   Subscription information
   * @covers  Chargify_Subscription::update
   */
  public function testUpdate($subscription_id, $subscription)
  {
    $result = $this->Subscription->update($subscription_id, $subscription);
    $this->assertFalse(isset($result->errors));
    $this->assertTrue(isset($result->subscription));
  }
  
  /**
   * Test Chargify_Subscription::delete
   *
   * @test
   * @dataProvider  providerDeleteSubscription
   * @param   int   $subscription_id    Chargify Subscription ID
   * @covers  Chargify_Subscription::delete
   */
  public function testDelete($subscription_id)
  {
    $result = $this->Subscription->delete($subscription_id);
    $this->assertTrue($result);
  }
  
  /**
   * Test Chargify_Subscription::reactivate
   *
   * @test
   * @dataProvider  providerDeleteSubscription
   * @param   int   $subscription_id    Chargify Subscription ID
   * @covers  Chargify_Subscription::reactivate
   */
  public function testReactivate($subscription_id)
  {
    $result = $this->Subscription->reactivate($subscription_id);
    $this->assertTrue(isset($result->subscription));
  }
  
  /**
   * Test Chargify_Subscription::reset_balance
   *
   * @test
   * @dataProvider  providerResetBalance
   * @param   int   $subscription_id    Chargify Subscription ID
   * @covers  Chargify_Subscription::reset_balance
   */
  
  public function testResetBalance($subscription_id)
  {
    $result = $this->Subscription->reset_balance($subscription_id);
    $this->assertTrue(isset($result->subscription));
  }
  
    /**
   * Provider for chargeSubscription
   *
   * @return array
   */
  public function providerChargeSubscription()
  {
    return array(
                 array(
                       88957,
                       array(
                        'amount' => '1.00',
                        'memo'   => 'This is a credit',
                       )
                      ),
                 );
  }
  
  /**
   * Tests Chargify_Charge::charge
   *
   * @test
   * @covers  Chargify_Charge::charge
   * @dataProvider  providerChargeSubscription
   * @param   int   $subscription_id    Chargify Subscription Id
   * @param   array $charge
   */
  public function testCharge($subscription_id, $charge)
  {
    $result = $this->Subscription->charge($subscription_id, $charge);
    $this->assertTrue(isset($result->charge));
    $this->assertTrue((bool)$result->charge->success);
  }
  
    /**
   * Dataprovider for creditSubscription
   */
  public function providerCredit()
  {
    return array(
                 array(
                       88957,
                       array(
                        'amount' => '1.00',
                        'memo'   => 'This is a credit',
                       )
                      ),
                 );
  }
  
  /**
   * Tests Chargify_Credit::credit
   *
   * @test
   * @dataProvider    providerCredit
   * @param           int       $subscription_id    Chargify Subscription ID
   * @param           array     $credit             Credit information array
   * @covers          Chargify_Credit::credit
   */
  public function testCredit($subscription_id, $credit)
  {
    $result = $this->Subscription->credit($subscription_id, $credit);
    $this->assertTrue(isset($result->credit));
    $this->assertTrue((bool)$result->credit->success);
  }
  
  /**
   * Provider for prorateSubscription
   *
   * @return array
   */
  function providerProductId()
  {
    return array(
                 array(
                       88957,
                       array(
                        'product_id' => 6765,
                       )
                      ),
                 );
  }
  
  /**
   * Tests Chargify_Prorate::prorate
   *
   * @test
	 * @covers Chargify_Prorate::prorate
	 * @dataProvider	providerProductId
	 * @param		int		$subscription_id		Chargify Subscription
	 * @param		array	$prorate						Prorate data
	 */
  function testProrate($subscription_id, $prorate)
  {
    $result = $this->Subscription->prorate($subscription_id, $prorate);
    $this->assertTrue(isset($result->subscription));
  }
  
}