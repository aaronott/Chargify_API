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
}

?>