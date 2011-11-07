<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	
	
	</style>
	<script>
 
  </script>
</head>
<body>


<select name="edition">
	<?php
		foreach($editions_avialabe as $edition) {
			echo '<option value="'.$edition->edition_code.'">'.$edition->edition_name.'</option>';
		}
	?>
</select>
<div class="flow_container">
	<ul id="cover_flow">
	<?php
	
		foreach($cards_in_flow as $card) {
			echo '<li><img src="'._IMAGES_DIR_.'/scans/'.$card['display_edition_code'].'/'.$card['card_name'].'.full.jpg" alt="card preview" width="250px" /></li>';
		}
	?>
	</ul>
</div>
<a href="#" id="previous">Previous!</a>
<a href="#" id="next">Next!</a>

<div id="container">
	<form action="" method="post" >
		Card Name: <input type="text" name="card" id="card" />
		Quantity: <input type="text" name="quantity" id="quantity" /> 
		<input type="submit" >
	</form>
</div>

</body>
</html>