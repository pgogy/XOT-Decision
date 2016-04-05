<?PHP

	function publish($row_play, $template_id){
	
		global $xerte_toolkits_site;
		
		$data = file_get_contents($xerte_toolkits_site->users_file_area_short . $row_play['template_id'] . "-" . $row_play['username'] . "-" . $row_play['template_name'] . "/preview.inc");
		
		file_put_contents($data, $xerte_toolkits_site->users_file_area_short . $row_play['template_id'] . "-" . $row_play['username'] . "-" . $row_play['template_name'] . "/data.inc");
		
	}

?>