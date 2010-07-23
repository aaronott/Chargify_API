<?php
/**
 * Response framework for Chargify API
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

require_once 'Chargify/Response/Exception.php';

/**
 * Chargify
 *  
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com>
 * @abstract
 */
abstract class Chargify_Response
{
    /**
     * Create a response instance
     *
     * @access      public
     * @param       string      $type       Type of response to create
     * @param       string      $response   Raw response from API
     * @return      object      $instance   Instance of the Response object
     * @throws      Chargify_Response_Exception
     */
    static public function factory($type, $response) {
        $file = 'Chargify/Response/' . $type . '.php';
        if (!include_once($file)) {
            throw new Chargify_Response_Exception('Unable to load response file: ' . $type);
        }

        $class = 'Chargify_Response_' . $type;
        if (!class_exists($class)) {
            throw new Chargify_Response_Exception('Unable to find response class: ' . $class);
        }

        $instance = new $class($response);
        return $instance;
    }
}

?>
