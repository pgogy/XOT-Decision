<?php 

/**
 * 
 * module functions page, shared functions for this module
 *
 * @author Patrick Lockley
 * @version 1.0
 * @copyright Copyright (c) 2008,2009 University of Nottingham
 * @package
 */

require_once(dirname(__FILE__) . '/../../config.php');

/**
 * 
 * Function dont_show_template
 * This function outputs the HTML for people have no rights to this template
 * @version 1.0
 * @author Patrick Lockley
 */

function dont_show_template(){

	?>No rights to this template<?PHP

}

function show_tree($data){

		?><html>
	<head>
		<script type="text/javascript" src="modules/decisiontree/scripts/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="modules/decisiontree/scripts/json-2.js"></script>
		<script type="text/javascript" language="javascript">

			choices = Array();

			function show_choice(id, ref, obj){
					
				if(choices[ref]!=undefined){
				
					$(obj)
					.parent()
					.parent()
					.nextAll()
					.remove();
					
				}
				
				$('#' + id)
					.clone()
					.appendTo('.choice');
				
				choices[ref] = id;
					
				$(obj)
					.parent()
					.children()
					.each(
					
						function(index,value){
						
							$(value)
								.css({ opacity: 0.25 });
						
						}
					
					);
					
				$(obj)
					.css({ opacity: 1});
			
			}
		
		</script>
		<style>
		
			.library{
				display:none;
			}
		
			.choice p{
				border:2px solid black;
				padding:20px;
				margin:20px;
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
			}
			
			.choice div{
				padding:20px;
			}
			
			.choice div span{
				margin-right:20px;
				padding:10px;
				cursor:pointer;
				color:#fff;
				background:#000;
				cursor:hand;
				border:2px solid black;
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
			}
			
			.hide{
				display:none;
			}
		
		</style>
	</head>
	<body>
		<?PHP
				
	$data = json_decode($data, true);
	
	$counter = 0;
	
	$library = $data;

	echo "<div class='library'>";

	while($choice = array_shift($library)){
		
		echo "<div id='" . $choice['id'] . "'>";
		echo "<p style='background:#" . $choice['colour'] . "'>" . $choice['html'] . "</p>";
		
		echo "<div>";
		
		while($option = array_shift($choice['choices'])){
		
			echo "<span onclick='javascript:show_choice(" . $option[1] . "," . $choice['id'] . ", this);'>" . $option[0] . "</span>";
		
		}
		
		echo "</div>";
		echo "</div>";
	
	}
	
	echo "</div>";
	
	$choice = array_shift($data);
	
	echo "<div class='choice'>";
	echo "<div id='" . $choice['id'] . "'>";
	echo "<p>" . $choice['html'] . "</p>";
	
	echo "<div>";
	
	while($option = array_shift($choice['choices'])){
	
		echo "<span onclick='javascript:show_choice(" . $option[1] . "," . $choice['id'] . ", this);'>" . $option[0] . "</span>";
	
	}
	
	echo "</div>";
	
	echo "</div>";
	
	?>
	</body>
</html><?PHP

}