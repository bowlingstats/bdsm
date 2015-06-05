<?
function drawPins($name, $rack, $pins = array(0,0,0,0,0,0,0,0,0,0), $mode = 0){
	//mode 1 means edit
	print "\n\t\t\t\t\t\t<table class=\"pins\">\n\t\t\t\t\t\t\t<tr>\n";
	//back row
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 7, $pins[6], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 8, $pins[7], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 9, $pins[8], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 10, $pins[9], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//space row
	print "\t\t\t\t\t\t\t<tr>\n";
	print "\t\t\t\t\t\t\t\t<td colspan=\"7\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//3rd row
	print "\t\t\t\t\t\t\t<tr>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 4, $pins[3], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 5, $pins[4], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 6, $pins[5], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//space row
	print "\t\t\t\t\t\t\t<tr>\n";
	print "\t\t\t\t\t\t\t\t<td colspan=\"7\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//2nd row
	print "\t\t\t\t\t\t\t<tr>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 2, $pins[1], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 3, $pins[2], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//space row
	print "\t\t\t\t\t\t\t<tr>\n";
	print "\t\t\t\t\t\t\t\t<td colspan=\"7\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	print "\t\t\t\t\t\t\t<tr>\n";
	//if we're entering scores we want the "X" displayed.
	if($mode) print "\t\t\t\t\t\t\t\t<td class=\"pin\"><img src=\"images/strike.png\" onClick=\"$name.strikePin($rack)\"></td>\n";
	else print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack, 1, $pins[0], $mode)."</td>\n";
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	//if we're entering scores we want the miss("-") displayed
	if($mode) print "\t\t\t\t\t\t\t\t<td class=\"pin\"><img src=\"images/miss.png\" onClick=\"$name.missPin($rack)\"></td>\n";
	else print "\t\t\t\t\t\t\t\t<td class=\"pin\">".displayPin($name, $rack)."</td>\n";//spacer
	print "\t\t\t\t\t\t\t</tr>\n";
	
	//now add all the hidden form elements used when entering pins.
	//use a for loop to make hidden fields for the pinfall
	if($mode){
		foreach($pins as $key => $value){
			$pin = $key + 1;
			print "\t\t\t\t\t\t\t<input type=\"hidden\" id=\"".$name."f".$rack."p".$pin."\" name=\"".$name."f".$rack."p".$pin."\" value=\"$value\">\n";
		}
	}
	print "\t\t\t\t\t\t</table>\n";
}

function displayPin($name, $rack, $pin = "", $value = "spacer", $mode = 0){
	$output = "<img ";
	if($mode && "$value" != "spacer") $output .= "id=\"".$name."f".$rack."p".$pin."image\" ";
	
	$output .= "src=\"";
	if("$value" == "0") $output .= "images/down.png";
	elseif("$value" == "1") $output .= "images/ball1.png";
	elseif("$value" == "2") $output .= "images/ball2.png";
	elseif($value == "spacer") $output .= "images/spacer.png";
	$output .= "\" ";
	//check for mode and add javascript stuff if needed
	//0 is just display 1 is interact
	if($mode){
		$output .= " onClick=\"$name.incrementPin($rack, $pin, this)\" ";
	}
	$output .= ">";
	
	return($output);
}
?>
