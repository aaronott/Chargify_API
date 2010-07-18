<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Product endpoint class
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
 * Chargify_Product
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 */
class Chargify_Product extends Chargify_Common
{
  /**
   * all
   *
   * @access public
   * @throws Chargify_Exception
   */
  public function all()
  {
    $endpoint = 'products';
    return $this->send_request($endpoint);
  }
  
  /**
   * by_id
   *
   * @access  public
   * @param   int   $id   Chargify Product Id
   * @throws  Chargify_Exception
   */
  public function by_id($id)
  {
    if( ! is_numeric($id))
    {
      throw new Chargify_Exception("Id Must by numeric");
    }
    
    $endpoint = 'products/' . $id;
    $result = $this->send_request($endpoint);
    
    if($this->callInfo['http_code'] != 200)
    {
      throw new Chargify_Exception("Unable to get product.");
    }
    
    return $result;
  }
  
  /**
   * by_handle
   *
   * @access  public
   * @param   int   $handle   Chargify Product Handle
   * @throws  Chargify_Exception
   */
  public function by_handle($handle)
  {
    $endpoint = 'products/handle/' . $handle;
    $result = $this->send_request($endpoint);
    
    if($this->callInfo['http_code'] != 200)
    {
      throw new Chargify_Exception("Unable to get product.");
    }
    
    return $result;
  }
}