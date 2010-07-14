<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Charge endpoint class
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
 * Chargify_Charge
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Charge extends Chargify_Common
{
  /**
   * chargeSubscription
   *
   * @access public
   * @param   int   $subscription_id    Chargify Subscription ID
   * @param   array $charge   
   * @throws Chargify_Exception
   */
  public function chargeSubscription($subscription_id, $charge)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception('subscription id must be numeric');
    }
    
    $endpoint = 'subscriptions/' . $subscription_id . '/charges';
    $charge = json_encode(array('charge' => $charge));
    $result = $this->sendRequest($endpoint, $charge, 'POST');
    
    return ($this->callInfo['http_code'] == 201);
  }
}