<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * API implementation for Chargify's web services
 *
 * PHP version 5.1.0+
 *
 * LICENSE: This source file is subject to the New BSD license that is 
 * available through the world-wide-web at the following URI:
 * http://www.opensource.org/licenses/bsd-license.php. If you did not receive  
 * a copy of the New BSD License and are unable to obtain it through the web, 
 * please send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package     Chargify
 * @author      Aaron Ott <aaron.ott@gmail.com> 
 * @copyright   2010 Aaron Ott
 */ 
require_once 'Chargify/Common.php';
require_once 'Chargify/Exception.php';

abstract class Chargify
{

    /***
     * URL for the chargify service
     */
     public static $uri = 'https://overnightlife.chargify.com';


    /**
     * API key
     */
     public static $apikey = 'D4iTya8Qp4zK7S-wzY0h';

    /**
     * API Password
     *
     * Currently chargify doesn't use a password
     * but I've decided to leave this here just in case
     */
     public static $password = 'x';


     /**
      * The factory
      */
      public function factory($endPoint)
      {
        $file = 'Chargify/' . $endPoint . '.php';
        if (!include_once($file)) {
            throw new Chargify_Exception('Endpoint file not found: ' . $file);
        }

        $class = 'Chargify_' . $endPoint;
        if (!class_exists($class)) {
            throw new Chargify_Exception('Endpoint class not found: ' . $class);
        }

        $instance = new $class();
        return $instance;
      }
}
