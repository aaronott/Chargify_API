<?php
/**
 * Tests Charge class
 *
 * @group chargify
 *
 * @package    Chargify
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2010 Aaron Ott
 */

require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Charge.php';

class Chargify_ChargeTest extends PHPUnit_Framework_TestCase
{

  protected $Charge;
  
  public function setUp()
  {
    $this->Charge = Chargify::factory('Charge');
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
   * Tests Chargify_Charge::chargeSubscription
   *
   * @test
   * @covers  Chargify_Charge::chargeSubscription
   * @dataProvider  providerChargeSubscription
   * @param   int   $subscription_id    Chargify Subscription Id
   * @param   array $charge
   */
  public function testChargeSubscription($subscription_id, $charge)
  {
    $result = $this->Charge->chargeSubscription($subscription_id, $charge);
    $this->assertTrue($result);
  }
  
}