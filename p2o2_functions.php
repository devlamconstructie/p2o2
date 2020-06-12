<?php

/*
* HOW TO USE (with code snippets plugin) 
* put in code snippet and activate.
* in oxygen, place text or header element inside a repeater, 
* Use Insert Data -> PHP Function Return value
* enter the function name, example: p2o2_string
* enter the function parameters, separated by comma, example: my_relationship_field, user_nicename, 0, true
* planned: use -1 to iterate through all the related rows using a ( ... , ... and ...)  format
* add checks for linked taxonomies; although those would not be necessary since they are already handled fine by O2.
*/ 
function p2o2_string ($fieldname , $rel_fieldname='post_title' , $index=0, $linked=false){
	$pulse = get_post_meta(get_the_ID(),$fieldname); //fetch field from current post containing the relationship
	if($pulse){ //check if we found the field, else return error msg.
		if (is_array($pulse)){ //make sure this is actually an array
			$pea = $pulse[$index][$rel_fieldname]; // the release candidate holds the field we wanted to grab from the related pod
			if ($pea && is_string($pea)) {
				if(!$linked){
					return $pea;
				}else {
					$classname = "p2o2-" . get_post_type() . "-" . $rel_fieldname;
					$id = "p2o2-" . $rel_fieldname . "-" . $index; 	
					if (array_key_exists ('post_title', $pulse[$index])) { //it's a post 
						$href=get_permalink($pulse[$index]['ID']);
					}elseif (array_key_exists ('user_nicename', $pulse[$index])){ //it's a user
					 	$href=get_author_posts_url($pulse[$index]['ID']);
					} // i should add checks for taxonomies.
					return "<a class='". $classname . "' id='" . $id . "' href='" . $href ."'>" . $pea . "</a>";
				}
			} else { // error handling
				return "this function was not meant to deal with fields with the content type: " . gettype ( $pea ); 
			}	
		} elseif (is_string($pulse)) { //error handling.
			return "this is not a relationship field - use the custom field dropdown instead."; // in case the field is not a relationship field.
		}
	}else{ // return error msg: 
		return "no data found, check syntax. p2o2_string(fieldname[str required], related-fieldname[str], index[int], linked[bool] )";		
	}
}

?>
