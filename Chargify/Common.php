<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Common class for Chargify API endpoints
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
require_once 'Chargify/Response.php';

abstract class Chargify_Common
{
    /**
     * Last API URI called
     *
     * @access      public
     * @var         string      $lastCall
     */
    public $lastCall = '';

    /**
     * Raw response of last API call
     *
     * @access      public
     * @var         string      $lastResponse
     * @see         Chargify_Common::$lastCall
     */
    public $lastResponse = '';

		/**
		 * Information about the last call made
		 *
		 * @access 			public
		 * @var					string			$callInfo
		 */
		 public $callInfo = '';
		 
    /**
     * Send a request to the API
     *
     * @access      public  
     * @param       string      $endPoint       Endpoint of API call
     * @param       array       $params         GET arguments of API call
     * @return      mixed
     */
    protected function sendRequest($endPoint, $data='', $method='GET', $format='json')
    {
        $uri = Chargify::$uri .'/'. $endPoint .'.'.$format;

				if(strtoupper($method) == 'GET' && !empty($data))
				{
						$params = array();
						foreach ($data as $key => $val) {
								$params[] = $key . '=' . urlencode($val);
						}
						$uri .= '?' . implode('&', $params);
				}
        $this->lastCall = $uri;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_USERPWD, Chargify::$apikey . ':' . Chargify::$password);
        
        if ($format == 'json') {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	            'Accept: application/json'
	        ));
        } else {
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/xml',
	            'Accept: application/xml'
	        ));         	
        }
        
        switch(strtoupper($method))
        {
          case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
          case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
          case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        }
        
        $this->lastResponse = curl_exec($ch);
				$this->callInfo = curl_getinfo($ch);
				
        if ($this->lastResponse === false) {
            throw new Chargify_Exception('Curl error: ' . curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);
				
				
        $response = Chargify_Response::factory(
            $format, 
            $this->lastResponse
        );

        try {
            return $response->parse();
        } catch (Chargify_Response_Exception $e) {
            throw new Chargify_Exception($e->getMessage(), $e->getCode(), $this->lastCall, $this->lastResponse); 
        }
				
    }
}