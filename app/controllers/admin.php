<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	/**
	 * Show Page for this controller.
	 *
	 */
	public function makeColor() {
		$this->load->database();
//		$card_hdl = $this->db->query('SELECT * FROM cards WHERE cards.mana_cost IS NULL AND card_type <> \'Vanguard\' AND card_type NOT LIKE \'%Scheme%\' AND card_type <> \'Land\' AND card_type <> \'Planeswalker\' AND card_type NOT LIKE \'%Land%\' ');
		$card_hdl = $this->db->query("SELECT * FROM cards WHERE cards.mana_cost IS NULL AND card_type is null AND card_text  ~ '(X|[0-9])*\((b|u|r|g|w|[0-9])/(b|u|r|g|w|p)*\)(B|U|R|G|W)*' AND card_text  !~ 'Plane.*'  ");
//		$card_hdl = $this->db->query('SELECT * FROM cards WHERE cards.mana_cost IS NULL AND card_type = \'Instant\' AND card_id<>3222 ');
		var_dump(count($card_hdl->result()));
//		$card_hdl = $this->db->get();		
		foreach ($card_hdl->result() as $row) {
		    echo '<br/>'.$row->card_id.'<br/>';
		    echo $row->card_name.'<br/>';
		    echo $row->card_type.'<br/>';
//		    gatherer.wizards.com/pages/search/default.aspx?name=+["Acid Web Spider"]
		    echo nl2br($row->card_text).'<br/>';
//		    var_dump($row->card_text);
			$ZEcard_id = $row->card_id;
		    echo '<hr/><br/>';
		    
		    $patern = "#((X|[0-9])*(\((b|u|r|g|w|[0-9])/(b|u|r|g|w|p)\))+)(B|U|R|G|W)*#um";
		    preg_match($patern , $row->card_text, $results_preg);
		    $mana_found = $results_preg[0];
//		    var_dump($results_preg);
		   	    
		    $buffer_text = str_replace($results_preg[0].chr(13).chr(10), '', $row->card_text);
		    $buffer_array = explode("\n", $buffer_text);
		    $i =0;
		    
		    $card_subtype_buffer = explode('--', $buffer_array[$i]);
	    	$card_type = trim($card_subtype_buffer[0]);
	    	$card_subtype = trim($card_subtype_buffer[1]);
	    	$i++;
	    	
	    	
	    	$patern = "#^(([0-9]|\*)+/([0-9]|\*)+)#u";
		    preg_match($patern , $buffer_array[$i], $results_preg);
	    	$card_pow = null;
	    	$card_def = null;
	    	$card_combat = null;
	    	if($results_preg != null) {
	    		$card_combat = explode('/', $results_preg[0]);
	    		
	    		$card_pow = trim($card_combat[0]);
	    		$card_def = trim($card_combat[1]);
	    		$i++;
	    	}
	    	$max = count($buffer_array);
	    	$zeText = null;
	    	for($i; $i < $max; $i++) {
	    		$zeText .= trim($buffer_array[$i])."\n";
	    	}
	    	
	    	
	    	if($mana_found != null && $zeText != null) {
		    	 echo 'mana found: '.$mana_found.'<br/>';
		    	 echo 'type found: '.$card_type.'--'.$card_subtype.'<br/>';
		    	 echo 'card_combat found: '.$card_pow.'/'.$card_def.'<br/>';
		    	 echo 'text found: '.$zeText.'<br/>';
			   
			   $qry = "UPDATE cards SET mana_cost='".pg_escape_string($mana_found)."', card_type='".pg_escape_string($card_type)."', card_subtype='".pg_escape_string($card_subtype)."', power='".pg_escape_string($card_pow)."', defense='".pg_escape_string($card_def)."', card_text='".pg_escape_string($zeText)."' WHERE card_id=".$ZEcard_id;
				echo '<b>'.$qry.'</b>';
//				$done = $this->db->query($qry);
//				echo $ZEcard_id. '  '. $done.'<br/>';
				
	//		
	//		    var_dump($buffer_text);
	//		    var_dump(ord($buffer_text[0]));
			}
		    
		    echo '<hr/><hr/><hr/><br/>';
		    
		    
		    
		    $this->db->select('*')->from('cards_releases');
			$this->db->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
			$this->db->join('rarities', 'rarities.rarity_id = cards_releases.rarity_id', 'left');
			$this->db->where('cards_releases.card_id', $row->card_id)->order_by('editions.date', 'DESC'); 
			$card_infos_hdl = $this->db->get();
			$card_infos = $card_infos_hdl->result();
			foreach ($card_infos_hdl->result() as $release) {
				if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/images/scans/'.$release->edition_code.'/'.$row->card_name.'.full.jpg') ) {
					
					echo '<img src="/public/images/scans/'.$release->edition_code.'/'.str_replace(array('?', '!'), '', $row->card_name).'.full.jpg" alt="'.$release->edition_code.'-'.str_replace('?', '', $row->card_name).'" />';
					break;
				}
			}
		    
		    
//		    $card = $row;
		}
		
		
		$this->load->view('cards_index');
	}
	
	public function makeManaCostTable() {
		$this->load->database();
		
		
		/**
		* patern mana incolor 
		* #^([0-9]+)#
		*
		* patern mana bicolore et mana phyrexian
		* #^((\((b|u|r|g|w|[0-9]+)/(b|u|r|g|w|p)\))+)#
		*
		* patern mana coloré
		* #^((B|U|R|W|G)+)#
		*/
				
		$card_hdl = $this->db->query('SELECT * FROM cards WHERE cards.mana_cost IS NOT NULL AND card_type <> \'Vanguard\' AND card_type NOT LIKE \'%Scheme%\' ORDER BY card_id');
//		var_dump($card_hdl->result());
		foreach ($card_hdl->result() as $row) {
			
//			echo '<br/>'.$row->card_id.'<br/>';
//		    echo $row->card_name.'<br/>';
//		    echo $row->card_type.'<br/>';
//		    echo $row->mana_cost.'<br/>';
////		    gatherer.wizards.com/pages/search/default.aspx?name=+["Acid Web Spider"]
//		    echo nl2br($row->card_text).'<br/>';
////		    var_dump($row->card_text);
			
//		    echo '<hr/><br/>';
		    
			$ZEcard_id = $row->card_id;
			$buffer = $row->mana_cost;
			//on a un X incolore au debut, on ajoute deja ca
			$i=0;
			if($buffer[$i] == 'X') {
				//appel db pour ajouter
				$qry = "INSERT INTO cards_mana_costs ( card_id, mana_form_id) VALUES (".$ZEcard_id.", 22)";
//				$done = $this->db->query($qry);
				
				$buffer = substr($buffer,1);				
				if($buffer[$i] == 'X') {
					//appel db pour ajouter
//					$done = $this->db->query($qry);
					
					$buffer = substr($buffer,1);
				}
			}
			
			// On cherche les mana incolore
			$patern = "#^([0-9]+)#u";			
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				// Appel DB
				// $results_preg[0] contient le nomber d'incolore a ajouter
				
//			echo '<br/>'.$row->card_id.'<br/>';
//		    echo $row->card_name.'<br/>';
//		    echo $row->card_type.'<br/>';
//		    echo $row->mana_cost.'<br/>';
////		    gatherer.wizards.com/pages/search/default.aspx?name=+["Acid Web Spider"]
//		    echo nl2br($row->card_text).'<br/>';
////		    var_dump($row->card_text);
//			
//		    echo '<hr/><br/>';
//				var_dump($results_preg[0]);
				
				$qry = null;
				$qry = "INSERT INTO cards_mana_costs ( card_id, mana_form_id) VALUES (".$ZEcard_id.", (SELECT mana_form_id FROM mana_forms WHERE mana_form_code='".$results_preg[0]."') )";
//				var_dump($qry);
//				$done = $this->db->query($qry);
				
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
			
			//On cherche les mana bizarre, bicolore et phyrexian
			$patern = "#^((\((b|u|r|g|w|[0-9]+)/(b|u|r|g|w|p)\))+)#u";		
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				// On a dans $results_preg[0] le resultat brute, on en fait des grappes de mana bicolor. 
				// En gros des pack de la forme u/r ou u/p, etc

				$tmp = explode('(',$results_preg[0]);
				unset($tmp[0]);
				foreach( $tmp  as $grappe) {
					// On nettoie la grappe pour la sauver en base
					$grappe = substr($grappe, 0,-1);
					$grappe = strtoupper(str_replace('/', '', $grappe));
					// Appel DB
					// $grappe contient a chaque fois la forme de mana bicolore ou phyrexian  a jouter
					// Il faut en ajouter autant que d'iteration
					$qry = null;
					$qry = "INSERT INTO cards_mana_costs ( card_id, mana_form_id) VALUES (".$ZEcard_id.", (SELECT mana_form_id FROM mana_forms WHERE mana_form_code='".$grappe."') )";
//					var_dump($qry);
//					$done = $this->db->query($qry);
					
				}
				
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
			
			// Enfin on chercher le mana coloré
			$patern = "#^((B|U|R|W|G)+)#u";			
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				foreach(  str_split($results_preg[0]) as $mana_simple) {					
					// Appel DB
					// $mana_simple contient a chaque fois la forme de mana coloré simple
					// Il faut en ajouter autant que d'iteration
					$qry = null;
					$qry = "INSERT INTO cards_mana_costs ( card_id, mana_form_id) VALUES (".$ZEcard_id.", (SELECT mana_form_id FROM mana_forms WHERE mana_form_code='".$mana_simple."') )";
//					var_dump($qry);
//					$done = $this->db->query($qry);

//					var_dump($mana_simple);
					
				}
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
//			if($buffer != false)
//				var_dump($buffer);
		}
		
		
		$this->load->view('cards_index');
	}
	
	public function makeCmc() {
		$this->load->database();
		
		
		/**
		* patern mana incolor 
		* #^([0-9]+)#
		*
		* patern mana bicolore et mana phyrexian
		* #^((\((b|u|r|g|w|[0-9]+)/(b|u|r|g|w|p)\))+)#
		*
		* patern mana coloré
		* #^((B|U|R|W|G)+)#
		*/
				
		$card_hdl = $this->db->query('SELECT * FROM cards WHERE cards.mana_cost IS NOT NULL ORDER BY card_id');
//		var_dump($card_hdl->result());
		foreach ($card_hdl->result() as $row) {
			
			
			$ZEcard_id = $row->card_id;
			$buffer = $row->mana_cost;
			
			$cmc = 0;
			$i=0;
			//on a un X incolore au debut, on ajoute deja ca
			if($buffer[$i] == 'X') {
				$cmc++;
				
				$buffer = substr($buffer,1);				
				if($buffer[$i] == 'X') {
					$cmc++;
					
					$buffer = substr($buffer,1);
				}
			}
			
			// On cherche les mana incolore
			$patern = "#^([0-9]+)#u";			
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				$cmc+=$results_preg[0];
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
			
			//On cherche les mana bizarre, bicolore et phyrexian
			$patern = "#^((\((b|u|r|g|w|[0-9]+)/(b|u|r|g|w|p)\))+)#u";		
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				// On a dans $results_preg[0] le resultat brute, on en fait des grappes de mana bicolor. 
				// En gros des pack de la forme u/r ou u/p, etc

				$tmp = explode('(',$results_preg[0]);
				unset($tmp[0]);
				foreach( $tmp  as $grappe) {
					$cmc++;
				}
				
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
			
			// Enfin on chercher le mana coloré
			$patern = "#^((B|U|R|W|G)+)#u";			
			if(preg_match($patern , $buffer, $results_preg) !== 0) {
				foreach(  str_split($results_preg[0]) as $mana_simple) {					
					// Il faut en ajouter autant que d'iteration
					$cmc++;					
					
				}
				$buffer = substr($buffer, strlen($results_preg[0]) );
			}
			
			$qry = null;
			$qry = "UPDATE cards SET converted_mana_cost=".$cmc." WHERE card_id=".$ZEcard_id;
//			var_dump($qry);
//			$done = $this->db->query($qry);
			
			
//			echo '<br/>'.$row->card_id.'<br/>';
//		    echo $row->card_name.'<br/>';
//		    echo $row->card_type.'<br/>';
//		    echo $row->mana_cost.'('.$cmc.')<br/>';
////		    gatherer.wizards.com/pages/search/default.aspx?name=+["Acid Web Spider"]
//		    echo nl2br($row->card_text).'<br/>';
////		    var_dump($row->card_text);
//			
//		    echo '<hr/><br/>';
		    
//			if($buffer != false)
//				var_dump($buffer);
		}
		
		$this->load->view('cards_index');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		$this->load->database();
		$card_hdl = $this->db->query('SELECT * FROM cards WHERE cards.mana_cost IS NULL AND card_type <> \'Vanguard\' AND card_type NOT LIKE \'%Scheme%\' ');
//		var_dump($card_hdl->result());
		foreach ($card_hdl->result() as $row) {
		    echo '<br/>'.$row->card_id.'<br/>';
		    echo $row->card_name.'<br/>';
		    echo $row->card_type.'<br/>';
//		    gatherer.wizards.com/pages/search/default.aspx?name=+["Acid Web Spider"]
		    echo nl2br($row->card_text).'<br/>';
//		    var_dump($row->card_text);
			$ZEcard_id = $row->card_id;
		    echo '<hr/><br/>';
		    
		    $this->db->select('*')->from('cards_releases');
			$this->db->join('editions', 'editions.edition_id = cards_releases.edition_id', 'left');
			$this->db->join('rarities', 'rarities.rarity_id = cards_releases.rarity_id', 'left');
			$this->db->where('cards_releases.card_id', $row->card_id)->order_by('editions.date', 'DESC'); 
			$card_infos_hdl = $this->db->get();
			$card_infos = $card_infos_hdl->result();
			foreach ($card_infos_hdl->result() as $release) {
				if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/images/scans/'.$release->edition_code.'/'.$row->card_name.'.full.jpg') ) {
					
					echo '<img src="/public/images/scans/'.$release->edition_code.'/'.str_replace(array('?', '!'), '', $row->card_name).'.full.jpg" alt="'.$release->edition_code.'-'.str_replace('?', '', $row->card_name).'" />';
					break;
				}
			}
		    
		    
//		    $card = $row;
		}
		
		$this->load->view('cards_index');
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