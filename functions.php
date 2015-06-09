<?php

date_default_timezone_set( "Asia/Istanbul" );
define("DB_HOSTNAME", 'localhost');
define("DB_DATABASE", 'aliburak');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", '');

function baglan()
{
	$db_server = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if(!$db_server) die("Cannot connect to MYSQL" . mysqli_error($db_server) );

	mysqli_select_db($db_server, DB_DATABASE) or die("Cannot connect to MYSQL" . mysqli_error($db_server) );

	mysqli_query($db_server,"SET NAMES UTF8");
	
	return $db_server;
}

function girisYapmis()
{
	if(isset($_COOKIE['uye_id']) && isset($_COOKIE['uye_adi']) && isset($_COOKIE['sifre']))
		return TRUE;
	return FALSE;
}

function getLastResultNum($baglanti)
{
	$query = "SELECT FOUND_ROWS()";
	$result = mysqli_query($baglanti,$query);
	$result = mysqli_fetch_array($result, MYSQLI_NUM);
	
	return $result[0];
}

function string_duzelt($var)
{
	$bag = baglan();
	
	if (get_magic_quotes_gpc())
		$var = stripslashes($var);
	$var = mysqli_real_escape_string($bag, $var);
	$var = htmlspecialchars($var);
	
	return $var;
}

function nl2br2($string) 
{ 
	return str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
} 

function nl2p($string)
{
	return preg_replace("/[\r\n]+/", "</p><p>", $string);
}

function metin_goster($str)
{
	$str = nl2br($str);
	
	return $str;
}

function rapor($basari, $rapor, $header_url = "index.php?action=anaSayfa")
{
	session_start();
	$veriler = array();
	
	$_SESSION['basari'] = $basari;
	$_SESSION['rapor'] = $rapor;
	$_SESSION['header_url'] = $header_url;
	
	header( 'Location: rapor.php' );
	exit;
}

function sureIndexBul($sure_adi)
{
	$sureler = tum_sureleri_ver();
	foreach($sureler as $index => $sure)
	{
		if($sure == $sure_adi)
			return $index;
	}
	return -1;
}


