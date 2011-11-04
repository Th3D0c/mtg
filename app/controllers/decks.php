<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decks extends CI_Controller {
	protected $ViewLinker;
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function getCardsNames() {
		$this->load->database();
		$this->db->select('card_name')->from('cards');
		$this->db->like('card_name', $this->input->get('term', true), 'after');		
		$query = $this->db->get();
		$card_names = array();
		foreach ($query->result() as $row) {
			$card_names[] = $row->card_name;
		}
		echo json_encode($card_names);
	}
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		$view_data = null;
		$this->viewlinker->view('decks/decks_index', $view_data);
	}
	
	function manaHelper($mana_string) {
		$buff = null;
		foreach(str_split($mana_string) as $char){
			$buff .= '<img src="/public/images/cards_picto/'.$char.'.png" alt="'.$char.'" />';
		}
		return $buff;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
