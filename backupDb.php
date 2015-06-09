 <?php

require_once("functions.php");
require_once("classes.php");

/*
for restoring with a back up file, use the following query
LOAD DATA INFILE 'ayet_09-06-2015.sql' INTO TABLE ayet CHARACTER SET UTF8;
*/

$bag = baglan();
$rapor = "";
$tableNames = [ 'ayet', 'ayet_tag', 'gunun_ayeti', 'tag', 'yoneticiler' ];
//$fileNames = [ 'ayet.sql', 'ayet_tag.sql', 'gunun_ayeti.sql', 'tag.sql', 'yoneticiler.sql' ];

$dateStamp = date('d-m-Y_H-i');
foreach( $tableNames as $table)
{
	$query = "SELECT * INTO OUTFILE '$table" . "_" . $dateStamp . ".sql' FROM $table";
	$result = mysqli_query($bag, $query);

	if($result)
		$rapor .= "Table '$table' is backed up into '$table" . "_" . $dateStamp . ".sql' successfully<br/><br/>";
	else
		$rapor .= "Table '$table' could NOT be backed up into '$table" . "_" . $dateStamp . ".sql' !<br/><br/>";

}

rapor(TRUE, $rapor);

 ?>