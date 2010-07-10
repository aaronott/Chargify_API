<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Subscription endpoint class
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
 * Chargify_Subscription
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Subscription extends Chargify_Common
{
  /**
   * listSubscriptions
   *
   * @access public
   * @throws Chargify_Exception
   */
  public function listSubscriptions()
  {
    $endpoint = 'subscriptions';
    return $this->sendRequest($endpoint);
  }
  
  /**
   * listSubscriptionsByCustomer
   *
   * @access  public
   * @param   int   $id   Chargify Customer Id
   * @throws  Chargify_Exception
   */
  public function listSubscriptionsByCustomer($id)
  {
    if( ! is_numeric($id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $id . '/subscriptions';
    return $this->sendRequest($endpoint);
  }
}