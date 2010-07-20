<?php
/**
 * Customer endpoint class
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
 * @link        http://support.chargify.com/faqs/api/api-customers
 */

/**
 * Chargify_Customer
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Customer extends Chargify_Common
{
  /**
   * all
   *
   * @access public
   * @throws Chargify_Exception
   * @return array  Array of all customer objects
   */
  public function all()
  {
    $endpoint = 'customers';
    return $this->send_request($endpoint);
  }
  
  /**
   * by_id
   *
   * @access  public
   * @param   integer   $customer_id     Chargify Customer Id
   * @return  object    Object matching the passed id
   * @throws  Chargify_Exception
   */
  public function by_id($customer_id)
  {
    if(!is_numeric($customer_id))
    {
      throw new Chargify_Exception("customer id must be numeric");
    }
    
    $endpoint = 'customers/' . $customer_id;
    return $this->send_request($endpoint);
  }
  
  /**
   * by_reference
   *
   * @access  public
   * @param   string    $value    Customer reference
   * @return  object    Object matching the passed id
   * @throws  Chargify_Exception
   */
  public function by_reference($value)
  {
    $endpoint = 'customers/lookup';
    $data     = array('reference' => $value);
    return $this->send_request($endpoint, $data);
  }
  
  /**
   * create
   *
   * To create a customer, pass an array with the following data
   * array (
   *  "first_name"  => "first name",    // (REQUIRED)
   *  "last_name"   => "last name",     // (REQUIRED)
   *  "email"       => "email address", // (REQUIRED)
   *  "organization"=> "company name",
   *  "reference"   => "internal id"
   * )
   * @access    public
   * @param     array   $customer   Customer data
   * @return    object  Newly created customer object
   * @throws    Chargify_Exception
   */
  public function create($customer)
  {    
    $required = array('first_name','last_name','email');
    $allowed = array('first_name','last_name','email','organization', 'reference');
    
    // ensure that only the allowed parameters are passed
    $customer_array = array();
    foreach($customer as $key => $val)
    {
      if(in_array($key, $allowed)) {
        $customer_array['customer'][$key] = $val;
      }
    }
    
    foreach($required as $key)
    {
      if(! isset($customer_array['customer'][$key]))
      {
        throw new Chargify_Exception('Required field missing');
      }
    } 
    
    $endpoint = 'customers';
    $customer_data = json_encode($customer_array);
    
    return $this->send_request($endpoint, $customer_data, 'POST');
  }
  
  /**
   * update
   *
   * @access    public
   * @param     int     $customer_id     Chargify Customer Id
   * @param     array   $update Updated customer information
   * @return    object    updated customer object
   * @throws    Chargify_Exception
   */
  public function update($customer_id, $update)
  {
    if(!is_numeric($customer_id))
    {
      throw new Chargify_Exception("customer id must be numeric");
    }
    
    $endpoint = 'customers/' . $customer_id;
    $response = $this->send_request($endpoint, json_encode($update), 'PUT');
    
    if(isset($response->errors))
    {
      throw new Chargify_Exception("Unable to update customer: " . $response->errors[0]);
    }
    
    return $response;
  }
  
  /**
   * delete
   *
   *  Delete is not currently supported by the Chargify API
   *  
   * @access    public
   * @param     int     $customer_id     Chargify Id
   * @return    boolean
   * @throws    Chargify_Exception
   */
  public function delete($customer_id)
  {
    // currently not supported but kept for the future
    throw new Chargify_Exception("Chargify_Customer::delete is currently not a supported API call.");
    
    if(!is_numeric($customer_id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $customer_id;
    $response = $this->send_request($endpoint, '' , 'DELETE');
    
    if($this->callInfo['http_code'] !== 200)
    {
      throw new Chargify_Exception("Unable to delete customer: " . $response->errors[0]);
    }
    
    return $response;
  }
}

?>