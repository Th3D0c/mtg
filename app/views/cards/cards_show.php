
	<?=$card_name;?><br/><br/>
	<img src="/public/images/scans/<?=$display_edition_code;?>/<?=$card_name;?>.full.jpg" alt="card preview" />
	<div>
		Mana Cost: <?=display_mana_cost($mana_cost);?>
		<br/>
		Converted Mana cost: <?=$converted_mana_cost;?><br/>
		Type: <?=$card_type;?> - <?=$card_subtype;?><br/>
		<?php
			if(!empty($card_power)) {
				echo 'Att/Def: '.$card_power.'/'.$card_defense.'<br/>';
			}
		?>		
		<br/>
	
		Text:<br/><?=parse_card_text($card_text);?><br/>
		<br/>
		Editions: <?=display_editions_img($display_editions_img);?> <br/>
		
		Rarity: <?=$rarity_name;?><br/>		
		
	</div>
