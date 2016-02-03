<?php
require 'credentials.php';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    //return;
    }
    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT distinct transporter from thalilist where Active=1"); 
    $stmt->execute();

    // set the resulting array to associative
    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt = $stmt->fetchAll(); 
    }
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
$checkbox_html = "";
var_dump($stmt);
for($i=0; $i<count($stmt); $i++)
{
	$val = $stmt[$i][0];
	$checkbox_html = $checkbox_html."<label><input type='checkbox' name = 'transporters[]' value='$val'/>$val</label>";	
}
$checkbox_html.="<br>\n";
?>
<!DOCTYPE html>
<html>
<head>
	<title> checkbox populating </title>
	<script src = "https://code.jquery.com/jquery-2.2.0.min.js"></script>
	<script type='text/javascript'>
	var transporters = null;
	$(document).ready(function(){
		$.getScript("checkbox.js");


		$('#checkedTransporters').html("hi");
	});

	</script>
</head>
<body>
<?php
echo $checkbox_html;
?>
<input type="button" id="getCheckedTransporters" value = 'get transporters'/>
<p id='checkedTransporters'>
</p>
</body>