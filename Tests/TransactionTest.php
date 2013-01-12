<?php
/**
 * Tests Transaction class
 *
 * @group chargify
 *
 * @package    Chargify
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2010 Aaron Ott
 */

require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Transaction.php';

class Chargify_TransactionTest extends PHPUnit_Framework_TestCase
{

  protected $Transaction;

  public function setUp()
  {
    $this->Transaction = Chargify::factory('Transaction');
  }

  /**
   * Provider for listTransactionsBySite
   */
  public function providerTransaction()
  {
    return array(
                 array(
                  array(
                    'kinds' => array('charge'),
                  ),
                 ),
                );
  }

  /**
   * Provider for listTransactionsBySite
   */
  public function providerTransactionBySubscription()
  {
    return array(
                 array(89738,
                  array(
                    'kinds' => array('charge'),
                  ),
                 ),
                );
  }

  /**
   * Tests Chargify_Transaction::by_site
   *
   * @test
   * @dataProvider  providerTransaction
   * @param   array $filters
   * @covers Chargify_Transaction::by_site
   */
  function testBySite($filters)
  {
    $result = $this->Transaction->by_site($filters);
    $this->assertTrue(is_array($result));
  }

  /**
   * Tests Chargify_Transaction::by_subscription
   *
   * @test
   * @dataProvider  providerTransactionBySubscription
   * @param   int   $subscription_id
   * @param   array $filters
   * @covers Chargify_Transaction::by_subscription
   */
  function testBySubscription($subscription_id,$filters)
  {
    $result = $this->Transaction->by_subscription($subscription_id,$filters);
    $this->assertTrue(is_array($result));
  }
}

