<?php
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
 * @link        http://support.chargify.com/faqs/api/api-subscriptions
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
   * @access    public
   * @return    array   array of all subscription objects
   * @throws    Chargify_Exception
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
   * @param   int   $customer_id   Chargify Customer Id
   * @return  array   Array of objects matching the customer id
   * @throws  Chargify_Exception
   */
  public function by_customer($customer_id)
  {
    if( ! is_numeric($customer_id))
    {
      throw new Chargify_Exception("customer id must be numeric");
    }
    
    $endpoint = 'customers/' . $customer_id . '/subscriptions';
    return $this->send_request($endpoint);
  }
  
  /**
   * by_id
   *
   * @access  public
   * @param   int   $subscription_id   Chargify Subscription ID
   * @return  object    Object matching the passed subscription id
   * @throws  Chargify_Exception
   */
  public function by_id($subscription_id)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception("Subscription ID must be numeric");
    }
    
    $endpoint = 'subscriptions/' . $subscription_id;
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
   * @return  object  Newly created subscription object
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
   * @return  object  updated subscription object
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
   * @return  boolean TRUE if subscription was deleted
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
   * @return  updated subscription object
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
    
    if($this->callInfo != 200)
    {
      throw new Chargify_Exception("Unable to reactivate subscription. Called:". $this->lastCall." Code:" . $this->callInfo['http_code'] . " Response:" . $this->lastResponse);
    }
    
    return $result;
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
   * @return  updated subscription object
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
    
    if($this->callInfo != 200)
    {
      throw new Chargify_Exception("Unable to reactivate subscription. Called:". $this->lastCall." Code:" . $this->callInfo['http_code'] . " Response:" . $this->lastResponse);
    }
    
    return $result;
  }
  
  /**
   * charge
   *
   * You must include either 'amount' or 'amount_in_cents' and 'memo'
   * <code>
   * array(
   *  amount => Amount to charge $10.00 would be represented as a string '10.00',
   *  OR
   *  amount_in_cents => Amount to charge in cents $10.00 would be 1000 passed as an int,
   *  memo  => reason for the charge
   * )
   * </code>
   * @access public
   * @param   int   $subscription_id    Chargify Subscription ID
   * @param   array $charge
   * @return  object  charge object
   * @link    http://support.chargify.com/faqs/api/api-charges#api-usage-json-charges-create
   * @throws Chargify_Exception
   */
  public function charge($subscription_id, $charge)
  {
    if( ! is_numeric($subscription_id))
    {
      throw new Chargify_Exception('subscription id must be numeric');
    }
    
    if( !(isset($charge['amount']) XOR isset($charge['amount_in_cents']))
        || ! isset($charge['memo']))
    {
      throw new Chargify_Exception("Missing required parameters");
    }
    
    $endpoint = 'subscriptions/' . $subscription_id . '/charges';
    $charge = json_encode(array('charge' => $charge));
    $result = $this->send_request($endpoint, $charge, 'POST');
    
    if(isset($result->errors))
    {
      throw new Chargify_Exception("The following error was returned from Chargify: " . $result->errors[0]);
    }
    
    if($this->callInfo != 201)
    {
      throw new Chargify_Exception("Unable to charge subscription. Called:". $this->lastCall." Code:" . $this->callInfo['http_code'] . " Response:" . $this->lastResponse);
    }
    
    return $result;
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
   * @return  credit object if success
   * @throws  Chargify_Exception
   * @link    http://support.chargify.com/faqs/api/api-credits
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
    
    if($this->callInfo != 201)
    {
      throw new Chargify_Exception("Unable to charge subscription. Called:". $this->lastCall." Code:" . $this->callInfo['http_code'] . " Response:" . $this->lastResponse);
    }
    
    return $result;
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
   * @link    http://support.chargify.com/faqs/api/api-prorated-upgrades-downgrades
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
    
    if($this->callInfo != 200)
    {
      throw new Chargify_Exception("Unable to charge subscription. Called:". $this->lastCall." Code:" . $this->callInfo['http_code'] . " Response:" . $this->lastResponse);
    }
    
    return $result;
  }
}