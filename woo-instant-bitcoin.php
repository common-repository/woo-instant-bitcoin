<?php
/**
 * Plugin Name: Cryptoo.me Bitcoin payment gateway
 * Description: Cryptoo.me Bitcoin  payment gateway for WooCommerce
 * Version: 1.1.1
 * Author: Alexey Trofimov
 * Author URI: http://cryptoo.me
 * License: GPL3
 *
 * Text Domain: wc_cryptoome_btc_payment_gateway
 * Domain Path: /languages
 * WC requires at least: 1.6
 * WC tested up to: 4.3 
 *
 */

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
This plugin is based on (and uses code samples of) :
	WC Sermepa GitHub Repository 
	http://github.com/jesusangel/wc-sermepa/
	Distributed under the terms of the GNU General Public License
	Special thanks to Jesús Ángel del Pozo Domínguez
*/
 
	add_action('plugins_loaded', 'init_wc_cryptoome_btc_payment_gateway', 0);


	function init_wc_cryptoome_btc_payment_gateway() {
	 
	    if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }
	    
		/**
		 * Cryptoo.me Bitcoin  Instant Payment Gateway
		 *
		 * Provides a Cryptoo.me Bitcoin  Instant Payment Gateway.
		 *
		 * @class 		WC_CryptooMe_BTC
		 * @extends		WC_Payment_Gateway
		 * @version		1.0.1
		 */
		   
		class WC_CryptooMe_BTC extends WC_Payment_Gateway {
			
		    /**
		     * Constructor for the gateway.
		     *
		     * @access public
		     * @return void
		     */
			public function __construct() {
				global $woocommerce;


				$this->id			= 'mycryptoomebtc';

				$this->icon 		= apply_filters( 'wc_cryptoome_btc_icon',  plugins_url('/assets/images/icons/cryptoome.png', __FILE__ ));
				$this->has_fields 	= false;
				$this->method_title     = __( 'Instant Bitcoin satoshi (Cryptoo.me)', 'wc_cryptoome_btc_payment_gateway' );
				$this->method_description = __( 'Pay in Bitcoin satoshi via Cryptoo.me', 'wc_cryptoome_btc_payment_gateway' );
	
		        // Set up localisation
	            $this->load_plugin_textdomain();
	                
				// Load the form fields.
				$this->init_form_fields();
		
				// Load the settings.
				$this->init_settings();
		
				// Define user set variables
				$this->title			= $this->settings['title'];
				$this->description		= $this->settings['description'];
				$this->api_key			= $this->settings['api_key'];
				$this->set_completed	= $this->settings['set_completed'];
				$this->debug			= $this->settings['debug'];

				$this->notify_url = add_query_arg( 'wc-api', 'WC_CryptooMe_BTC', home_url( '/' ) );
				
		
				// Logs
				if ( 'yes' == $this->debug ) {
					if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
						$this->log = $woocommerce->logger();
					} else {
						$this->log =  new WC_Logger();
					}
				}
		
				// Actions
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
					// Check for gateway messages using WC 1.X format
					add_action( 'init', array( $this, 'check_notification' ) );
					add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
				} else {
					// Payment listener/API hook (WC 2.X) 
					add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_notification' ) );
					add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				}
				add_action( 'woocommerce_receipt_mycryptoomebtc', array( $this, 'receipt_page' ) );
				
				
				if ( !$this->is_valid_for_use() ) 
					$this->enabled = false;

		    }//__construct()

	
		    
			/**
	         * Localisation.
	         *
	         * @access public
	         * @return void
	         */
	        function load_plugin_textdomain() {
                // Note: the first-loaded translation file overrides any following ones if the same translation is present
                $locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce' );
                $variable_lang = ( get_option( 'woocommerce_informal_localisation_type' ) == 'yes' ) ? 'informal' : 'formal';
                load_textdomain( 'wc_cryptoome_btc_payment_gateway', WP_LANG_DIR.'/wc_cryptoome_btc_payment_gateway/wc_cryptoome_btc_payment_gateway-'.$locale.'.mo' );
                load_plugin_textdomain( 'wc_cryptoome_btc_payment_gateway', false, dirname( plugin_basename( __FILE__ ) ).'/languages/'.$variable_lang );
                load_plugin_textdomain( 'wc_cryptoome_btc_payment_gateway', false, dirname( plugin_basename( __FILE__ ) ).'/languages' );
	        }//load_plugin_textdomain()
		
		
		    /**
		     * Check if this gateway is enabled and available in the user's country
		     *
		     * @access public
		     * @return bool
		     */
		    function is_valid_for_use() {
				$all_supported = get_woocommerce_currencies(); //array, "BTC" => Bitcoin
				if (!array_key_exists ( 'BTC' , $all_supported)) 
					return false;
					
		        return true;
		    }//is_valid_for_use()
		
			/**
			 * Admin Panel Options
			 * - Options for bits like 'title' and availability on a country-by-country basis
			 *
			 * @since 1.0.0
			 */
			public function admin_options() {
				global $woocommerce;
				$img = '<img style="vertical-align: middle;width:24px;height:24px;border:0;" src="'. plugin_dir_url( __FILE__ ) . 'assets/images/icons/icon-256x256.png'.'"></img>';
		    	?>
<h3><?php _e($img.'Cryptoo.me Bitcoin ', 'wc_cryptoome_btc_payment_gateway'); ?></h3>
<p><?php _e('Cryptoo.me Bitcoin  operates via  <a target=_blank href="https://cryptoo.me">Cryptoo.me</a>', 'wc_cryptoome_btc_payment_gateway'); ?>.</p>

<?php if ( $this->is_valid_for_use() ) : ?>
<table class="form-table">
					<?php 
						if ( strlen($this->api_key) < 40 )	
						{
							echo('<div class="inline error"><p>');
							_e( 'Please obtain free API key at <a target=_blank href="https://cryptoo.me">Cryptoo.me</a>.', 'wc_cryptoome_btc_payment_gateway' );
							echo('<br>');
							_e( 'Register new Application at <a target=_blank href="https://cryptoo.me/applications/">Application Manager</a>, and use the Application API key in the form bellow.', 'wc_cryptoome_btc_payment_gateway' );
							echo('<br>');
							$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
							$site_name = get_bloginfo();
							_e( 'Set Application Name to ', 'wc_cryptoome_btc_payment_gateway' );
							echo("<b> $site_name </b> ");
							_e( 'and Application URL to ', 'wc_cryptoome_btc_payment_gateway' );
							echo("<b> $shop_page_url </b>  ");	
							_e( 'to receive extra traffic from the <target=_blank href="https://cryptoo.me/rotator/">Applications List</a>.', 'wc_cryptoome_btc_payment_gateway' ) . '<u>' . $shop_page_url . '</u> ';
							echo('</p></div>');//inline error
						}					
					
		    			// Generate the HTML For the settings form.
		    			$this->generate_settings_html();
		    		?>
		    		</table>
<!--/.form-table-->
<?php else : ?>
<div class="inline error">
	<p>
		<strong><?php _e( 'Gateway Disabled', 'wc_cryptoome_btc_payment_gateway' ); ?></strong>: 
		<?php _e( 'Cryptoo.me Bitcoin  does not support your store currency.', 'wc_cryptoome_btc_payment_gateway' ); ?></p>
		<?php print_r(get_woocommerce_currency());?>
</div>
<?php
		        	endif;
				
		    }//admin_options()
		


		
		    /**
		     * Initialise Gateway Settings Form Fields
		     *
		     * @access public
		     * @return void
		     */
		    function init_form_fields() {
		
		    	$this->form_fields = array(
		    		'enabled' => array(
						'title' => __( 'Enable/Disable', 'wc_cryptoome_btc_payment_gateway' ),
						'type' => 'checkbox',
						'description' => __( 'Enable/Disable payment method.', 'wc_cryptoome_btc_payment_gateway' ),
		    			'desc_tip'    => true,
						'label' => __( 'Enable Cryptoo.me Bitcoin ', 'wc_cryptoome_btc_payment_gateway' ),
						'default' => 'yes'
					),

					'title' => array(
						'title' => __( 'Title', 'wc_cryptoome_btc_payment_gateway' ),
						'type' => 'text',
						'description' => __( 'This controls the title which the user sees during checkout.', 'wc_cryptoome_btc_payment_gateway' ),
						'desc_tip'    => true,
						'default' => __( 'Instant Bitcoin Payment', 'wc_cryptoome_btc_payment_gateway' )
					),
	    			'description' => array(
    					'title' => __( 'Description', 'wc_cryptoome_btc_payment_gateway' ),
    					'type' => 'textarea',
    					'description' => __( 'This controls the description which the user sees during checkout.', 'wc_cryptoome_btc_payment_gateway' ),
	    				'desc_tip'    => true,
    					'default' => __( 'Bitcoin Payment gateway with Cryptoo.me', 'wc_cryptoome_btc_payment_gateway' )
	    			),

					'api_key' => array(
						'title' => __( 'Application API Key', 'wc_cryptoome_btc_payment_gateway' ),
						'type' => 'text',
						'description' => __( 'Please enter your API key. Get it for free at <a target=_blank href=https://cryptoo.me/applications/>Cryptoo.me Application Manager</a>.', 'wc_cryptoome_btc_payment_gateway' ),
						'default' => ''
					),

	    			'set_completed' => array(
	    					'title'       => __( 'Set order as completed after payment?', 'wc_cryptoome_btc_payment_gateway' ),
	    					'type'        => 'select',
	    					'description' => __( 'After payment, should the order be set as "completed" ? Default is "processing".', 'wc_cryptoome_btc_payment_gateway' ),
	    					'desc_tip'    => false,
	    					'options'     => array(
	    							'N' => __( 'No', 'wc_cryptoome_btc_payment_gateway' ),
	    							'Y' => __( 'Yes', 'wc_cryptoome_btc_payment_gateway' ),
	    					),
	    					'default'     => 'Y'
	    			),

					'debug' => array(
						'title' => __( 'Debug Log', 'wc_cryptoome_btc_payment_gateway' ),
						'type' => 'checkbox',
						'label' => __( 'Enable logging', 'wc_cryptoome_btc_payment_gateway' ),
						'description' => sprintf( __( 'Log Cryptoo.me Bitcoin  events, inside %s', 'wc_cryptoome_btc_payment_gateway' ), wc_get_log_file_path( 'cryptoome' ) ),
						'default' => 'no'
					)
				);
		
		    }//init_form_fields()
		
		
		
		    /**
			 * Generate the cryptoome button link
		     *
		     * @access public
		     * @param mixed $order_id
		     * @return string
		     */
		    function generate_cryptoome_btc_form( $order_id ) {
				global $woocommerce;
		
				$order = new WC_Order( $order_id );
				$o_currency = $order->get_currency();
				if($o_currency != 'BTC')
				{
					return( __( 'CRYPTOO.ME Bitcoin Payment Gateway does not operate with the currency of this order', 'wc_cryptoome_btc_payment_gateway' ) . ' (' . $o_currency .')');
				}
				
				
				$cryptoome_url = 'https://cryptoo.me/api/v1/invoice/create/';
				$api_key = $this->api_key; //ATT!!! WE DO NOT EXPOSE THAT ONE ON FRONTEND EVER! 
				$amount = $order->get_total() * 100000000; //gotta be in satoshi
				$notice = __( 'Payment for order', 'wc_cryptoome_btc_payment_gateway') . ' ' . $order_id;
				$callback_url = $this->notify_url;
				
				$fields = array(
					'key'=>$api_key,
					'amount'=>$amount,
					'notice'=>$notice,
					'data'=>$order_id,
					'redirect_url'=>$callback_url,
				);

				if ( 'yes' == $this->debug ) {
					$this->log->add( 'cryptoome', 'Invoice out data for Cryptoo.me  ' . print_r( $fields, true ));
				}			

				$out = wp_remote_post( 'https://cryptoo.me/api/v1/invoice/create/', array(
					'method' => 'POST', 
					'body' => $fields)  );
				$out_ison = json_decode($out['body']);


				if ( 'yes' == $this->debug ) {
					$this->log->add( 'cryptoome', 'Invoice returned data for Cryptoo.me   ' . print_r( $out_ison, true ));
				}	 
				
				if($out_ison->status != 200)
				{

					return( __( 'CRYPTOO.ME returned error', 'wc_cryptoome_btc_payment_gateway' ) . ' ' . $out_ison->status . " , " . $out_ison->message );
				}

				$_SESSION["mycryptoomebtc_invid_" . $order_id] = $out_ison->invid; //session stores it on the server side - safe enough
				$jump_url = 'https://cryptoo.me/api/v1/invoice/open/'.$out_ison->invid;
				wp_redirect($jump_url);
				exit;

			}//generate_cryptoome_btc_form
		
		    /**
		     * Process the payment and return the result
		     *
		     * @access public
		     * @param int $order_id
		     * @return array
		     */
			function process_payment( $order_id ) {
	
				$order = new WC_Order( $order_id );

				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					$redirect_url = add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))));
				} else {
					$redirect_url = $order->get_checkout_payment_url( true );
				}
		
				return array(
					'result' 	=> 'success',
					'redirect'	=> $redirect_url
				);
			}//process_payment()
		
		    /**
		     * Output for the order received page.
		     *
		     * @access public
		     * @return void
		     */
			function receipt_page( $order ) {
		
				echo $this->generate_cryptoome_btc_form( $order );
		
			}//receipt_page()
		
			/**
			 * Check for Cryptoo.me Bitcoin  notification
			 *
			 * @access public
			 * @return void
			 */
			function check_notification() {
				global $woocommerce;
				
				$order_id = $_GET['data'];
				$session_tag = "mycryptoomebtc_invid_" . $order_id;
				if($_SESSION[$session_tag])
				{
					@ob_clean();
					$invid = $_SESSION[$session_tag];
					$api_key = $this->api_key; //ATT!!! WE DO NOT EXPOSE THAT ONE ON FRONTEND EVER! 

					$fields = array(
						'key'=> $api_key,
						'invid'=>$invid
					);

					$out = wp_remote_post( 'https://cryptoo.me/api/v1/invoice/state/', array(
					'method' => 'POST', 
					'body' => $fields)  );
					$out_ison = json_decode($out['body']);

					if ( 'yes' == $this->debug ) {
						$this->log->add( 'cryptoome', 'Transaction verification returned ' . print_r($out_ison, true) );
					}					
					
					if($out_ison->success == true)
					{
						$order = new WC_Order( $order_id );
					    // Payment completed
					    $order->add_order_note( __('Cryptoo.me Bitcoin  payment completed', 'wc_cryptoome_btc_payment_gateway') );
					    $order->payment_complete();
						$woocommerce->cart->empty_cart();
					                
					    // Set order as completed if user did set up it
					    if ( 'Y' == $this->set_completed ) {
					       	$order->update_status( 'completed' );
					    }
				
					    if ( 'yes' == $this->debug ) {
					       	$this->log->add( 'cryptoome', 'Payment complete for order ' .  $order_id );
					    }
						
						wp_redirect( $this->get_return_url( $order ) );						
					}//sucess
					
				}//session has invid
	
			}//check_notification()
		} //class WC_CryptooMe_BTC
		
	    /**
		 * Add the gateway to WooCommerce
		 *
		 * @access public
		 * @param array $methods
		 * @package 
		 * @return array
		 */
		function add_cryptoome_btc_gateway( $methods ) {
			$methods[] = 'WC_CryptooMe_BTC';
			return $methods;
		}
		
				
		add_filter('woocommerce_payment_gateways', 'add_cryptoome_btc_gateway' );
	}

//plugin menu settings stuff	
	add_filter( "plugin_action_links_" . plugin_basename(  __FILE__ ), 'add_cryptoome_btc_gateway_add_settings_link' );	
	
	function add_cryptoome_btc_gateway_add_settings_link( $links ) {
		$img = '<img style="vertical-align: middle;width:24px;height:24px;border:0;" src="'. plugin_dir_url( __FILE__ ) . 'assets/images/icons/icon-256x256.png'.'"></img>';	
		$settings_link = '<a href="' . admin_url('/admin.php?page=wc-settings&tab=checkout&section=mycryptoomebtc') . '">' . $img . __( 'Settings' ) . '</a>';
		array_unshift($links , $settings_link);	
		return $links;
	}
