<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MTG project
 *
 * @package		mtg
 * @author		Th3D0c
 * @link		http://www.thedochouse.org
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Mana Helpers
 *
 * @package		mtg
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Th3D0c
 */

// ------------------------------------------------------------------------

if ( ! defined('_IMAGES_DIR_')) exit('You must define images path before!');
/**
 * make img tag from mana cost code array 
 *
 * Returns img html tag for each mana code
 *
 * @access	public
 * @param Array $mana_cost
 * @return	string
 */
 if ( ! function_exists('display_mana_cost')) {
	function display_mana_cost(Array $mana_cost) {
		$buffer = null;
		foreach($mana_cost as $mana_code) {
			$buffer .= '<img src="'._IMAGES_DIR_.'cards_picto/'.$mana_code.'.png" alt="'.$mana_code.'" />';
		}
		return $buffer;
	}
}

/**
 * get image for editions
 *
 * Returns string containing edition name, a coma then the img html tag for this edition
 *
 * @access	public
 * @param Array $editions
 * @return	string
 */
 if ( ! function_exists('display_editions_img')) {
	function display_editions_img(Array $editions) {
		$buffer = null;
		foreach($editions as $edition_info) {
			$buffer .= $edition_info['edition_name'].
			' <img src="'._IMAGES_DIR_.'extetions_pictos/'.$edition_info['edition_code'].'-'.$edition_info['rarity_code'].'.png" alt="'.$edition_info['edition_code'].'" />, ';
		}
		// We replace trailing ',' by a space caracter
		$buffer[strlen($buffer)-2] = ' ';
		return $buffer;
	}
}

/**
 * get images for card text
 *
 * Returns string containing card text with pictos replacement for mana and other "illustrations"
 * mana forms into text are:
 *				-{(u/w)} or {(u/w){(u/w)} when more than one. Note the missing } after the first mana form
 				 Works for both two color, X/color and phyrexian mana
 *				- X, T, B or any other simple mana form
 *
 * @access	public
 * @param string $card_text
 * @return	string
 */
 if ( ! function_exists('parse_card_text')) {
	function parse_card_text($card_text) {
		// On commence par les mana colorés, c'est le plus compliqué
		$patern = "#(({\((b|u|r|g|w|[0-9]+)/(b|u|r|g|w|p)+\)}?)+)#u";		
		if(preg_match($patern , $card_text, $results_preg) !== 0) {
			$buffer = null;
			$tmp = explode('{', substr($results_preg[0], 1,-1) );
			foreach( $tmp  as $grappe) {
				// On nettoie la grappe, les () et le slash
				$grappe = strtoupper(str_replace('/', '', substr($grappe, 1,-1) ));
				$buffer .= '<img src="'._IMAGES_DIR_.'cards_picto/'.$grappe.'.png" alt="'.$grappe.'" />';
			}
			$card_text = str_replace($results_preg[0], $buffer, $card_text);
		}
		
		$uncolored_patern = '#{([0-9]+|T|G|U|R|B|W)}#u';
		$uncolored_replacement = '<img src="'._IMAGES_DIR_.'cards_picto/$1.png" alt="$1" />';
		return nl2br( preg_replace($uncolored_patern, $uncolored_replacement, $card_text) );
	}
}

/* End of file date_helper.php */
/* Location: ./system/helpers/date_helper.php */