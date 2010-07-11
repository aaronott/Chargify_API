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
  function providerSubscriptionCustomers()
  {
    return array(
      array('id' => 87058,),
    );
  }
  
  /**
   * Tests Chargify_Subscription::listSubscriptions
   *
   * @test
   * @covers  Chargify_Subscription::listSubscriptions
   */
  function testListSubscriptions()
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
  function testListSubscriptionsByCustomer($id)
  {
    $subscriptions = $this->Subscription->listSubscriptionsByCustomer($id);
    $this->assertTrue(is_array($subscriptions));
  }
}