function tum_sureleri_ver()
{
	$sureler = array("Fâtiha", "Bakara", "Âl-i İmrân", "Nisâ", "Mâide", "Enâm", "Arâf", "Enfâl", "Tevbe", "Yunus", "Hûd", "Yusuf", "Rad", "İbrahim", 
"Hicr", "Nahl", "İsrâ", "Kehf", "Meryem", "Tâ-Hâ", "Enbiyâ", "Hac", "Müminûn", "Nûr", "Furkan", "Şuarâ", "Neml", "Kasas", "Ankebût", 
"Rûm", "Lokman", "Secde", "Ahzâb", "Sebe", "Fâtır", "Yâsin", "Sâffât", "Sâd", "Zümer", "Mümin", "Fussilet", "Şûrâ", "Zuhruf", "Duhân", 
"Câsiye", "Ahkaf", "Muhammed", "Fetih", "Hucurât", "Kaf", "Zâriyât", "Tûr", "Necm", "Kamer", "Rahmân", "Vâkıa", "Hadid", "Mücâdele", "Haşr", 
"Mümtehine", "Saf", "Cuma", "Münâfikûn", "Teğabün", "Talâk", "Tahrim", "Mülk", "Kalem", "Hâkka", "Meâric", "Nuh", "Cin", "Müzzemmil", "Müddessir", 
"Kıyamet", "İnsan", "Mürselât", "Nebe", "Nâziât", "Abese", "Tekvir", "İnfitâr", "Mutaffifin", "İnşikak", "Bürûc", "Târık", "Alâ", "Gâşiye", 
"Fecr", "Beled", "Şems", "Leyl", "Duhâ", "İnşirâh", "Tin", "Alak", "Kadir", "Beyyine", "Zilzâl", "Âdiyât", "Kâria", "Tekâsür", "Asr", 
"Hümeze", "Fil", "Kureyş", "Mâûn", "Kevser", "Kâfirûn", "Nasr", "Tebbet", "İhlâs", "Felâk", "Nâs");
	
	return $sureler;
/*"Fâtiha(1/7)", "Bakara(2/286)", "Âl-i İmrân(3/200)", "Nisâ(4/176)", 
"Mâide(5/120)", "En’âm(6/165)", "A’râf(7/206)", "Enfâl(8/75)", "Tevbe(9/129)", "Yûnus(10/109)", "Hûd(11/123)", "Yûsuf(12/111)", "Ra’d(13/43)", "İbrahim(14/52)", "Hicr(15/99)","Nahl(16/128)", "İsrâ(17/111)", "Kehf(18/110)", "Meryem(19/98)", "Tâ-Hâ(20/135)", 
"Enbiyâ(21/112)", "Hac(22/78)", "Mü’minûn(23/118)", "Nûr(24/64)", "Furkân(25/77)", "Şu’arâ(26/227)", "Neml(27/93)", 
"Kasas(28/88)", "Ankebût(29/69)", "Rûm(30/60)", "Lokman(31/34)", "Secde(32/30)", "Ahzâb(33/73)", "Sebe’(34/54)", 
"Fâtır(35/45)", "Yâsîn(36/83)", "Sâffât(37/182)", "Sâd(38/88)", "Zümer(39/75)", "Mü’min(40/85)", "Fussilet(41/54)", 
"Şûrâ(42/53)", "Zuhruf(43/89)", "Duhân(44/59)", "Câsiye(45/37)", "Ahkâf(46/35)", "Muhammed(47/38)", "Fetih(48/29)", 
"Hucurât(49/18)", "Kâf(50/45)", "Zâriyât(51/60)", "Tûr(52/49)", "Necm(53/62)", "Kamer(54/55)", "Rahmân(55/78)", 
"Vâkı’a(56/96)", "Hadîd(57/29)", "Mücâdele(58/22)", "Haşr(59/24)", "Mümtehine(60/13)", "Saff(61/14)", "Cum’a(62/11)", 
"Münâfikûn(63/11)", "Teğâbun(64/18)", "Talâk(65/12)", "Tahrîm(66/12)", "Mülk(67/30)", "Kalem(68/52)", "Hâkka(69/52)", 
"Me’âric(70/44)", "Nûh(71/28)", "Cin(72/28)", "Müzzemmil(73/20)", "Müddessir(74/56)", "Kıyâme(75/40)", "İnsan(76/31)", "Mürselât(77/50)", "Nebe’(78/40)", "Nâzi’ât(79/46)", "Abese(80/42)", "Tekvîr(81/29)", "İnfitâr(82/19)", "Mutaffifîn(83/36)", 
"İnşikâk(84/25)", "Bürûc(85/22)", "Târık(86/17)", "A’lâ(87/19)", "Gâşiye(88/26)", "Fecr(89/30)", "Beled(90/20)", 
"Şems(91/15)", "Leyl(92/21)", "Duhâ(93/11)", "İnşirâh(94/8)", "Tîn(95/8)", "Alak(96/19)", "Kadr(97/5)", "Beyyine(98/8)", 
"Zilzâl(99/8)", "Âdiyât(100/11)", "Kâri’a(101/11)", "Tekâsür(102/8)", "Asr(103/3)", "Hümeze(104/9)", "Fil(105/5)", 
"Kureyş(106/4)", "Mâ’ûn(107/7)", "Kevser(108/3)", "Kâfirûn(109/6)", "Nasr(110/3)", "Tebbet(111/5)", "İhlâs(112/4)", 
"Felâk(113/5)", "Nâs(114/6)"*/
}

function tum_ayet_sayilari_ver()
{
	$arr = array(7, 286, 200, 176, 120, 165, 206, 75, 129, 109, 123, 111, 43, 52, 99, 128, 111, 110, 98, 135, 112, 78, 118, 64, 77, 227, 93, 88, 69, 60, 34, 30, 73, 54, 45, 83, 182, 88, 75, 85, 54, 53, 89, 59, 37, 35, 38, 29, 18, 45, 60, 49, 62, 55, 78, 96, 29, 22, 24, 13, 14, 11, 11, 18, 12, 12, 30, 52, 52, 44, 28, 28, 20, 56, 40, 31, 50, 40, 46, 42, 29, 19, 36, 25, 22, 17, 19, 26, 30, 20, 15, 21, 11, 8, 8, 19, 5, 8, 8, 11, 11, 8, 3, 9, 5, 4, 7, 3, 6, 3, 5, 4, 5, 6);
	return $arr;
}

function tarih_ver($time)
{
	$turkce_aylar = array("Ocak", "Şubat", "Mart", "Nisan",
					"Mayıs", "Haziran", "Temmuz", "Ağustos",
					"Eylül", "Ekim", "Kasım", "Aralık");
	$ay = date("n", $time);
	$ay = $turkce_aylar[$ay - 1];
	
	$tarih = date("d", $time) . " " . $ay . date(" Y", $time) . " Saat " . date("H:i", $time);
	
	return $tarih;
}

?>
