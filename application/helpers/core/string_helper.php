<?php
	function append_chars($string,$position = "right",$count = 0, $char = "")
	{
	    $rep_count = $count - strlen($string);
	    $append_string = "";
	    for ($i=0; $i < $rep_count ; $i++) {
	        $append_string .= $char;
	    }
	    if ($position == 'right')
	        return $string.$append_string;
	    else
	        return $append_string.$string;
	}
	function align_center($string,$count,$char = " ")
	{
	    $rep_count = $count - strlen($string);
	    for ($i=0; $i < $rep_count; $i++) {
	        if ($i % 2 == 0) {
	            $string = $char.$string;
	        } else {
	            $string = $string.$char;
	        }
	    }
	    return $string;
	}
	function substrwords($text,$maxchars,$end='...')
	{
		if (strlen($text) > $maxchars || $text == '') {
			$words = preg_split('/\s/', $text);
			$output = "";
			$counter = 0;
			do {
				$strLen = 0;
				if(isset($words[$counter]))
					$strLen = strlen($words[$counter]);
				$length = strlen($output) + $strLen;
				if ($length > $maxchars)
					break;
				else {
					$wr = '';
					if(isset($words[$counter]))
						$wr = $words[$counter];
					$output .= " ".$wr;
					$counter++;
				}
			} while (true);
			$output .= $end;
		} else {
			$output = $text;
		}
		return trim($output);
	}