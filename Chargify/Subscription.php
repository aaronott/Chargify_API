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
  public function all()
  {
    $endpoint = 'subscriptions';
    return $this->send_request($endpoint);
  }
  
  /**
   * by_customer
   *
   * @access  public
   * @param   int   $id   Chargify Customer Id
   * @throws  Chargify_Exception
   */
  public function by_customer($id)
  {
    if( ! is_numeric($id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $id . '/subscriptions';
    return $this->send_request($endpoint);
  }
  
  /**
   * by_id
   *
   * @access  public
   * @param   int   $subscriptionId   Chargify Subscription ID
   * @throws  Chargify_Exception
   */
  public function by_id($subscriptionId)
  {
    if( ! is_numeric($subscriptionId))
    {
      throw new Chargify_Exception("Subscription ID must be numeric");
    }
    
    $endpoint = 'subscriptions/' . $subscriptionId;
    $result = $this->send_request($endpoint);
    
    return $result;
  }
  
  /**
   * create
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
  public function create($subscription)
  {
    $endpoint = 'subscriptions';
    $subscription = array('subscription' => $subscription);
    $subscription_data = json_encode($subscription);
    
    $result = $this->send_request($endpoint, $subscription_data, 'POST');
    return $result;
  }
  
  /**
   * update
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
  public function update($subscription_id, $subscription)
  {
    $endpoint = 'subscriptions/' . $subscription_id;
    $subscription = array('subscription' => $subscription);
    $subscription_data = json_encode($subscription);
    
    $result = $this->send_request($endpoint, $subscription_data, 'PUT');
    return $result;
  }
  
  /**
   * delete
   *
   * Deletes (Cancels) a subscription with an optional message
   *
   * @access  public
   * @param   int     $subscription_id    Chargify Subscription ID
   * @param   string  $reason             cancelation message
   * @throws  Chargify_Exception
   */
  public function delete($subscription_id, $reason = '')
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription id must be numeric");
    }
    
    if($reason != ''){
      $reason = json_encode(array('subscription'=>array('cancelation_message' => $reason)));
    }
    
    $endpoint = 'subscriptions/' . $subscription_id;
    $result   = $this->send_request($endpoint, $reason, 'DELETE');
    
    return ($this->callInfo['http_code'] == 200);
  }
  
  /**
   * reactivate
   *
   * Reactivate a previously cancelled subscription
   *
   * @access  public
   * @param   int   $subscription_id    Chargify Subscription ID
   * @throw   Chargify_Exception
   */
  public function reactivate($subscription_id)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription id must be numeric");
    }
    
    $endpoint = 'subscriptions/' . $subscription_id .'/reactivate';
    $result   = $this->send_request($endpoint, '', 'PUT');
    
    return ($this->callInfo['http_code'] == 200);
  }
  
  /**
   * reset_balance
   *
   * If a subscription has a positive balance, this API call will issue a credit
   * to the subscription for the outstanding balance. This is particularly
   * helpful if you want to reactivate a canceled subscription without charging
   * the customer for their previously owed balance.
   *
   * @access  public
   * @param   int   $subscription_id    Chargify Subscription ID
   * @throw   Chargify_Exception
   */
  public function reset_balance($subscription_id)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription id must be numeric");
    }
    
    $endpoint = 'subscriptions/' . $subscription_id .'/reset_balance';
    $result   = $this->send_request($endpoint, '', 'PUT');
    
    return ($this->callInfo['http_code'] == 200);
  }
  
  /**
   * charge
   *
   * @access public
   * @param   int   $subscription_id    Chargify Subscription ID
   * @param   array $charge   
   * @throws Chargify_Exception
   */
  public function charge($subscription_id, $charge)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception('subscription id must be numeric');
    }
    
    $endpoint = 'subscriptions/' . $subscription_id . '/charges';
    $charge = json_encode(array('charge' => $charge));
    $result = $this->send_request($endpoint, $charge, 'POST');
    
    return ($this->callInfo['http_code'] == 201);
  }
  
  /**
   * credit
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
  public function credit($subscription_id, $credit)
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
    
    $result = $this->send_request($endpoint, $credit, 'POST');
    
    return ($this->callInfo['http_code'] == 201);
  }
  
  /**
   * prorate
   *
   * Allows for a subscription to be upgraded or downgraded
   *
   * The prorate data should include
   * <code>
   *  array(
   *    'product_id'              => 1234,
   *    OR
   *    'product_handle'          => 'handle1234', // handle of product that the
   *                                               // subscription will be moved to
   *
   *    'include_trial'           => 0,  // If 1 is sent the customer will
   *                                     // migrate to the new product with a
   *                                     // trial if one is available. If 0 is sent,
   *                                     // the trial period will be ignored.
   *
   *    'include_initial_charge'  => 0   // If 1 is sent initial charges will be
   *                                     // assessed. If 0 is sent initial charges
   *                                     // will be ignored.
   *  )
   * </code>
   * 
   * @access  public
   * @param   int     $subscription_id    Chargify Subscription ID
   * @param   array   $prorate            Prorate data
   * @throws  Chargify_Exception
   */
  public function prorate($subscription_id, $prorate)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription ID must be numeric");
    }
    
    if( ! isset($prorate['product_id']) && ! isset($prorate['product_handle']))
    {
      throw new Chargify_Exception("You must set either product_id or product_handle");
    }
    
    if( isset($prorate['product_id']) && ! is_numeric($prorate['product_id']))
    {
      throw new Chargify_Exception("product_id must be numeric");
    }
    
    $endpoint = "subscriptions/".$subscription_id."/migrations";
    $prorate = json_encode($prorate);
    $result = $this->send_Request($endpoint, $prorate, 'POST');

    return ($this->callInfo['http_code'] == 200);
  }
}