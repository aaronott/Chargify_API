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
  
  /**
   * getSubscription
   *
   * @access  public
   * @param   int   $subscriptionId   Chargify Subscription ID
   * @throws  Chargify_Exception
   */
  public function getSubscription($subscriptionId)
  {
    if( ! is_numeric($subscriptionId))
    {
      throw new Chargify_Exception("Subscription ID must be numeric");
    }
    
    $endpoint = 'subscriptions/' . $subscriptionId;
    $result = $this->sendRequest($endpoint);
    
    return $result;
  }
  
  /**
   * createSubscription
   *
   *  pass an array with the required information to complete the subscription
   * <code>
   * array(
   *      'product_handle'         => $product_handle,
   *      'customer_attributes'    => array(
   *                                'first_name' => $firstname,
   *                                'last_name'  => $lastname,
   *                                'email'      => $email
   *                                ),
   *      'credit_card_attributes' => array (
   *                                'full_number' => '1',
   *                                'expiration_month' => '10',
   *                                'expiration_year'  => '2011'
   *                                ),
   *      )
   * </code>
   *
   *  Or you could pass with customer Id
   * <code>
   * array(
   *      'product_handle'         => $product_handle,
   *      'customer_id'            => $customer_id,
   *      'credit_card_attributes' => array (
   *                                'full_number' => '1',
   *                                'expiration_month' => '10',
   *                                'expiration_year'  => '2011'
   *                                ),
   *      )
   * </code>
   *
   * @access  public
   * @param   array   $subscription   Array containing subscription information
   * @throws  Chargify_Exception
   */
  public function createSubscription($subscription)
  {
    $endpoint = 'subscriptions';
    $subscription = array('subscription' => $subscription);
    $subscription_data = json_encode($subscription);
    
    $result = $this->sendRequest($endpoint, $subscription_data, 'POST');
    return $result;
  }
  
  /**
   * updateSubscription
   *
   * Use this to update a subscription.
   *
   * This could be used to update a creditcard or expiration date
   * <code>
   * array(
   *      'credit_card_attributes' => array (
   *                                'full_number' => 'new_card_number',
   *                                'expiration_month' => '10',
   *                                'expiration_year'  => '2011'
   *                                ),
   *      )
   * </code>
   *
   * This could also be used to change the produce, though no prorating will
   * occur if you change it in this manner.
   * <code>
   * array(
   *      'product_handle'         => $new_product_handle,
   *      )
   * </code>
   *
   * @access  public
   * @param   int     $subscription_id
   * @param   array   $subscription   Array containing subscription information
   * @throws  Chargify_Exception
   */
  public function updateSubscription($subscription_id, $subscription)
  {
    $endpoint = 'subscriptions/' . $subscription_id;
    $subscription = array('subscription' => $subscription);
    $subscription_data = json_encode($subscription);
    
    $result = $this->sendRequest($endpoint, $subscription_data, 'PUT');
    return $result;
  }
  
  /**
   * deleteSubscription
   *
   * Deletes (Cancels) a subscription with an optional message
   *
   * @access  public
   * @param   int     $subscription_id    Chargify Subscription ID
   * @param   string  $reason             cancelation message
   * @throws  Chargify_Exception
   */
  public function deleteSubscription($subscription_id, $reason)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription id must be numeric");
    }
    
    if($reason != ''){
      $reason = json_encode(array('subscription'=>array('cancelation_message' => $reason)));
    }
    
    $endpoint = 'subscriptions/' . $subscription_id;
    $result   = $this->sendRequest($endpoint, $reason, 'DELETE');
    
    return ($this->callInfo['http_code'] == 200);
  }
}