<?php

// require the include files that has the info needed for the site
require_once("./config.php");

?>

<!-- show the body of the main page here -->
<html>
<body>
	<div class="container center">
		<div id="header">
			<div class="row col-12">
				<h1>My URL Shortener for Payroc</h1>
			</div>
		</div>
<?php
	if (isset($_GET['error']))
	{
		echo "<p class='text-danger'><strong>";
		if ($_GET['error'] == 'connection')
		{
			echo "Database Connection Error, please contact developer";
		}
		if ($_GET['error'] == 'not_exist')
		{
			echo "The short URL entered does not exist in the system";
		}
		if ($_GET['error'] == 'dbase_err')
		{
			echo "There was a database error, please try again";
		}
		
		echo "</strong></p>";
	}
?>
		<div id="content">
			<div class="row col-12">Enter the URL, Click "Get Shortened URL" and the shortened URL will appear in the box below<br>&nbsp;</div>

			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-4 center"><input type="text" class="form-control" name="url" id="url" placeholder="https://" required></div>
				<div class="col-md-2"><input type="submit" class="btn btn-primary" name="shorten" value="Get Shortened URL" onClick="SendForData();"></div>
				<div class="col-md-3"></div>
			</div>
			<p id="projectIDSelectError" class="red"</p>
			<div class="row">
				<div class="col-md-12"><br>&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-2"><strong><p class="row text-right">Shortened URL:</p></strong></div>
				<div class="col-md-4 center"><input type="text" class="form-control" name="ret_val" id="ret_val"></div>
				<div class="col-md-2 left"><p class="row text-left"><button class="btn btn-primary" id="copy" onClick="copyData();">Click to Copy URL</button></p></div>
				<div class="col-md-2"></div>
			</div>
		</div>



</body>
</html>
<style>
.center{
	text-align: center;
}
.red{
	color: red;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
function SendForData()
{
	if($('#url').val() != "")
	{
		var get_url = "submit.php?url=" + $('#url').val();
		$.ajax({
			url:  get_url,
			type:  "GET",
			dataType: "json",
			success: function (data) {
				$('#ret_val').val(data);
    		}
		});
	}
	else
	{
		$("#url").attr("placeholder", "You must enter a URL to shorten").val("").focus().blur();
		$("#projectIDSelectError").html("You must enter a URL to shorten").addClass("error-msg");
		return false;
	}
}

function copyData()
{
	//var copyText = ;
    copyToClipboard($('#ret_val').val());
}

function copyToClipboard(text) {
    var sampleTextarea = document.createElement("textarea");
    document.body.appendChild(sampleTextarea);
    sampleTextarea.value = text; //save main text in it
    sampleTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(sampleTextarea);
}
</script>


