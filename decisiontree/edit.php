<?php
/**
 * allows the site to edit a simile module
 *
 * @author Patrick Lockley
 * @version 1.0
 * @copyright Copyright (c) 2008,2009 University of Nottingham
 * @package
 */


function output_editor_code($row_edit, $xerte_toolkits_site, $read_status, $version_control){

	require_once("config.php");

    $row_username = db_query_one("select username from {$xerte_toolkits_site->database_table_prefix}logindetails where login_id=?" , array($row_edit['user_id']));

    if(empty($row_username)) {
        die("Invalid user id ?");
    }

    /**
     * create the preview xml used for editing
     */

	if(isset($_POST['data_save'])){
	
		file_put_contents($xerte_toolkits_site->root_file_path . $xerte_toolkits_site->users_file_area_short . $row_edit['template_id'] . "-" . $row_username['username'] . "-" . $row_edit['template_name'] . "/preview.inc", $_POST['data_save']);
	
	}

    $preview = $xerte_toolkits_site->root_file_path . $xerte_toolkits_site->users_file_area_short . $row_edit['template_id'] . "-" . $row_username['username'] . "-" . $row_edit['template_name'] . "/preview.inc";
    $data = $xerte_toolkits_site->root_file_path . $xerte_toolkits_site->users_file_area_short . $row_edit['template_id'] . "-" . $row_username['username'] . "-" . $row_edit['template_name'] . "/data.inc";

    if(!file_exists($preview) && file_exists($data)){
        copy($data, $preview);
        chmod($preview, 0777);
    }
	
	if(file_exists($preview)){
	
		$data = json_decode(file_get_contents($preview));
		
	}else{
	
		$data = "";
	
	}
	
	?><html>
	<head>
		<script type="text/javascript" src="modules/decisiontree/scripts/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="modules/decisiontree/scripts/json-2.js"></script>
		<script type="text/javascript" src="modules/decisiontree/scripts/jscolor/jscolor.js"></script>
		<script type="text/javascript" language="javascript">
		
			function create_data(){
			
				data = new Object;
				
				$(".prompt")
					.each(
					
						function(index,value){
						
							set = new Object;
						
							set.id = $(value).attr("id");
							
							set.name = $(value)
								.children()
								.first()
								.next()
								.html();
								
							set.html = $(value)
								.children()
								.first()
								.next()
								.next()
								.next()
								.children()
								.last()
								.prev()
								.val();	
								
							set.colour = $(value)
								.children()
								.first()
								.next()
								.next()
								.next()
								.children()
								.last()
								.children()
								.first()
								.val();	
							
							set.choices = Array();
	
							data[set.id]=set;
							
							
						}
					
					);		
			
				$(".option")
					.each(
						
						function(index,value){
						
							set = data[$(value)
									.parent()
									.parent()
									.parent()
									.parent()
									.parent()
									.attr("id")];
							
							set.choices.push(Array(
								$(value)
								.children()
								.first()
								.next()
								.html(),
								
								$(value)
								.next()
								.children()
								.last()
								.prev()
								.val()
							
								)
							);
							
							data[$(value)
									.parent()
									.parent()
									.parent()
									.parent()
									.parent()
									.attr("id")] = set;
						
						}
	
					);
					
				if(data){
				
					$("#data_send").val(JSON.stringify(data, null, 2));
					$("#data_save").val(JSON.stringify(data, null, 2));
				
				}else{
			
					return false;
					
				}
			
			}
		
			function delete_prompt(obj){
			
				var del = confirm("Are you sure you want to delete");
			
				if($(".title").length!=1){
			
					if(del==true){
			
						obj.parentNode.remove();
						
					}
				
				}
				
				select_menus();
			
			}
			
			function delete_choice(obj){
			
				obj.parentNode.parentNode.remove();
				
			}
		
			function expand_choice(obj){
				
				$(obj)
					.parent()
					.children()
					.each(
					
						function(index,value){
						
							if(value.tagName=="DIV"){
							
								$(value)
									.toggleClass("hide",1);
							
							}
						
						}
					
					)
			
			}
		
			function choices_children(){
			
				return $(".title").length;
			
			}
		
			function get_select_menu_options(){
				
				choices_store = Array("<option value='null'>Choose</option>");
			
				$(".title")
					.each(
					
						function(index,value){
						
							choices_store.push("<option value='" + $(value).parent().attr("id") + "'>" + (index+1) + ". " + $(value).html() + "</option>");
						
						}
					
					);
					
				return choices_store.join("");
			
			}
		
			function select_menus(){
			
				choices_store = Array();

				$(".title")
					.each(
					
						function(index,value){
					
							choices_store.push("<option value='" + index + "'>" + $(value).html() + "</option>");
						
						}
					
					);
					
				selected = Array()	
					
				$(".route option:selected")
					.each(
					
						function(index,value){
						
							selected.push($(value).val());
						
						}
					
					);	
					
				console.log(selected);	

				$(".route").html(get_select_menu_options());
				
				if(choices_children()!=1){
				
					$(".route")
						.each(
						
							function(index,value){
							
								$(value).prop("disabled", false);
							
							}
						
						)
				
				}
				
				$(".route")
					.each(
					
						function(index,value){

							selected_item = selected.shift();
						
							$(value)
								.children()
								.each(
								
									function(index,value){
									
										if($(value).val()==selected_item){
										
											$(value).prop("selected",true);
										
										}
									
									}
								
								);
						
						}
					
					);	
				
				
				
			}
		
			function edit_title(obj){
			
				$(obj)
					.toggleClass("hide",1);
				
				$(obj)
					.next()
					.toggleClass("hide",0)
					.toggleClass("show",1);
					
				$(obj)
					.next()
					.children()
					.first()
					.val($(obj).html());	
			
			}
			
			function edit_option_title(obj){
			
				console.log("here");
			
				$(obj)
					.toggleClass("hide",1);
				
				$(obj)
					.parent()
					.next()
					.toggleClass("hide",0)
					.toggleClass("show",1);
					
				$(obj)
					.parent()
					.next()
					.children()
					.first()
					.val($(obj).html());
			
			}			
					
			function save_text(obj){
						
				if($(obj).prev().val()!=""){
				
					$(obj)
						.parent()
						.parent()
						.children()
						.first()
						.next()
						.html($(obj).prev().val());
				}
				
				$(obj)
					.parent()
					.toggleClass("show",0)
					.toggleClass("hide",1);
					
				$(obj)
					.parent()
					.parent()
					.children()
					.first()	
					.next()
					.toggleClass("hide",0);	
					
				select_menus();	
			
			}
			
			function save_route_choice(obj){
		
				if($(obj).prev().prev().val()!=""){
				
					$(obj)
						.parent()
						.parent()
						.children()
						.first()
						.children()
						.first()
						.next()
						.html($(obj).prev().prev().val());
				}
				
				$(obj)
					.parent()
					.toggleClass("show",0)
					.toggleClass("hide",1);
					
				$(obj)
					.parent()
					.parent()
					.children()
					.first()	
					.toggleClass("show",1);	
			
			}
			
			function save_option(obj){
						
				if($(obj).prev().val()!=""){
				
					name = $(obj).prev().val();
					
				}else{
				
					name = "Option name"
				
				}
				
				$(obj)
					.parent()
					.toggleClass("show",0)
					.toggleClass("hide",1);
					
				$(obj)
					.parent()
					.parent()
					.children()
					.first()	
					.toggleClass("hide",0);	
					
				if(choices_children()!=1){
				
					state = "";
					get_children = true;
			
				}else{
				
					state = "disabled";
					get_children = false;
				
				}
					
				html = '<div><div class="option"><span onclick="javascript:left_choice(this)"><</span><p onclick="javascript:edit_option_title(this)">' + name + '</p><span onclick="javascript:right_choice(this)">></span><span onclick="javascript:delete_choice(this)">X</span></div><form class="option_form hide"><input type="text" value="" /><select class="route" ' + state + ' >';
				if(get_children){
					html += get_select_menu_options();
				}
				html += '</select><a onclick="javascript:save_route_choice(this)">Change</a></form></div>';	
					
				$(obj)
					.parent()
					.parent()
					.children()
					.last()
					.append(html);
			
			}
			
			function move_up(obj){
			
				$(obj)
					.parent()
					.parent()
					.insertBefore(obj.parentNode.parentNode.previousSibling);
				
				$(".prompt")
					.each(
					
						function(index,value){
						
							$(value).attr("id",index);
						
						}
					
					);
			
			}
			
			function move_down(obj){
			
				$(obj)
					.parent()
					.parent()
					.insertAfter(obj.parentNode.parentNode.nextSibling);
					
				$(".prompt")
					.each(
					
						function(index,value){
						
							$(value).attr("id",index);
						
						}
					
					);	
			
			}
			
			function left_choice(obj){
				
				$(obj)
					.parent()
					.parent()
					.insertBefore(obj.parentNode.parentNode.previousSibling);
			
			}
			
			function right_choice(obj){
				
				$(obj)
					.parent()
					.parent()
					.insertAfter(obj.parentNode.parentNode.nextSibling);
			
			}
			
			function add_decision(){
			
				$("#decisions")
					.children()
					.first()
					.clone()
					.appendTo("#decisions")
					.attr("id", parseInt(
								$("#decisions")
									.children()
									.first()
									.attr("id")) + $(".title").length);
				
				$("#decisions")
					.children()
					.last()
					.children()
					.first()
					.next()
					.html("New Choice");
	
				$("#decisions")
					.children()
					.last()
					.children()
					.last()
					.prev()
					.children()
					.last()
					.children()
					.last()
					.html("");
					
				select_menus();	
			
			}
	
		</script>
		<style>
		
			.hide{
			
				display:none;
			
			}
			
			.show{
			
				display:block;
			
			}
			
			html{
				font-family:verdana,arial;
			}
			
			#decisions div.prompt{
				float:left;
				position:relative;
				width:98%;
				clear:both;
				border:2px solid #000;
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
				height:auto;
				margin-bottom:20px;
			}
			
			#decisions div.prompt div{
				margin:20px;
			}
			
			#decisions div.prompt div div{
				margin:0px;
			}
			
			#decisions div.prompt textarea{
				width:100%;
				height:50px;
			}
			
			.choices{
				float:left;
				position:relative;
			}
			
			.choices div{
				float:left;
				position:relative;
			}
			
			.title{
				display:inline-block;
			}
			
			p.delete{
				clear:both;
			}
			
			#decisions div.prompt div.choices div{
				border:2px solid #000;
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
				padding:5px;
				font-size:75%;
				margin-right:5px;
			}
			
			#decisions div.prompt div.choices div div{
				border:none;
			}
			
			.option p{
				display:inline;
			}
			
			.option span{
				padding:5px;
				margin:5px;
				border:2px solid #000;
				-webkit-border-radius: 10px;
				-moz-border-radius: 10px;
				border-radius: 10px;
				background:#000;
				color:#fff;
				cursor:pointer;
				cursor:hand;
			}
			
			.prompt span.expand{
				font-size:200%;
				font-weight:bold;
			}
			
			.prompt p.title{
				font-size:200%;
				margin:0px;
				padding:0px;
			}
			
			.option_form input, .option_form select, .option_form a{
				display:block;
				margin:10px 0px;
				padding:10px 0px;
			}
			
			.option_form a{
				cursor:pointer;
				cursor:hand;
			}
			
			.prompt p.delete{
				margin:20px 0px;
				padding:20px;
			}
			
			.title, .choices_menu p, .delete, .expand{
				cursor:pointer;
				cursor:hand;
			}
			
			.decision{
				clear:both;
			}
			
			.decision a{
				cursor:pointer;
				cursor:hand;
			}	
			
			p.delete span{
			
				margin-right: 20px;
			
			}
		
		</style>
	</head>
	<body><?PHP
	
			if(isset($_POST['data_save'])){
			
				echo "<h2>Data Saved</h2>";
			
			}
	
		?>
		<div id="decisions">
			<?PHP
			
				if($data!=""){
				
					foreach($data as $index => $choice){
					
						?>
						<div class="prompt" id="<?PHP echo $choice->id; ?>">
							<span class="expand" onclick="javascript:expand_choice(this)">+</span>
							<p class="title" onclick="javascript:edit_title(this)"><?PHP echo $choice->name; ?></p>
							<form class="hide">
								<input type="text" value="" /><a onclick="javascript:save_text(this)">Change</a>
							</form>
							<div>
								<p>Text to appear for this choice</p>
								<textarea><?PHP echo $choice->html; ?></textarea>
								<p>
									<input class="color" type="text" value="<?PHP echo $choice->colour; ?>" />
								</p>
							</div>			
							<div>
								<h2>Options</h2>
								<div class="choices_menu">
									<p onclick="javascript:edit_title(this)">Click to add an option</p>
									<form class="hide">
										<input type="text" value="New option" /><a onclick="javascript:save_option(this)">Add</a>
									</form>
									<div class="choices"><?PHP
									
										$total = count($choice->choices);
									
										while($option = array_shift($choice->choices)){
										
											?><div>
												<div class="option">
													<span onclick="javascript:left_choice(this)"><</span>
													<p onclick="javascript:edit_option_title(this)"><?PHP echo $option[0]; ?></p>
													<span onclick="javascript:right_choice(this)">></span>
													<span onclick="javascript:delete_choice(this)">X</span>
												</div>
												<form class="option_form hide">
													<input type="text" value="" />
													<select class="route"><?PHP
													
														if($total!=1){
														
															echo "<option value='null'>Choose</option>";
															
															foreach($data as $optindex => $optvalue){
															
																echo "<option value='" . $optindex . "' "; 
																if($option[1]==$optindex){
																	echo " selected ";
																}
																echo ">" . $optindex . ". " . $option[0] . "</option>";
															
															}
														
														}
													
													?></select>
													<a onclick="javascript:save_route_choice(this)">Change</a>
												</form>
											</div><?PHP
										
										}
									
									?></div>
								</div>
							</div>
							<p class="delete" onclick="javascript:delete_prompt(this)">Delete</p>
						</div>
						<?PHP
						
					}
				
				}else{
			
			?>
			<div class="prompt" id="1">
				<span class="expand" onclick="javascript:expand_choice(this)">+</span>
				<p class="title" onclick="javascript:edit_title(this)">Choice</p>
				<form class="hide">
					<input type="text" value="" /><a onclick="javascript:save_text(this)">Change</a>
				</form>
				<div>
					<p>Text to appear for this choice</p>
					<textarea></textarea>
				</div>			
				<div>
					<h2>Options</h2>
					<div class="choices_menu">
						<p onclick="javascript:edit_title(this)">Click to add an option</p>
						<form class="hide">
							<input type="text" value="New option" /><a onclick="javascript:save_option(this)">Add</a>
						</form>
						<div class="choices"></div>
					</div>
				</div>
				<p class="delete"><span onclick="javascript:delete_prompt(this.parentNode)">Delete</span><span onclick="javascript:move_up(this)">Move Up</span><span onclick="javascript:move_down(this)">Move Down</span></p>
			</div>
			<?PHP
			
				}
				
			?>
		</div>
		<p class="decision"><a onclick="javascript:add_decision();">Add Decision</a></p>
		<p>
			<form onsubmit="return create_data();" action="preview.php?template_id=<?PHP echo $_GET['template_id']; ?>" method="post" target="_blank" enctype="application/json; charset=utf-8">
				<button>Preview</button>
				<input id="data_send" name="data_send" type="hidden" value="" />
			</form>
			<form onsubmit="return create_data();" action="edit.php?template_id=<?PHP echo $_GET['template_id']; ?>" method="post" enctype="application/json; charset=utf-8">
				<button>Save</button>
				<input id="data_save" name="data_save" type="hidden" value="" />
				<input id="publish" name="publish" type="hidden" value="publish" />
			</form>
			<form onsubmit="return create_data();" action="edit.php?template_id=<?PHP echo $_GET['template_id']; ?>" method="post" enctype="application/json; charset=utf-8">
				<button>Save</button>
				<input id="data_save" name="data_save" type="hidden" value="" />
			</form>
		</p>
	</body>
</html><?PHP

}