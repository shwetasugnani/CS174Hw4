<?php
	
 //{"Jan":[7,5,4], "Feb":[20,12,14],"Mar":[4,5,6] ,"Dec":[5,7,8]}, {"title":"Test Chart - Month v Value","type":"LineGraph"});
	function formatData($Data){

		$formattedString="{";

		$lines = explode("\n", $Data);
		for($i = 0; $i < count($lines); $i++){
			$currentLine = $lines[$i];
			$currentLineItems = explode(",", $currentLine);
			$label = array_shift($currentLineItems);
			$list = implode(',', $currentLineItems);	
			$insertString = '"'. $label . '":['. $list .'], ';
			$formattedString .= $insertString;
		}

		$formattedString = $formattedString . "}";
		return $formattedString;
	}



	function formatDataJSON($Data){

		$myArray = array();


		$lines = explode("\n", $Data);
		for($i = 0; $i < count($lines); $i++){
			$currentLine = $lines[$i];
			$currentLineItems = explode(",", $currentLine);
			$label = array_shift($currentLineItems);
			
			for($j=0; $j < count($currentLineItems); $j++){
				$currentLineItems[$j] = str_replace("\r", '', $currentLineItems[$j]);
			}
		
			$myArray[$label] = $currentLineItems;
		}

		

		$toJSON = json_encode($myArray, JSON_PRETTY_PRINT);
    	return $toJSON;

	}
	

?>