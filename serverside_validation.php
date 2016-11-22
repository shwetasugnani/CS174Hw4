<?php

	include_once "createDB.php";

	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		if (validate()){
			initializeDB();
			hashStoreToDatabase();
    		
		}


	}

	function validate(){

		$dataArea = $_POST["dataArea"];
		
		if (empty($dataArea)){
			echo "No data found. Please input data with specified format. You will be redirected in 3 seconds.";
			return false;
			header('Refresh: 5; URL=index.html');
			return false;
		}
		else if (isset($dataArea)){
			$lines = explode("\n", $dataArea);
			for($i = 0; $i < count($lines); $i++){
				$currentLine = $lines[$i];
				$currentLineItems = explode(",", $currentLine);

				$label = array_shift($currentLineItems);

				if($label == ""){
					echo "Invalid! You will be redirected in 3 seconds.";
					header('Refresh: 5; URL=index.html');
					return false;
					
				}

				if (count($currentLineItems) > 5){
					$lineIndex = $i + 1;
					echo "Sorry, at most 5 coordinates allowed, " . count($currentLineItems) . " coordinates given at line " . $lineIndex . ". You will be redirected.";
					header('Refresh: 5; URL=index.html');
					return false;
				}

				for($j = 0; $j < count($currentLineItems); $j++){

					$lineIndex = $i + 1;
					
					if (preg_match("/[a-z]/i", $currentLineItems[$j])){
	
						echo "Sorry, only number types allowed for coordinates. " . $currentLineItems[$j] . " on line " . $lineIndex . " is not a number";

						header('Refresh: 5; URL=index.html');
						return false;
		    		}
				}

			}
			return true;
			
		}


	}

	function validateData($dataArea){

		
		
		if (empty($dataArea)){
			//echo "No data found. Please input data with specified format. You will be redirected in 3 seconds.";
			return false;
			header('Refresh: 5; URL=index.html');
			return false;
		}
		else if (isset($dataArea)){
			$lines = explode("\n", $dataArea);
			for($i = 0; $i < count($lines); $i++){
				$currentLine = $lines[$i];
				$currentLineItems = explode(",", $currentLine);

				$label = array_shift($currentLineItems);

				if($label == ""){
					//echo "Invalid! You will be redirected in 3 seconds.";
					header('Refresh: 5; URL=index.html');
					return false;
					
				}

				if (count($currentLineItems) > 5){
					$lineIndex = $i + 1;
					//echo "Sorry, at most 5 coordinates allowed, " . count($currentLineItems) . " coordinates given at line " . $lineIndex . ". You will be redirected.";
					//header('Refresh: 5; URL=index.html');
					return false;
				}

				for($j = 0; $j < count($currentLineItems); $j++){

					$lineIndex = $i + 1;
					
					if (preg_match("/[a-z]/i", $currentLineItems[$j])){
	
						//echo "Sorry, only number types allowed for coordinates. " . $currentLineItems[$j] . " on line " . $lineIndex . " is not a number";

						//header('Refresh: 5; URL=index.html');
						return false;
		    		}
				}

			}
			return true;
			
		}


	}

	function hashStoreToDatabase(){

			$config = require("config.php");
    		$con = mysqli_connect($config['host'], $config['username'], $config['password'], "chartData");

    		$chartTitle = $_POST["chartTitle"];
    		$dataArea = $_POST["dataArea"];
    	

    		$hashed = hash('md5', $dataArea);
    		$sql = "INSERT INTO Data (`hashedData`, `title`, `data`) VALUES ('$hashed', '$chartTitle', '$dataArea')";
            $result = mysqli_query($con, $sql);
            $url = $config['url'];
           	header("Location: $url?c=chart&a=show&arg1=LineGraph&arg2=$hashed");

	}

?>