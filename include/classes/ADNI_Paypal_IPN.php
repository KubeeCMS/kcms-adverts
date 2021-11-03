<?php
/* -------------------------------------------------------
 * Exit if accessed directly
 * ------------------------------------------------------- */
if ( !defined( 'ABSPATH' ) ) exit;


class ADNI_Paypal_IPN {	

	public function __construct() {
		
		$data = ADNI_Sell::sell_main_settings();
        $sell_options = $data['sell'];
		
		
		$this->debug = !empty($sell_options) ? $sell_options['payment']['paypal']['debug'] : 1;
		$this->sandbox = !empty($sell_options) ? $sell_options['payment']['paypal']['sandbox'] : 1;
		$this->log_file = ADNI_DIR.'log.txt';
	}
	
	
	
	/*
	 * Paypal IPN
	 *
	 * @access public
	 * @param int $ssl_check
	 * @return null
	*/
	public function wp_tuna_get_paypal_redirect( $test_mode = 0 ) 
	{
		// Check the current payment mode
		$paypal_url = $test_mode || $this->sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
	
		return $paypal_url;
	}
	
	
	
	
	
	public function log_add($message)
	{
		error_log(date('[Y-m-d H:i e] ', current_time( 'timestamp' )). $message . PHP_EOL, 3, $this->log_file);
	}
	
	
	
	
	
	public function check_ipn_request()
	{
		$ipn_response = !empty($_POST) ? $_POST : false;

		$return = $this->paypal_ipn_for_wordpress_check_adaptive_paments_is_validate($ipn_response);
		
		if (isset($return['validate']) && ($return['validate'] == 'required_to_check')) {

            // If $_POST is empty return without process
            if ($ipn_response == false) {
                return false;
            }

            if ($ipn_response && $this->check_ipn_request_is_valid($ipn_response)) {

                header('HTTP/1.1 200 OK');

                //do_action("paypal_ipn_for_wordpress_valid_ipn_request", $ipn_response);

                return true;
            } else {

                //do_action("paypal_ipn_for_wordpress_ipn_request_failed", "PayPal IPN Request Failure", array('response' => 200));

                return false;
            }
        }
		
		return $return;
	}
	
	
	
	public function check_ipn_request_is_valid($ipn_response) 
	{
        /**
         *  paypal_ipn_for_wordpress_ipn_forwarding_handler action allow developer to trigger own function
         */
        //do_action('paypal_ipn_for_wordpress_ipn_forwarding_handler', $ipn_response);

        /**
         * allow developer paypal_ipn_for_wordpress_ipn_response_handler to trigger own function
         */
        //do_action('paypal_ipn_for_wordpress_ipn_response_handler', $ipn_response);

        if ($this->debug) {
            $this->log_add('IPN paypal_ipn_for_wordpress_ipn_forwarding_handler: ' . print_r($ipn_response, true));
        }

        $paypal_url = $this->wp_tuna_get_paypal_redirect();

        if ($this->debug) {
            $this->log_add('Checking IPN response is valid via ' . $paypal_url . '...');
        }

        // Get received values from post data
        $validate_ipn = array('cmd' => '_notify-validate');
        $validate_ipn += stripslashes_deep($ipn_response);

        // Send back post vars to paypal
        $params = array(
            'body' => $validate_ipn,
            'sslverify' => false,
            'timeout' => 60,
            'httpversion' => '1.0.0',
            'compress' => false,
            'decompress' => false,
            'user-agent' => 'paypal-ipn/'
        );

        if ($this->debug) {
            $this->log_add('IPN Request Params: ' . print_r($params, true));
        }

        // Post back to get a response
        $response = wp_remote_post($paypal_url, $params);

        if ($this->debug) {
            $this->log_add('IPN Response: ' . print_r($response, true));
        }

        // check to see if the request was valid
        if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
            if ($this->debug) {
                $this->log_add('Received valid response from PayPal');
            }

            return true;
        }

        if ($this->debug) {
            $this->log_add('Received invalid response from PayPal');
            if (is_wp_error($response)) {
                $this->log_add('Error response: ' . $response->get_error_message());
            }
        }

        return false;
    }
	
	
	
	
	
	public function paypal_ipn_for_wordpress_check_adaptive_paments_is_validate($posted = null) 
	{
		$paypal_url = $this->wp_tuna_get_paypal_redirect();
		$return = array();
		$txn_type = (isset($posted['txn_type'])) ? $posted['txn_type'] : '';
		$reason_code = (isset($posted['reason_code'])) ? $posted['reason_code'] : '';
		$payment_status = (isset($posted['payment_status'])) ? $posted['payment_status'] : '';
		$account_key = (isset($posted['account_key'])) ? $posted['account_key'] : '';
		$transaction_type = (isset($posted['transaction_type'])) ? $posted['transaction_type'] : '';
	
		if (strtoupper($transaction_type) == 'ADAPTIVE PAYMENT PREAPPROVAL' || strtoupper($transaction_type) == 'ADAPTIVE PAYMENT PAY' || !empty($account_key)) {
	
			if ($posted == false) {
				return false;
			}
			/**
			 *  paypal_ipn_for_wordpress_ipn_forwarding_handler action allow developer to trigger own function
			 */
			//do_action('paypal_ipn_for_wordpress_ipn_forwarding_handler', $posted);
	
			/**
			 * allow developer paypal_ipn_for_wordpress_ipn_response_handler to trigger own function
			 */
			//do_action('paypal_ipn_for_wordpress_ipn_response_handler', $posted);
	
			$raw_post_data = file_get_contents('php://input');
			$raw_post_array = explode('&', $raw_post_data);
	
			$myPost = array();
	
			foreach ($raw_post_array as $keyval) {
				$keyval = explode('=', $keyval);
				if (count($keyval) == 2)
					$myPost[$keyval[0]] = urldecode($keyval[1]);
			}
	
			// read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';
			if (function_exists('get_magic_quotes_gpc')) {
				$get_magic_quotes_exists = true;
			}
	
			foreach ($myPost as $key => $value) {
				if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
					$value = urlencode(stripslashes($value));
				} else {
					$value = urlencode($value);
				}
				$req .= "&$key=$value";
			}
	
	
			$ch = curl_init($paypal_url);
			if ($ch == FALSE) {
				return FALSE;
			}
	
			$is_enable_curl = function_exists('curl_init') ? true : false;
	
			if ($is_enable_curl == false) {
				if ($this->debug) {
					$this->log_add("cURL is not enabled");
				}
			}
	
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	
			if ($this->debug) {
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
			}
	
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
			$res = curl_exec($ch);
	
			if (curl_errno($ch) != 0) { // cURL error
				if ($this->debug) {
					$this->log_add("Can't connect to PayPal to validate IPN message: " . print_r(curl_error($ch), true));
				}
				curl_close($ch);
				exit;
			} else {
				
				if ($this->debug) {
					$this->log_add('HTTP response of validation request: ' . print_r($req, true));
				}
				curl_close($ch);
			}
			$tokens = explode("\r\n\r\n", trim($res));
			$res = trim(end($tokens));
			if (strcmp($res, "VERIFIED") == 0) {
				
				if ($this->debug) {
					$this->log_add('paypal', 'Verified IPN: ' . print_r($res, true));
				}
				return true;
			} else if (strcmp($res, "INVALID") == 0) {
	
				if ($this->debug) {
					$this->log_add('paypal', 'Invalid IPN: ' . print_r($res, true));
				}
				return false;
			}
		} else {
			$return['validate'] = 'required_to_check';
			return $return;
		}
	}
	
	
	
	
	
	
	
	public function successful_request($IPN_status) 
	{
        $ipn_response = !empty($_POST) ? $_POST : false;

        if ($this->debug) {
            $this->log_add('Payment IPN Array: ' . print_r($ipn_response, true));
        }
        // If $_POST is empty return without process
        if ($ipn_response == false) {
            return false;
        }

        $ipn_response['IPN_status'] = ( $IPN_status == true ) ? 'Verified' : 'Invalid';

        if ($this->debug) {
            $this->log_add('Payment IPN_status: ' . $IPN_status);
        }
		
		$posted = stripslashes_deep($ipn_response);
		
		$custom           = isset($posted['custom']) ? $posted['custom'] : '';
		$txn_id           = isset($posted['txn_id']) ? $posted['txn_id'] : '';
		$payment_status   = isset($posted['payment_status']) ? $posted['payment_status'] : '';
		$payment_currency = isset($posted['mc_currency']) ? $posted['mc_currency'] : '';
		$payment_amount   = isset($posted['mc_gross']) ? $posted['mc_gross'] : '';
		$receiver_email   = isset($posted['receiver_email']) ? $posted['receiver_email'] : '';
		$payer_email      = isset($posted['payer_email']) ? $posted['payer_email'] : '';
		
		if ($this->debug) {
            $this->log_add('Payment Status: ' . $payment_status);
        }
	
		if( $payment_status == 'Completed' ) 
		{	
			$res = array(
				'custom'           => $custom,
				'txn_id'           => $txn_id,
				'payment_status'   => $payment_status,
				'payment_currency' => $payment_currency,
				'payment_amount'   => $payment_amount,
				'receiver_email'   => $receiver_email,
				'payer_email'      => $payer_email
			);
			
			return $res;
		}
		
	}
	
	
}