<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Transaction endpoint class
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
 * @link        http://support.chargify.com/faqs/api/api-transactions
 */

/**
 * Chargify_Transaction
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Transaction extends Chargify_Common
{
  
  /**
   * Allowed kinds filter
   *
   * @access private
   * @var array $allowed_kinds
   */
  private $_allowed_kinds = array('charge', 'refund', 'payment', 'credit',
                           'payment_authorization', 'info', 'adjustment');
  
  /**
   * sanitizeFilters
   *
   * @access private
   * @param $filters
   * @throws Chargify_Exception
   */
  private function _sanitizeFilters($filters)
  {
    // sanatize the filters
    if(isset($filters['kinds']))
    {
      $kinds = array();
      foreach($filters['kinds'] as $kind)
      {
        if(in_array($kind, $this->_allowed_kinds))
        {
          $kinds[] = $kind;
        }
      }
      $filters['kinds'] = $kinds;
    }
    
    if(isset($filters['since_id']) && ! is_numeric($filters['since_id']))
    {
      throw new Chargify_Exception('since_id must be numerid');
    }
    
    if(isset($filters['max_id']) && ! is_numeric($filters['max_id']))
    {
      throw new Chargify_Exception('max_id must be numerid');
    }
    
    if(isset($filters['page']) && ! is_numeric($filters['page']))
    {
      throw new Chargify_Exception('page must be numerid');
    }
    
    if(isset($filters['per_page']) && ! is_numeric($filters['per_page']))
    {
      throw new Chargify_Exception('per_page must be numerid');
    }
    
    return $filters;
  }
  
  /**
   * by_site
   *
   * allows you to view all transactions for a site
   *
   * *Filters*
   * All filters are optional
   * <code>
   * array(
   *  'kinds' => array(charge, refund, payment, credit,
   *                   payment_authorization, info, adjustment), // you may include 0 to all 7 kinds
   *  'since_id'    =>  int $subscription_id, // includes ids greater than or equal to this
   *  'max_id'      =>  int $subscription_id, // includes ids less than or equal to this
   *  'since_date'  =>  string YYYY-MM-DD,  // returns transactions *created_at* or greater than this date
   *  'until_date'  =>  string YYYY-MM-DD,  // returns transactions *created_at* or less than this date
   *  'page'        =>  int // used for pagination
   *  'per_page'    =>  int // defaults to 20 transactions per page
   * )
   * </code>
   * 
   * @access  public
   * @param   array  $filters   Optional filters
   * @throws  Chargify_Exception
   */
  public function by_site($filters = array())
  {
    $sanitized_filters = array();
    
    if(! empty($filters))
    {
      $sanitized_filters = $this->_sanitizeFilters($filters);
    }
    
    $endpoint = "transactions";
    $result = $this->send_request($endpoint, $sanitized_filters);
    
    return $result;
  }
  
  /**
   * by_subscription
   *
   * allows you to view all transactions for a given subscription
   *
   * *Filters*
   * All filters are optional
   * <code>
   * array(
   *  'kinds' => array(charge, refund, payment, credit,
   *                   payment_authorization, info, adjustment), // you may include 0 to all 7 kinds
   *  'since_id'    =>  int $subscription_id, // includes ids greater than or equal to this
   *  'max_id'      =>  int $subscription_id, // includes ids less than or equal to this
   *  'since_date'  =>  string YYYY-MM-DD,  // returns transactions *created_at* or greater than this date
   *  'until_date'  =>  string YYYY-MM-DD,  // returns transactions *created_at* or less than this date
   *  'page'        =>  int // used for pagination
   *  'per_page'    =>  int // defaults to 20 transactions per page
   * )
   * </code>
   * 
   * @access  public
   * @param   int    $subscription_id   Optional filters
   * @param   array  $filters   Optional filters
   * @throws  Chargify_Exception
   */
  public function by_subscription($subscription_id, $filters = array())
  {
    $sanitized_filters = array();
    
    if(! empty($filters))
    {
      $sanitized_filters = $this->_sanitizeFilters($filters);
    }
    
    $endpoint = "subscriptions/" . $subscription_id . "/transactions";
    $result = $this->send_request($endpoint, $sanitized_filters);
    
    return $result;
  }
}