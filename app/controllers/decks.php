<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decks extends CI_Controller {
	public $viewlinker;
	
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
	* editions Page for this controller.
	*
	*/
	public function editions() {
		$this->viewlinker->addJsScript('jquery.roundabout');
		$this->viewlinker->addJsScript('jquery.mousewheel');
		$this->viewlinker->addJsScript('decks/decks_editions');
		$this->viewlinker->addCssSheet('decks_editions');
		$this->load->database();
		$view_data = array();
		
		$this->db->select('edition_code, edition_name')->from('editions')->order_by('editions.date', 'DESC');
		$view_data['editions_avialabe'] = $this->db->get()->result();
				
		$this->db->select('*')->from('cards')
			->join('cards_releases', 'cards_releases.card_id = cards.card_id', 'left')
			->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left')
			->join('cards_mana_costs', 'cards_mana_costs.card_id = cards.card_id', 'left')
			->join('mana_forms', 'mana_forms.mana_form_id = cards_mana_costs.mana_form_id', 'left')
			->where('editions.edition_code', 'ISD')
			->where('mana_forms.mana_form_code', 'B');				
		$query = $this->db->get()->result();
		
		$i=0;
		$view_data['cards_in_flow'] = array();
		$card_name = null;
		foreach ($query as $card_to_flow) {			
			if($card_name === $card_to_flow->card_name) {
				continue;
			} else  {
				$card_name = $card_to_flow->card_name;
				$view_data['cards_in_flow'][$i] = array('card_name' => $card_to_flow->card_name, 'display_edition_code' => null);
					
				// On load les editions pour l'affichage et pour trouver un scan de la carte
				$this->db->select('*')->from('cards_releases');
				$this->db->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
				$this->db->join('rarities', 'rarities.rarity_id = cards_releases.rarity_id', 'left');
				$this->db->where('cards_releases.card_id', $card_to_flow->card_id)->order_by('editions.date', 'DESC');
				$qq =$this->db->get();
				foreach ($qq->result() as $row) {
					//On trouve une images qui correspond
					if(file_exists($_SERVER['DOCUMENT_ROOT']._IMAGES_DIR_.'scans/'.$row->edition_code.'/'.$view_data['cards_in_flow'][$i]['card_name'].'.full.jpg') ) {
						$view_data['cards_in_flow'][$i]['display_edition_code'] = $row->edition_code;
						break;
					}
				}
				$i++;
			}
			
		}
		$this->viewlinker->view('decks/decks_editions', $view_data);
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
