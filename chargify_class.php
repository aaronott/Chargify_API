#!/usr/bin/env php
<?php

$key = 'D4iTya8Qp4zK7S-wzY0h';

$base_url = 'https://overnightlife.chargify.com';
$action = 'customers.xml';

		// Create a new curl instance
		$curl = curl_init();

		// Set curl options
		curl_setopt_array($curl, array(
			CURLOPT_URL            => $base_url.'/'.$action,
			CURLOPT_USERPWD        => $key.":x",
		));

		if (($response = curl_exec($curl)) === FALSE)
		{
			// Get the error code and message
			$code  = curl_errno($curl);
			$error = curl_error($curl);

			// Close curl
			curl_close($curl);
      
      echo "There was an error: ($code) $error\n";
		}
    
    echo "RESPONSE: ".$response."\n";
		// Close curl
		curl_close($curl);

?>