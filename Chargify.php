<?php

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
