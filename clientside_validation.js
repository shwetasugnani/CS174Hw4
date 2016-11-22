// alert("hi");
function validate() {
   	var textInput = document.getElementById('dataArea').value;
   
    if (textInput == "") {
        alert("You must enter data before you share");
        return false;
    } else {
    	var lines = textInput.split('\n');
		for(var i = 0; i < lines.length; i++){
		    var currentLine = lines[i];
		    var currentLineItems = currentLine.split(',');

		    var label = currentLineItems[0]; //get label (x-axis)

		    //check if label is given
		    if (label == ""){
		    	alert("New line has no label! Please enter data in this format: Label, coordinate 1, coordinate 2, ... coordinate 5");
		    	return false;
		    }

		    currentLineItems.splice(0, 1); //remove the label from the array so you can check how many coordinates
		  
		    if (currentLineItems.length > 5){
		    	var lineIndex = i+1;
		    	 alert("Sorry, at most 5 coordinates allowed, " + currentLineItems.length + " coordinates given at line " + lineIndex + ".");
		    	 return false;
		    }
		   
		    for(var j = 0; j < currentLineItems.length; j++){
		    	var lineIndex = i+1;
		    	var curr = currentLineItems[j];
		  
		    	if (curr.match(/[a-z]/i)){
		    		alert("Sorry, only number types allowed for coordinates. " + curr + " on line " + lineIndex + " is not a number.");
		    	 	return false;
		    	}
		    }

		}
		return true;
    }
}
