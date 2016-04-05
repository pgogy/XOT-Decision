<?PHP     

/**
* 
* new template page, allows the site to make a new xerte module
*
* @author Patrick Lockley
* @version 1.0
* @copyright Copyright (c) 2008,2009 University of Nottingham
* @package
*/


$temp_dir_path="";
$temp_new_path="";

// taken from php.net/manual/en/function.copy.php

define('DS', DIRECTORY_SEPARATOR); // I always use this short form in my code.

/**
 * 
 * Function sort out paramaters
 * This function creates folders needed when duplicating a template
 * @param number $folder_name_id - the id of this template
 * @param number $tutorial_id_from_post - the parent template name for the new tutorial
 * @version 1.0
 * @author Patrick Lockley
 */


function create_new_template($folder_name_id,$parent_template_name){

	global $xerte_toolkits_site;

	mkdir($xerte_toolkits_site->root_file_path . $xerte_toolkits_site->users_file_area_short . $folder_name_id . "-" . $_SESSION['toolkits_logon_username'] . "-" . $parent_template_name . "/");

}
