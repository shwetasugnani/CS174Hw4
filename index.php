<!DOCTYPE html>
<?php include "serverside_validation.php"; ?>
<html>
<head>
	<title>PasteChart</title>
	</head>
<body>
	<h1>PasteChart</h1>
	<p>Share your data in charts!</p>
	<form name="chartform" onsubmit="return validate();" action="serverside_validation.php" method="POST">
		<label>Chart Title:</label> 
		<input type="text" id="chartTitle" name="chartTitle">
		<br></br>
	<textarea id="dataArea" name="dataArea" rows="50" cols="80" placeholder="Label, coordinate 1, coordinate 2, ... coordinate 5"></textarea>
	<br></br>
		<input type="submit" value="Share">
	</form>
	<script src="clientside_validation.js"></script>

</body>
</html>