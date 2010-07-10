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

/**
 * Chargify_Response_Common
 *  
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 * @abstract
 */
abstract class Chargify_Response_Common
{
    /**
     * Raw API Response
     *
     * @access    protected
     * @var       string       $response    Raw API Response
     */
    protected $response = '';
    
    /**
     * __construct()
     *
     * @access    public
     * @param     string      $response     Raw API Response
     * @return    void
     */
    public function __construct($response)
    {
      $this->response = $response;
    }
    
    /**
     * parse()
     *
     * @abstract
     * @access    public
     * @return    mixed     PEAR_Error on failure
     */
    abstract public function parse();
}