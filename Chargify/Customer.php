<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

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
   * listCustomers
   *
   * @access public
   * @throws Chargify_Exception
   */
  public function listCustomers()
  {
    $endpoint = 'customers';
    return $this->sendRequest($endpoint);
  }
  
  /**
   * getCustomerById
   *
   * @access    public
   * @param     integer   $id     Chargify Customer Id
   * @throws    Chargify_Exception
   */
  public function getCustomerById($id)
  {
    if(!is_numeric($id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $id;
    return $this->sendRequest($endpoint);
  }
  
  /**
   * getCustomerByReference
   *
   * @access    public
   * @param     string      $value    Customer reference
   * @throws    Chargify_Exception
   */
  public function getCustomerByReference($value)
  {
    $endpoint = 'customers/lookup';
    $data     = array('reference' => $value);
    return $this->sendRequest($endpoint, $data);
  }
  
  /**
   * createCustomer
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
   * @throws    Chargify_Exception
   */
  public function createCustomer($customer)
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
    
    return $this->sendRequest($endpoint, $customer_data, 'POST');
  }
  
  /**
   * updateCustomer
   *
   * @access    public
   * @param     int     $id     Chargify Id
   * @param     array   $update Updated customer information
   * @throws    Chargify_Exception
   */
  public function updateCustomer($id, $update)
  {
    if(!is_numeric($id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $id;
    $response = $this->sendRequest($endpoint, json_encode($update), 'PUT');
    
    if(isset($response->errors))
    {
      throw new Chargify_Exception("Unable to update customer: " . $response->errors[0]);
    }
    
    return $response;
  }
  
  /**
   * deleteCustomer
   *
   *  Delete is not currently supported by the Chargify API
   *  
   * @access    public
   * @param     int     $id     Chargify Id
   * @throws    Chargify_Exception
   */
  public function deleteCustomer($id)
  {
    if(!is_numeric($id))
    {
      throw new Chargify_Exception("ID must be numeric");
    }
    
    $endpoint = 'customers/' . $id;
    $response = $this->sendRequest($endpoint, '' , 'DELETE');
    
    if($this->callInfo['http_code'] == 403)
    {
      throw new Chargify_Exception("Unable to delete customer: " . $response->errors[0]);
    }
    
    return $response;
  }
}

?>