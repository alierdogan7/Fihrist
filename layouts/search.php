<?php
require_once("functions.php");
require_once("classes.php");

if(isset($_POST['submit']))
{
	$bag = baglan();
	
	$str = string_duzelt($_POST['search']);
	$words_array = explode(' ', $str);
	$count = count($words_array);
	
	$query = "SELECT * FROM ayet WHERE";
	foreach($words_array as $index => $word)
	{
		$query .= " ayet_meal LIKE '%$word%'";
		if( $count != ($index+1) )
			$query .= " AND";
	}
	
	echo $query;
	
	$result = mysqli_query($bag, $query);
	if(!$result) rapor(FALSE, "hata");
	
	$i = 0;
	
	while ( $row = mysqli_fetch_array($result) )
	{
		echo ++$i . " inside loop";
		echo $row['sure_adi'] . " " . $row['sure_no'] . " => " . $row['ayet_meal'] . "<br />";
	}
}



?>

<form action="search.php" method="POST">
<input type="text" name="search" />
<input type="submit" name="submit" /></form>