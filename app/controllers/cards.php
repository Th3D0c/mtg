<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cards extends CI_Controller {
	protected $ViewLinker;
	/**
	 * Show Page for this controller.
	 *
	 */
	public function show($card_id) {
		$this->load->helper('cards_display');
		$this->load->database();
		
		$this->db->select('*')->from('cards')->where('cards.card_id', $card_id);
		$card_hdl = $this->db->get();
		foreach ($card_hdl->result() as $row) {			
			$wiew_data['card_name'] = $row->card_name;
			$wiew_data['converted_mana_cost'] = $row->converted_mana_cost;
			$wiew_data['card_type'] = $row->card_type;
			$wiew_data['card_subtype'] = $row->card_subtype;
			$wiew_data['card_power'] = $row->power;
			$wiew_data['card_defense'] = $row->defense;
			$wiew_data['card_text'] = trim($row->card_text);
		}
		
		// On get les infos de mana pour afficher les pictos
		$this->db->select('*')->from('cards_mana_costs');
		$this->db->join('mana_forms', 'mana_forms.mana_form_id = cards_mana_costs.mana_form_id', 'left');		
		$this->db->where('cards_mana_costs.card_id', $card_id)->order_by('mana_forms.display_order', 'ASC'); 
		$card_infos_hdl = $this->db->get();
		$card_infos = $card_infos_hdl->result();		
		foreach ($card_infos_hdl->result() as $row) {
			$wiew_data['mana_cost'][] = $row->mana_form_code;
		}
		
		// On load les editions pour l'affichage et pour trouver un scan de la carte
		$this->db->select('*')->from('cards_releases');
		$this->db->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
		$this->db->join('rarities', 'rarities.rarity_id = cards_releases.rarity_id', 'left');
		$this->db->where('cards_releases.card_id', $card_id)->order_by('editions.date', 'DESC'); 
		$card_infos_hdl = $this->db->get();
		$card_infos = $card_infos_hdl->result();
		$wiew_data['display_edition_code'] = null;
		foreach ($card_infos_hdl->result() as $row) {			
			$wiew_data['rarity_name'] = $row->rarity_name;
			$wiew_data['display_editions_img'][] = array('edition_name' => $row->edition_name, 'edition_code' => $row->edition_code, 'rarity_code' => $row->rarity_code);
			
			//On trouve une images qui correspond
			if($wiew_data['display_edition_code'] == null && file_exists($_SERVER['DOCUMENT_ROOT']._IMAGES_DIR_.'scans/'.$row->edition_code.'/'.$wiew_data['card_name'].'.full.jpg') ) {
				$wiew_data['display_edition_code'] = $row->edition_code;
			}			
		}
		
		$this->viewlinker->view('cards/cards_show', $wiew_data);
	}
	
	public function imgOnly($card_id) {
		$this->load->database();
		$this->load->database();
		$this->db->select('*')->from('cards')->where('cards.card_id', $card_id);
		$card_hdl = $this->db->get();
		$card = $card_hdl->result();
		
		$this->db->select('*')->from('cards_releases')->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
		$this->db->where('cards_releases.card_id', $card_id)->order_by('editions.date', 'DESC'); 
		$card_infos_hdl = $this->db->get();
		$card_infos = $card_infos_hdl->result();
		foreach ($card_infos_hdl->result() as $row) {
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/images/scans/'.$row->edition_code.'/'.$card[0]->card_name.'.full.jpg') ) {
				echo '<img src="/public/images/scans/'.$row->edition_code.'/'.$card[0]->card_name.'.full.jpg" alt="'.$row->edition_code.'-'.$card[0]->card_name.'" />';
				break;
			}
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		$this->load->database();
		$this->db->select('*')->from('cards');
		$this->db->join('cards_releases', 'cards.card_id = cards_releases.card_id', 'left');
		$this->db->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
		$this->db->join('rarities', 'rarities.rarity_id = cards_releases.rarity_id', 'left');
		$this->db->where('block', 'Innistrad')->or_where('block', 'Scars of Mirrodin'); 
		$query = $this->db->get();
		foreach ($query->result() as $row) {
		    echo '<a href="/cards/show/'.$row->card_id.'">'.$row->card_name.'</a>';
		    echo ' - '.$this->manaHelper($row->mana_cost);
		    echo ' - <img src="/public/images/extetions_pictos/'.$row->edition_code.'-'.$row->rarity_code.'.png" alt="'.$row->edition_code.'-'.$row->rarity_code.'" />';
		    echo '<br/>';
		}
		
		$this->viewlinker->view('cards/cards_index', $wiew_data);
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
