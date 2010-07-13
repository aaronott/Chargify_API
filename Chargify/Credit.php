<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Credit endpoint class
 *
 * PHP version 5.1.0+
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 * @copyright   2010 Aaron Ott
 */

/**
 * Chargify_Credit
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Credit extends Chargify_Common
{
  /**
   * creditSubscription
   *
   * credit must contain the following
   * <code>
   *  array(
   *    'amount' => '1.00',
   *    'memo'   => 'Example of a memo message'
   *  )
   * </code>
   * 
   * @access public
   * @param   int   $subscription_id    Chargify Subscription id
   * @param   array $credit
   * @throws Chargify_Exception
   */
  public function creditSubscription($subscription_id, $credit)
  {
    if( ! isset($credit['amount']) || ! is_string($credit['amount']))
    {
      throw new Chargify_Exception('Amount must be a string: "10.00"');
    }
    
    if( ! isset($credit['memo']) || $credit['amount'] == '')
    {
      throw new Chargify_Exception('memo cannot be an empty string');
    }
    
    $endpoint = 'subscriptions/' . $subscription_id . '/credits';
    $credit = json_encode(array('credit' => $credit));
    
    $result = $this->sendRequest($endpoint, $credit, 'POST');
    
    return ($this->callInfo['http_code'] == 201);
  }
}