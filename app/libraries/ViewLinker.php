<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */



/**
 * Logging Class extention. We just add log level  to be able to differ from default CI log levels
 * This way we can have differents level in logs and start or stop them at will.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author	Mickael Suisse m.suisse@gmail.com
 */
class ViewLinker {
	
	public $base_view;
	
	private $A_css_sheets = Array();
	private $A_js_scripts = Array();
	private $CI;
	
//	view
	public function __construct () {
		$this->CI =& get_instance();
		$this->CI->config->load('view_linker');
		$this->CI->load->helper('view_linker');
	}
	
	public function addCssSheet($css_file) {
		$this->A_css_sheets[] = $css_file;
	}
	
	public function addJsScript($js_script) {
		$this->A_js_scripts[] = $js_script;
	}
	
	public function getCssSheets() {
		return array_merge($this->CI->config->item('A_default_css_sheets'), $this->A_css_sheets );
	}
	
	public function getJsScripts() {
		return array_merge($this->CI->config->item('A_default_js_scritps'), $this->A_js_scripts );
	}
	
	public function view($page_to_render, $page_data = null) {
		if( !empty($page_to_render) ) { 
			$this->base_view = $page_to_render;
		}
		
		$header_data['cssSheets'] = $this->getCssSheets();
		$header_data['jsScripts'] = $this->getJsScripts();
		$this->CI->load->view('common/header', $header_data);
		$this->CI->load->view($this->base_view, $page_data);
		$this->CI->load->view('common/footer');		
	}
}
// END Log Class

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */
