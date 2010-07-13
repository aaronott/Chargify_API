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
require_once 'Chargify/Credit.php';

class Chargify_CreditTest extends PHPUnit_Framework_TestCase
{

  protected $Credit;
  
  public function setUp()
  {
    $this->Credit = Chargify::factory('Credit');
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
   * Tests Chargify_Credit::creditSubscription
   *
   * @test
   * @dataProvider    providerCredit
   * @param           int       $subscription_id    Chargify Subscription ID
   * @param           array     $credit             Credit information array
   * @covers          Chargify_Credit::creditSubscription
   */
  public function testCreditSubscription($subscription_id, $credit)
  {
    $result = $this->Credit->creditSubscription($subscription_id, $credit);
    $this->assertTrue($result);
  }
}