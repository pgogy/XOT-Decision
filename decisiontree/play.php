<?php

//Function show_template
//
// Version 1.0 University of Nottingham
// (pl)
// Set up the preview window for a xerte piece

function show_template($row_play){
    
	global $xerte_toolkits_site;

	$data = file_get_contents($xerte_toolkits_site->users_file_area_short . $row_play['template_id'] . "-" . $row_play['username'] . "-" . $row_play['template_name'] . "/data.inc");
	
	require_once(dirname(__FILE__) . '/module_functions.php');
	
	show_tree($data);
	
}
