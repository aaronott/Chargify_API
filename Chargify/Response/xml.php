<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Response class for Chargify API
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

require_once 'Chargify/Response/Common.php';

/**
 * XML response parser
 *
 * @author    Aaron Ott <aaron.ott@gmail.com>
 * @package   Chargify
 */
class Chargify_Response_xml extends Chargify_Response_Common
{
  /**
   * Parse the response
   *
   * @access    public
   * @return    object    Instance of SimpleXMLElement
   * @throws    Chargify_Response_Exception
   */
  public function parse()
  {
    if( ! function_exists('simplexml_load_string'))
    {
      throw new Chargify_Response_Exception('simplexml_load_string() not found');
    }
    
    $xml = @simplexml_load_string($this->response);
    if( ! $xml instanceof SimpleXMLElement)
    {
      throw new Chargify_Response_Exception('Could not parse XML');
    }
    
    if ((isset($xml['code']) && is_numeric($xml['code'])) && 
        (isset($xml['message']) && strlen($xml['message']))) {
      throw new Chargify_Response_Exception((string)$xml['message'], (int)$xml['code']);
    }
    
    return $xml;
  }
}
