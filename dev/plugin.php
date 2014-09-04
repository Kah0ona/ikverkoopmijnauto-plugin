<?php
/*
Plugin Name: ikverkoopmijnauto plugin
Plugin URI: http://www.lokaalgevonden.nl
Description: This plugin connects to the backend webshops of IkVerkoopMijnAuto
Version: 1.0
Author: Marten Sytema
Author URI: http://www.sytematic.nl
Author Email: marten@sytematic.nl
License:

  Copyright 2013 Sytematic Software (marten@sytematic.nl)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if($_SERVER['SERVER_NAME'] != 'localhost')
	define('SYSTEM_URL_BACKEND', 'http://ikverkoopmijnauto.lokaalgevonden.nl');
else
	define('SYSTEM_URL_BACKEND', 'http://ikverkoopmijnautodev.lokaalgevonden.nl');
define('BASE_URL_BACKEND', SYSTEM_URL_BACKEND.'/public');
define('EURO_FORMAT', '%.2n');
define('IVMA_PLUGIN_PATH', plugin_dir_path(__FILE__) );

setlocale(LC_MONETARY, 'it_IT');
class IkVerkoopMijnAuto {
	protected $options = null;

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		add_shortcode('ikverkoopmijnauto', array($this,'render_form'));
		add_shortcode('ikverkoopmijnauto_ads', array($this,'render_ads'));


		// Load plugin text domain
		add_action('init', array( $this, 'plugin_textdomain' ) );
		add_action('init', array($this, 'load_options'));
		//add_action('template_redirect', array($this,'template_redirect'));

		//add_filter('the_posts', array($this, 'init_models'));

		// Register admin styles and scripts
		add_action('admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action('admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	
		// Register site styles and scripts
		add_action('wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action('wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
	
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
				
		add_action('widgets_init', array($this, 'register_widgets' ));
				
	
		
	
		//this must be inside is_admin, see: http://codex.wordpress.org/AJAX_in_Plugins#Ajax_on_the_Viewer-Facing_Side	
		if(is_admin()){
			add_action('wp_ajax_nopriv_place_order',array($this,'process_order'));			
			add_action('wp_ajax_place_order',array($this,'process_order'));		
			add_action('wp_ajax_nopriv_relay_postcode',array($this,'relay_postcode'));			
			add_action('wp_ajax_relay_postcode',array($this,'relay_postcode'));		
		}
	} // end constructor
	

	function reArrayFiles(&$file_post) {

		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);

		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}

		return $file_ary;
	}	
	
	public function relay_postcode(){
		$zip = $_POST['zip'];
		$nr = $_POST['nr'];
		include_once('lib/postcodelib.php');

		$client = new PostcodeNl_Api_RestClient('MfNrQ12hKOVSIguKUyWdvRUNAcONh0Y7k7kJpQ4bXzQ',
			'ah2iQyNWTXjTmGDa1bGZ5SI2KUv5mYpNpNgyPhkOgEE');

		header('Content-Type: application/json');
		try {
			$result = $client->lookupAddress($zip,$nr,null,false);
			echo json_encode($result);
		} catch(Exception $e){
			echo '{}';
		}

		exit;
	}
	/**
	* Responds to the ajax call that submits the checkout form
	*/
	public function process_order(){
		session_start();
		$this->load_options();
		$_POST['CarModel_id'] = $_POST['modelName'];

		ob_start();


		//file uploads
		$stamp = time(); //timestamp
		$path = IVMA_PLUGIN_PATH."/uploads/".$stamp."/";
		if (!file_exists($path)) {
		    mkdir($path, 0777, true);
		}

		$file_ary = $this->reArrayFiles($_FILES['picture']);
		print_r($file_ary);
		$arr  = array();
		foreach ($file_ary as $file) {
//			print 'File Name: ' . $file['name'];
//			print 'File Type: ' . $file['type'];
//			print 'File Size: ' . $file['size'];
		    move_uploaded_file($file["tmp_name"],
				$path.$file['name']);
			chmod($path.$file['name'], 0777);
			$this->resizeImage($path.$file['name']);
			$arr[] = plugins_url().'/ikverkoopmijnauto-plugin/uploads/'.$stamp.'/'.$file['name'];
		}
		$_POST['picture'] = $arr;
		$_POST['street'] = $_POST['street'] . ' ' . $_POST['number'];

		print_r($_POST);	
		$bod = ob_get_contents();
		ob_end_clean();			
		echo $this->curl_post(BASE_URL_BACKEND.'/newcar',$_POST);	
		$this->logMessage($bod);
			
		exit;
	}

	protected function resizeImage($file){
	/*	$thumb = new Imagick();
		$thumb->readImage($file);    
		$thumb->resizeImage(800, 0, Imagick::FILTER_LANCZOS,1);//800 width, keep A/R
		$thumb->writeImage($file);
		$thumb->clear();
		$thumb->destroy(); */
	}

	protected function decodeParamsIntoGetString($params){
		$ret = "";
		$c = 0;
		foreach($params as $k=>$v){
			$ret .= ($c == 0) ? '' : '&';		
			if(is_array($v)){
				$c2 = 0;
				foreach($v as $v1){
					if($c2 != 0)
						$ret .= '&';
					$ret .= $k.'='.urlencode($v1);
					$c2++;
				}
			
			}
			else {
	
				$ret .= $k.'='.urlencode($v);
	
			}
			$c++;
		}
		return $ret;
	} 


	/**
	 * Send a POST requst using cURL
	 * @param string $url to request
	 * @param array $post values to send
	 * @param array $options for cURL
	 * @return string
	 */
	protected function curl_post($url, array $post = NULL, array $options = array()) {
		$this->curlError = 0;
	    $defaults = array(
	        CURLOPT_POST => 1,
	        CURLOPT_HEADER => 0,
	        CURLOPT_URL => $url,
	        CURLOPT_FRESH_CONNECT => 1,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_FORBID_REUSE => 1,
	        CURLOPT_TIMEOUT => 4,
	        CURLOPT_POSTFIELDS => $this->decodeParamsIntoGetString($post),
			CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
	    );
	    
	    $this->logMessage("Posting to: ".$this->decodeParamsIntoGetString($post));
		
	    $ch = curl_init();
	    curl_setopt_array($ch, ($options + $defaults));
	    if( !$result = curl_exec($ch)){    
	    	$this->curlError = curl_error($ch);
	    	return false;
	    }
	    else {
	    	return $result;
	   
	   	} 	
	}
	
	


	private function logMessage($msg){
		date_default_timezone_set('Europe/Amsterdam');
		file_put_contents(IVMA_PLUGIN_PATH.'logs/order.log',date("Y-m-d H:i:s").': '.$msg."\n",FILE_APPEND);
	}

	public function register_widgets(){
	}
	
	public function load_options(){
		include_once('models/PluginOptions.php');
		$w = new PluginOptions();
		$w->loadOptions();
		$this->options = $w;
	}
	
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		// TODO:	Define activation functionality here
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		// TODO:	Define deactivation functionality here		
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		// TODO:	Define uninstall functionality here		
	} // end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		$domain = 'ikverkoopmijnauto-locale';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
		wp_enqueue_style('ikverkoopmijnautocss', plugins_url().'/ikverkoopmijnauto-plugin/css/plugin.css');

	} // end register_plugin_styles 
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
		//wp_enqueue_script('bootstrap-js', plugins_url('/webshop-plugin/js/bootstrap.min.js'), array('jquery') );		

	} // end register_plugin_scripts
	


	/*---------------------------------------------*
	 * Controller Functions
	 *---------------------------------------------*/
	public function render_ads(){
		ob_start();
		include_once('models/AdsModel.php');
		include_once('views/AdsView.php');
		$m = new AdsModel($this->options);
		$v = new AdsView($m);
		$data = $m->getData();
		$v->render($data);
		$output = ob_get_contents();
		ob_end_clean();	
	
		return $output;	

	}


	public function render_form($atts){
		extract(shortcode_atts(
			array(
			), $atts)
		);

		ob_start();
		include_once('models/FormModel.php');
		include_once('views/FormView.php');
		$m = new FormModel($this->options);
		$v = new FormView($m);
		$v->render();
		$output = ob_get_contents();
		ob_end_clean();	
	
		return $output;	
	}
	
} // end class

$ikverkoopmijnauto = new IkVerkoopMijnAuto();

