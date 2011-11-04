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
class MY_Log extends CI_Log{
	protected $_levels	= array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3',  'PHP_ERROR' => '4', 'ALL' => '5');
}
// END Log Class

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */
