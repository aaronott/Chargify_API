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
   * Tests Subscription::listSubscriptions
   *
   * @test
   * @covers  Subscription::listSubscriptions
   */
  function testListSubscriptions()
  {
    $list = $this->Subscription->listSubscriptions();
    $this->assertTrue(is_array($list));
  }
  
  /**
   * Tests Subscription::listSubscriptionsByCustomer
   *
   * @test
   * @dataProvider  providerSubscriptionCustomers
   * @param         int     $id     Chargify Customer id
   * @covers        Subscription::listSubscriptionsByCustomer
   */
  function testListSubscriptionsByCustomer($id)
  {
    $subscriptions = $this->Subscription->listSubscriptionsByCustomer($id);
    $this->assertTrue(is_array($subscriptions));
  }
}