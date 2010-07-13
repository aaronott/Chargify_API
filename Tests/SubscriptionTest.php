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
   * Tests Chargify_Subscription::listSubscriptions
   *
   * @test
   * @covers  Chargify_Subscription::listSubscriptions
   */
  public function testListSubscriptions()
  {
    $list = $this->Subscription->listSubscriptions();
    $this->assertTrue(is_array($list));
  }
  
  /**
   * Tests Chargify_Subscription::listSubscriptionsByCustomer
   *
   * @test
   * @dataProvider  providerSubscriptionCustomers
   * @param         int     $id     Chargify Customer id
   * @covers        Chargify_Subscription::listSubscriptionsByCustomer
   */
  public function testListSubscriptionsByCustomer($id)
  {
    $subscriptions = $this->Subscription->listSubscriptionsByCustomer($id);
    $this->assertTrue(is_array($subscriptions));
  }
  
  /**
   * Tests Chargify_Subscription::getSubscription
   *
   * @test
   * @dataProvider  providerSubscriptionIds
   * @param         int     $id     Chargify Subscription ID
   * @covers        Chargify_Subscription::getSubscription
   */
  public function testGetSubscription($id)
  {
    $subscription = $this->Subscription->getSubscription($id);
    $this->assertTrue(is_object($subscription));
  }
  
  /**
   * Tests Chargify_Subscription::createSubscription
   *
   * @test
   * @dataProvider  providerCreateSubscription
   * @param   array  $subscription  Subscription information
   * @covers  Chargify_Subscription::createSubscription
   */
  public function testCreateSubscription($subscription)
  {
    $result = $this->Subscription->createSubscription($subscription);
    $this->assertFalse(isset($result->errors));
    $this->assertTrue(isset($result->subscription));
  }
  
  /**
   * Tests Chargify_Subscription::updateSubscription
   *
   * @test
   * @dataProvider  providerUpdateSubscription
   * @param   int   $subscription_id    Chargify Subscription ID
   * @param   array $subscription   Subscription information
   * @covers  Chargify_Subscription::updateSubscription
   */
  public function testupdateSubscription($subscription_id, $subscription)
  {
    $result = $this->Subscription->updateSubscription($subscription_id, $subscription);
    $this->assertFalse(isset($result->errors));
    $this->assertTrue(isset($result->subscription));
  }
  
  /**
   * Test Chargify_Subscription::deleteSubscription
   *
   * @test
   * @dataProvider  providerDeleteSubscription
   * @param   int   $subscription_id    Chargify Subscription ID
   * @covers  Chargify_Subscription::deleteSubscription
   */
  public function testDeleteSubscriptoin($subscription_id)
  {
    $result = $this->Subscription->deleteSubscription($subscription_id);
    $this->assertTrue($result);
  }
}