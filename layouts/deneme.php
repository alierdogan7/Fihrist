<?php
require_once("functions.php");

$bag = baglan();



if(isset($_POST['submit']))
{


	$var = $_POST['var'];
	
	//$var = "Deneme.Deneme.Deneme.Deneme. Deneme.Deneme.\r\n\r\nDeneme.Deneme.\r\n\r\n\r\nDeneme.";
	echo "POST'tan gelen var " . $var . "<br /><br />";
	//echo "Önceden yazdığın var " . $var . "<br /><br />";
	
	if (get_magic_quotes_gpc())
	{
		$var = stripslashes($var);
		echo "1.stripslashes " . $var . "<br /><br />";
	}

	$var = mysql_real_escape_string($var);
	echo "mysql_real_escape_string " . $var . "<br /><br />";
	
	$conn = new PDO( "mysql:host=localhost;dbname=test", DB_USERNAME, DB_PASSWORD );
	$sql = "INSERT INTO deneme(id, var) VALUES(NULL, :var)";
	$st = $conn->prepare ( $sql );
	
	$st->bindValue( ":var", $var, PDO::PARAM_STR );
	$st->execute();
	$id = $conn->lastInsertId();
	
	$query = "SELECT * FROM deneme WHERE id = " . $id . "";
	$result = mysqli_query($bag, $query);
	
	if(!$result) die("error at selecting: " . mysqli_error($bag));
	
	$data = mysqli_fetch_array($result);
	$var = $data['var'];
	
	if (get_magic_quotes_gpc())
	{
		$var = stripslashes($var);
		echo "1.stripslashes " . $var . "<br /><br />";
	}
	
	echo "selected from database ID={$id}: {$var}<br /><br />";
	
	$var = stripslashes(nl2br($var));
	echo "stripslashes+nl2br " . $var . "<br /><br />";
	
	$var = preg_replace("/[\r\n]+/", "</p><p>", $var);
	echo "preg_replace after selecting: " . $var . "<br /><br />";
	
	
	
	//$var = mysql_real_escape_string($var);
	//echo "2. mysql_real_escape_string " . $var . "<br /><br />";
	
	//$var = strip_tags($var);
	//echo "strip tags " . $var . "<br /><br />";

	//$var = stripslashes($var);
	//echo "2.strip slashes " . $var . "<br /><br />";

	//$var = nl2br($var);
	//echo "nl2br " . nl2br($var) . "<br /><br />";
	
	//$var = nl2br2($var);
	//echo "nl2br2 " . $var . "<br /><br />";
	
	
	
	echo nl2br("foo isn't\n bar");

	
}


//$var = "Deneme.Deneme.Deneme.Deneme. Deneme.Deneme.\\r\\n\\r\\nDeneme.Deneme.\\r\\n\\r\\n\\r\\nDeneme.";


?>
<form action="deneme.php" method="POST">
<textarea name="var" cols="50" rows="5"></textarea>
<input type="submit" name="submit" /></form>

