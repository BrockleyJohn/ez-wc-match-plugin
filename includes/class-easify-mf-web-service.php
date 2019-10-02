<?php
/**
 * variant of class-easify-wc-web-service.php for More Forward
   author John Ferguson @BrockleyJohn john@sewebsites.net
   used instead of wc class

/**
 * Copyright (C) 2017  Easify Ltd (email:support@easify.co.uk)
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

require_once( 'class-easify-generic-web-service.php' );

/**
 * Implementation of the abstract Easify_Generic_Web_Service class to provide
 * the shop functionality for a WooCommerce system.
 * 
 * The shop class provides functionality to manipulate products in the online shop,
 * i.e. Add/Update/Delete products.
 * 
 * Because each online shop requires different code, you can subclass the 
 * Easify_Generic_Web_Service class as done here in order to provide a shop 
 * class that is compatible with your online shop. 
 * 
 * @class       Easify_WC_Web_Service
 * @version     4.0
 * @package     easify-woocommerce-connector
 * @author      Easify 
 */
class Easify_MF_Web_Service extends Easify_Generic_Web_Service {

    /**
     * Factory method to create a WooCommerce shop class...
     * 
     * Returns a WooCommerce shop class to the superclass.
     */
    public function create_shop() {
        // Create WooCommerce shop class...
        return new Easify_MF_Shop($this->easify_server_url, $this->username, $this->password);
    }

    private function authorise() {
        // Check basic authentication if credentials were supplied... [override generic to ignore capitalisation in user name]
        if (!empty($this->username) && !empty($this->password)) {
            /* Check basic auth credentials... 
             * This is the Easify Subscription username and password. It is entered in the 
             * Easify ECommerce Channel manager and is passed using basic http authentication
             * with the incoming notification. */
     
            // Make sure we have credentials from header
            if (!isset($this->basic_auth->username) || !isset($this->basic_auth->password)) {
                // PHP Auth not found - return 403 error
                Easify_Logging::Log("Incoming notification from Easify Server - could not get HTTP Basic Authentication values from http header. " .
                        "Make sure that PHP Basic Authentication is enabled for your website, and that your website is not set to run in PHP Safe Mode." .                        
                        "Also ensure that the appropriate re-write rules are present in .htaccess.");
                header('HTTP/1.0 403 Forbidden');
                echo 'Could not get authentication values.';
                return false;
            }             
            
            // Authenticate user...                  
            if (strtolower($this->basic_auth->username) != strtolower($this->username) || $this->basic_auth->password != $this->password) {
              // Username or password doesn't match - return 403 error                    
                Easify_Logging::Log("Incoming notification from Easify Server - invalid username or password. " .
                        "Check that the username and password for the Easify Subscription has been correctly " .
                        "entered in both the Easify ECommerce Channel Manager in Easify pro, and also in the " .
                        "settings page of the Easify plugin.");
                header('HTTP/1.0 403 Forbidden');
                echo 'Invalid username or password.';
                return false;
            }                        
        }

        // Check the PK if it was provided (not used in Easify WooCommerce plugin)...
        if (!empty($this->pk)) {
            /* Check $pk matches what we expect... 
             * This is the Easify Private Key value. It is entered in the 
             * Easify ECommerce Channel manager and is passed with the incoming 
             * notification. */
            if ($this->pk != $this->easify_pk) {
                // Username or password doesn't match - return 403 error
                Easify_Logging::Log("Incoming notification from Easify Server - invalid private key value. " .
                        "Check that the private for has been correctly " .
                        "entered in the Easify ECommerce Channel Manager in Easify pro.");
                header('HTTP/1.0 403 Forbidden');
                echo 'Invalid private key.';
                return false;
            }
        }

        return true;
    }

    /**
     * ignore anything except updating product stock and price
     */
    protected function process_request() {
        Easify_Logging::Log("Easify_MF_Web_Service->process_request() - Entity:" . $this->easify_entity_name . 
                " Action:" . $this->easify_action . 
                " Key:" . $this->easify_key_value);
        
        switch ($this->easify_entity_name) {
		 
		 	case 'ProductPublished' :
		 	case 'ProductStockLevelChanged' :
		 	case 'Products' :
            	switch ($this->easify_action) {
					case "Modified":
					case "Added":
						if ($this->shop->IsExistingProduct($this->easify_key_value)) {
							// update existing product 
							Easify_Logging::Log("Easify_MF_Web_Service.UpdateProduct(" . $this->easify_key_value . ")");
							$this->shop->UpdateProduct($this->easify_key_value);
						} else {
			                Easify_Logging::Log("Easify_MF_Web_Service.process_request() - not found - sku ignored");
						}
						break;
					default :
		                Easify_Logging::Log("Easify_MF_Web_Service.process_request() - Action ignored");
				}
				break;
			default :
                Easify_Logging::Log("Easify_MF_Web_Service.process_request() - Entity ignored");
		}
		 
    }

}
