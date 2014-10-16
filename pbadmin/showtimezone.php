<?php
	print "\n".'<select id="timezone" name="timezone">';
	print '<optgroup label="Africa">';
	$timezone_identifiers = DateTimeZone::listIdentifiers();
	foreach ($timezone_identifiers as $value) {
		if (preg_match('/^(Africa|America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $value)) {
			$ex = explode("/", $value);
			if (!empty($continent) && $continent != $ex[0]){
				if (!empty($continent) &&$continent != " ") {
					print '</optgroup>';
				}
				print '<optgroup label="'.$ex[0].'">';
		    }
			$city = $ex[1];
			$continent = $ex[0];
			if (!empty($ex[2])) {
				$ex[2] = strtr($ex[2],'_',' ');
				$city = $city.'/'.$ex[2];
			}
			print '<option value="'.$value.'">'.$city.'</option>';	    		
		}
	}
	print '</optgroup>';
	print "</select>\n";
?>
