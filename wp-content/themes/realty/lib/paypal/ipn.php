<?php
/**
 *  PHP-PayPal-IPN Example
 *
 *  This shows a basic example of how to use the IpnListener() PHP class to 
 *  implement a PayPal Instant Payment Notification (IPN) listener script.
 *
 *  For a more in depth tutorial, see my blog post:
 *  http://www.micahcarrick.com/paypal-ipn-with-php.html
 *
 *  This code is available at github:
 *  https://github.com/Quixotix/PHP-PayPal-IPN
 *
 *  @package    PHP-PayPal-IPN
 *  @author     Micah Carrick
 *  @copyright  (c) 2011 - Micah Carrick
 *  @license    http://opensource.org/licenses/gpl-3.0.html
 */
 
 
/*
Since this script is executed on the back end between the PayPal server and this
script, you will want to log errors to a file or email. Do not try to use echo
or print--it will not work! 

Here I am turning on PHP error logging to a file called "ipn_errors.log". Make
sure your web server has permissions to write to that file. In a production 
environment it is better to have that log file outside of the web root.
*/

// Add WP Functionality

$root = explode( 'wp-content', dirname(__FILE__) );
define('WP_USE_THEMES', false);
require_once( $root[0] . 'wp-load.php' );

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

//update_post_meta( 423, 'property_payment_status', '' );
/*
update_post_meta( 423, 'property_payment_status', '' );
$property = array(
	'ID'					=> 423,
	'post_status' => 'publish',
);
wp_update_post( $property );
*/

// instantiate the IpnListener class
include('ipnlistener.php');
$listener = new IpnListener();

// Theme Options Settings
global $realty_theme_option;
$paypal_settings_merchant_id = $realty_theme_option['paypal-merchant-id'];
$paypal_settings_sandbox = $realty_theme_option['paypal-sandbox'];
$paypal_settings_ssl = $realty_theme_option['paypal-ssl'];
$paypal_settings_amount = $realty_theme_option['paypal-amount'];
$paypal_settings_currency_code = $realty_theme_option['paypal-currency-code'];
$paypal_settings_ipn_email_address = $realty_theme_option['paypal-ipn-email-address'];
$paypal_settings_auto_publish = $realty_theme_option['paypal-auto-publish'];

$headers = array();
$headers[] = "From: PayPal IPN <$paypal_settings_ipn_email_address>";
//$headers[] = "Content-type: text/html\r\n";

/*
When you are testing your IPN script you should be using a PayPal "Sandbox"
account: https://developer.paypal.com
When you are ready to go live change use_sandbox to false.
*/

/*
By default the IpnListener object is going  going to post the data back to PayPal
using cURL over a secure SSL connection. This is the recommended way to post
the data back, however, some people may have connections problems using this
method. 

To post over standard HTTP connection, use:
*/

if( $paypal_settings_sandbox ) {
	$listener->use_sandbox = true;
}

if ( !$paypal_settings_ssl ) {
	$listener->use_ssl = false;
}

/*
To post using the fsockopen() function rather than cURL, use:
$listener->use_curl = false;
*/

/*
The processIpn() method will encode the POST variables sent by PayPal and then
POST them back to the PayPal server. An exception will be thrown if there is 
a fatal error (cannot connect, your server is not configured properly, etc.).
Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
file we setup at the top of this file.

The processIpn() method will send the raw data on 'php://input' to PayPal. You
can optionally pass the data to processIpn() yourself:
$verified = $listener->processIpn($my_post_data);
*/
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}


/*
The processIpn() method returned true if the IPN was "VERIFIED" and false if it
was "INVALID".
*/
if ( $verified ) {
    /*
    Once you have a verified IPN you need to do a few more checks on the POST
    fields--typically against data you stored in your database during when the
    end user made a purchase (such as in the "success" page on a web payments
    standard button). The fields PayPal recommends checking are:
    
      1. Check the $_POST['payment_status'] is "Completed"
	    2. Check that $_POST['txn_id'] has not been previously processed 
	    3. Check that $_POST['receiver_email'] is your Primary PayPal email 
	    4. Check that $_POST['payment_amount'] and $_POST['payment_currency'] 
	       are correct
    
    Since implementations on this varies, I will leave these checks out of this
    example and just send an email using the getTextReport() method to get all
    of the details about the IPN.  
    */
    
    // https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/
    
    if( $_POST['payment_status'] == "Completed" && $_POST['receiver_email'] == $paypal_settings_merchant_id ) {
    
    	$property_id = intval( $_POST['item_number'] );
    	
    	if ( isset( $_POST['payment_date'] ) && !empty( $_POST['payment_date'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_payment_date', $_POST['payment_date'] );
    	}
    	
    	if ( isset( $_POST['first_name'] ) && !empty( $_POST['first_name'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_first_name', $_POST['first_name'] );
    	}
    	
    	if ( isset( $_POST['last_name'] ) && !empty( $_POST['last_name'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_last_name', $_POST['last_name'] );
    	}
    	
    	if ( isset( $_POST['payer_email'] ) && !empty( $_POST['payer_email'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_payer_email', $_POST['payer_email'] );
    	}
    	
    	if ( isset( $_POST['mc_currency'] ) && !empty( $_POST['mc_currency'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_mc_currency', $_POST['mc_currency'] );
    	}
    	
    	if ( isset( $_POST['mc_gross'] ) && !empty( $_POST['mc_gross'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_mc_gross', $_POST['mc_gross'] );
    	}
    	
    	if ( isset( $_POST['txn_id'] ) && !empty( $_POST['txn_id'] ) ) {
	    	update_post_meta( $property_id, 'property_payment_txn_id', $_POST['txn_id'] );
    	}
    	
    	update_post_meta( $property_id, 'property_payment_status', $_POST['payment_status'] );
    	
    	if( $paypal_settings_auto_publish ) {
        $property = array(
        	'ID'					=> $property_id,
        	'post_status' => 'publish',
        );
        wp_update_post( $property );
      }
      
      error_log( "SUCCESS: ".$_POST['txn_id'] );
    
    }
    
    //mail('YOUR EMAIL ADDRESS', 'Verified IPN', $listener->getTextReport());
    
    wp_mail( $paypal_settings_ipn_email_address, 'Verified IPN', $listener->getTextReport(), $headers );

} else {
    /*
    An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
    a good idea to have a developer or sys admin manually investigate any 
    invalid IPN.
    */
    
    //mail('YOUR EMAIL ADDRESS', 'Invalid IPN', $listener->getTextReport());
    
    wp_mail( $paypal_settings_ipn_email_address, 'Invalid IPN', $listener->getTextReport(), $headers );
    
}
?>