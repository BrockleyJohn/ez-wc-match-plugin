<?php
/**
 * variant of class-easify-wc-shop.php for More Forward
   author John Ferguson @BrockleyJohn john@sewebsites.net
   extend wc class:
   override UpdateProduct method

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
include_once(dirname(__FILE__) . '/easify_functions.php');
include_once(dirname(__FILE__) . '/class-easify-generic-shop.php');
include_once(dirname(__FILE__) . '/class-easify-wc-shop.php');
include_once(dirname(__FILE__) . '/class-easify-mf-easify-server.php');

/**
 * Provides a means for the Easify Web Service to manipulate a WooCommerce
 * shopping system.
 * 
 * Implements abstract methods from the Easify_Generic_Shop superclass as 
 * required for use by the Easify_Generic_Web_Service class.
 * 
 * @class       Easify_Generic_Shop
 * @version     4.12
 * @package     easify-woocommerce-connector
 * @author      Easify 
 */
class Easify_MF_Shop extends Easify_WC_Shop {

    public function __construct($easify_server_url, $username, $password) {
        // Create an Easify Server class so that the subclasses can communicate with the 
        // Easify Server to retrieve product details etc....
        $this->easify_server = new Easify_MF_Easify_Server($easify_server_url, $username, $password);
    }
    
    /**
     * Public implementation of abstract methods in superclass
     */
/*    public function IsExistingProduct($SKU) {
        try {
            // get number of WooCommerce products that match the Easify SKU
            global $wpdb;
            $ProductId = $wpdb->get_var($wpdb->prepare(
                            "SELECT post_id FROM " . $wpdb->postmeta . " WHERE meta_key = '_sku' AND meta_value = '%s' LIMIT 1", $SKU
            ));
            return is_numeric($ProductId) ? true : false;
        } catch (Exception $e) {
            Easify_Logging::Log("IsExistingProduct Exception: " . $e->getMessage() . "\n");
        }
    }
*/

    public function UpdateProduct($EasifySku) {
        try {
            /* Autocomplete hints... */  
            /* @var $Product ProductDetails */                   
            Easify_Logging::Log('Easify_MF_Shop.UpdateProduct()');
            // get product
            if (empty($this->easify_server)) {
                Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - Easify Server is NULL");
            }
 
            $Product = $this->easify_server->GetProductFromEasify($EasifySku);
            
            if ($Product->Published == FALSE) {
                Easify_Logging::Log('Easify_MF_Shop.UpdateProduct() - Not published, deleting product and not updating.');
//                $this->DeleteProduct($EasifySku);
                return;
            }

             if ($Product->Discontinued == 'true') {
                Easify_Logging::Log('Easify_MF_Shop.UpdateProduct() - Discontinued, deleting product and not updating.');
//                $this->DeleteProduct($EasifySku);
                return;
            }           
            
            // calculate price from trade margin and cost price
            $Price = round(($Product->CostPrice / (100 - $Product->TradeMargin) * 100), 4);
            Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - price $Price");

            // catch reserved delivery SKUs and update delivery prices
/*            if ($this->UpdateDeliveryPrice($Product->SKU, $Price))
            {
                Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - Product was delivery SKU, updated price and nothing more to do.");
                 return;               
            }
*/

            // get WooCommerce product id from Easify SKU
            $ProductId = $this->GetWooCommerceProductIdFromEasifySKU($Product->SKU);
            Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - woocommerce product id $ProductId");

/*            // create a WooCommerce stub for the new product
            $ProductStub = array(
                'ID' => $ProductId,
                'post_title' => $Product->Description,
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'product'
            );
            
            // insert product record and get WooCommerce product id
            $ProductId = wp_update_post($ProductStub);

            // link subcategory to product
            wp_set_post_terms($ProductId, array($SubCategoryId), "product_cat");

            // get WooCommerce tax class from Easify tax id
            $TaxClass = $this->GetWooCommerceTaxIdByEasifyTaxId($Product->TaxId);

            // Easify_Logging::Log("UpdateProduct.TaxClass: " . $TaxClass);

            /*
              flesh out product record meta data
             */

            // pricing
//            update_post_meta($ProductId, '_sku', $Product->SKU); 
            update_post_meta($ProductId, '_price', $Price);
            update_post_meta($ProductId, '_regular_price', $Price);
            update_post_meta($ProductId, '_sale_price', $Price);
            update_post_meta($ProductId, '_sale_price_dates_from	', '');
            update_post_meta($ProductId, '_sale_price_dates_to', '');
//            update_post_meta($ProductId, '_tax_status', 'taxable');
//            update_post_meta($ProductId, '_tax_class', strtolower($TaxClass));

            // handling stock - we get free stock minus allocated stock
            $stockLevel = $Product->StockLevel - $this->easify_server->get_allocation_count_by_easify_sku($Product->SKU);
            Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - stock $stockLevel");
            
            // WooCommerce has a separate status value for in stock / out of stock, set it 
            // according to stock level...
            if ($stockLevel > 0)
            {
                $this->DeleteOutofStockTermRelationship($ProductId);                                  
                update_post_meta($ProductId, '_stock_status', 'instock');                
            }
            else
            {
                update_post_meta($ProductId, '_stock_status', 'outofstock');                   
            }
/*                        
            update_post_meta($ProductId, '_manage_stock', 'yes');
            update_post_meta($ProductId, '_downloadable', 'no');
            update_post_meta($ProductId, '_virtual', 'no');
            update_post_meta($ProductId, '_visibility', 'visible');
            update_post_meta($ProductId, '_sold_individually', '');
            update_post_meta($ProductId, '_manage_stock', 'yes');
            update_post_meta($ProductId, '_backorders', 'no');
*/            
            // This needs to be free stock level not on hand stock level (Stock level minus amount of stock allocated to other orders)...
            Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - Updating stock level.");                     
            update_post_meta($ProductId, '_stock', $stockLevel);
/*  
            // physical properties
            update_post_meta($ProductId, '_weight', $Product->Weight);
            update_post_meta($ProductId, '_length', '');
            update_post_meta($ProductId, '_width', '');
            update_post_meta($ProductId, '_height', '');

            // misc
            update_post_meta($ProductId, '_purchase_note', '');
            update_post_meta($ProductId, '_featured', 'no');
            update_post_meta($ProductId, '_product_attributes', 'a:0:{}'); // no attributes
            // get web info if available
            if ($Product->WebInfoPresent == 'true') {
                $this->UpdateProductInformation($EasifySku);
            }

            // Update tags if present...
            if (!empty($Product->Tags))
            {
                Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - Adding Tags: " . $Product->Tags);                
                wp_set_object_terms($ProductId, explode(',', $Product->Tags), 'product_tag');
            }
*/            
            
            Easify_Logging::Log("Easify_MF_Shop.UpdateProduct() - End.");
        } catch (Exception $e) {
            Easify_Logging::Log("Easify_MF_Shop->UpdateProductInDatabase Exception: " . $e->getMessage());
            throw $e;
        }
    }    
        
}

?>