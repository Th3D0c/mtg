<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2011, VME
  */

/**
 * My_Exceptions Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Exceptions
 * @author	Mickael Suisse m.suisse@gmail.com
  */
class MY_Exceptions extends CI_Exceptions {

	/**
	 * Exception Logger
	 *
	 * This function logs PHP generated error messages
	 *
	 * @access	private
	 * @param	string	the error severity
	 * @param	string	the error string
	 * @param	string	the error filepath
	 * @param	string	the error line number
	 * @return	string
	 */
	function log_exception($severity, $message, $filepath, $line)
	{
		if(  !empty($this->levels[$severity]) ) {
			$severity_label = $this->levels[$severity];
		}
		
		if( empty($this->levels[$severity]) )  {
			log_message($severity, $message. ' '.$filepath.' '.$line, TRUE);
		} else {
			log_message('PHP_ERROR', 'Severity: '.$this->levels[$severity].'  --> '.$message. ' '.$filepath.' '.$line, TRUE);
		}
	}

}
