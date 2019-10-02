<?php

/**
 * extension to generic-easify-server
 * handles no response from easify server by checking 
 * the IP address with the discovery server
 * in case it has changed
 */

/**
 * Provides generic access to the specified Easify Server
 * 
 * Construct this class with the URL of your Easify Server, along with the 
 * username and password of your Easify ECommerce subscription.
 * 
 * You can then call the methods within the class to retrieve data from your 
 * Easify Server.
 * 
 * @class       Easify_Generic_Easify_Server
 * @version     4.10
 * @package     easify-woocommerce-connector
 * @author      Easify 
 */
class Easify_MF_Easify_Server extends Easify_Generic_Easify_Server {

    /**
     * DEPRECATED
     * 
     * Being replaced with GetFromEasifyServer() which uses JSON instead of XML
     * 
     * @param type $Url
     * @return string
     * @throws Exception
     */
    protected function GetFromWebService($Url) {
        // initialise PHP CURL for HTTP GET action
        $ch = curl_init();

        // setting up coms to an Easify Server 
        // HTTPS and BASIC Authentication
        // NB. required to allow self signed certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (version_compare(phpversion(), "7.0.7", ">=")) {
            // CURLOPT_SSL_VERIFYSTATUS is PHP 7.0.7 feature
            // TODO: Also need to ensure CURL is V7.41.0 or later!
            //curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, false);
        }

        // do not verify https certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // if https is set, user basic authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");

        // server URL 
        curl_setopt($ch, CURLOPT_URL, $Url);
        // return result or GET action
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // set timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, EASIFY_TIMEOUT);

        // send GET request to server, capture result
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] != '200') {
            Easify_Logging::Log('Could not communicate with Easify Server -  http response code: ' . $info['http_code']);

			if ($info['http_code'] == 0) { // there was no response from the server at all, try rediscovery
            	Easify_Logging::Log('Maybe it moved - attempting to relocate the server ');
				$disco = new Easify_Generic_Easify_Server_Discovery(EASIFY_DISCOVERY_SERVER_ENDPOINT_URI, $this->username, $this->password);
				if ($service_location = $disco->get_easify_server_endpoint()) {
		            Easify_Logging::Log('IP: ' . $service_location);
					if ($service_location <> get_option('easify_web_service_location')) {
						Easify_Logging::Log('Saving new IP: ' . $service_location);
                        update_option('easify_web_service_location', $service_location);
					}
				}
			}
			throw new \Exception('Could not communicate with Easify Server -  http response code: ' . $info['http_code']);
        }

        // record any errors
        if (curl_error($ch)) {
            $result = 'error:' . curl_error($ch);
            Easify_Logging::Log($result);
            throw new Exception($result);
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Gets a JSON response from the specified Easify Server...
     * 
     * If you want to send an order to an Easify Server, use the Easify Cloud
     * API Server (See Easify_WC_Send_Order_To_Easify()).
     * 
     * @param type $url
     * @return string
     * @throws Exception
     */
    protected function GetFromEasifyServer($url) {
        Easify_Logging::Log("Easify_Generic_Easify_Server.GetFromEasifyServer()");

        // initialise PHP CURL for HTTP GET action
        $ch = curl_init();

        // Specify JSON to an Easify Server and it will return JSON instead of 
        // XML.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        // setting up coms to an Easify Server 
        // HTTPS and BASIC Authentication
        // NB. required to allow self signed certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (version_compare(phpversion(), "7.0.7", ">=")) {
            // CURLOPT_SSL_VERIFYSTATUS is PHP 7.0.7 feature
            // TODO: Also need to ensure CURL is V7.41.0 or later!
            //curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, false);
        }

        // do not verify https certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // if https is set, user basic authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");

        // server URL 
        curl_setopt($ch, CURLOPT_URL, $url);
        // return result or GET action
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // set timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, EASIFY_TIMEOUT);

        // send GET request to server, capture result
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] != '200') {
            Easify_Logging::Log('Could not communicate with Easify Server -  http response code: ' . $info['http_code']);

			if ($info['http_code'] == 0) { // there was no response from the server at all, try rediscovery
            	Easify_Logging::Log('Maybe it moved - attempting to relocate the server ');
				$disco = new Easify_Generic_Easify_Server_Discovery(EASIFY_DISCOVERY_SERVER_ENDPOINT_URI, $this->username, $this->password);
				if ($service_location = $disco->get_easify_server_endpoint()) {
		            Easify_Logging::Log('IP: ' . $service_location);
					if ($service_location <> get_option('easify_web_service_location')) {
						Easify_Logging::Log('Saving new IP: ' . $service_location);
                        update_option('easify_web_service_location', $service_location);
					}
				}
			}
			throw new \Exception('Could not communicate with Easify Server -  http response code: ' . $info['http_code']);
        }

        // record any errors
        if (curl_error($ch)) {
            $result = 'error:' . curl_error($ch);
            Easify_Logging::Log($result);
            throw new Exception($result);
        }

        curl_close($ch);

        return $result;
    }

}
