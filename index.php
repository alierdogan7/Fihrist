<?php
 
require_once("functions.php");
require_once("classes.php");

$action = isset($_GET['action']) ?  string_duzelt($_GET['action']) :  "";
$giris_yapmis = girisYapmis();

switch($action)
{
	case 'anaSayfa':
		anaSayfa();
		break;
	case 'giris':
		if(!$giris_yapmis) giris();
		else rapor(FALSE, "Zaten giriş yapılmış!");
		break;
	case 'cikis':
		if($giris_yapmis) cikis();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	default:
		anaSayfa();
}

function anaSayfa()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır

	$veriler = array();
	
	$veriler['tagler'] = Tag::getList("", "tag_adi ASC");
	
	$veriler['son_5_ayet'] = Ayet::getList("LIMIT 5", "tarih DESC");
	$veriler['son_5_ayet_tagler'] = array();
	
	foreach($veriler['son_5_ayet'] as $ayet)
	{
		$veriler['son_5_ayet_tagler'][] = AyetTag::getTagListByAyet($ayet->ayet_id, TRUE, "", "tag_adi ASC");
	}
	
	$veriler['gunun_ayeti'] = Ayet::getGununAyeti();
	$veriler['gunun_ayeti_tagler'] = AyetTag::getTagListByAyet($veriler['gunun_ayeti']->ayet_id, TRUE, "", "tag_adi ASC");
	
	$veriler['baslik'] = "Ayetlerim - Ana Sayfa";
	require("templates/anasayfa.php");
}

function giris()
{
	$veriler = array();

	if(isset($_POST['giris']) )
	{
		if($_POST['uye_adi'] != "" && $_POST['sifre'] != "" )
		{
			$uyeBilgileri = array();
			$tmp_uye_adi = string_duzelt($_POST['uye_adi']);
			$tmp_sifre = md5(string_duzelt($_POST['sifre']));

			$query = "SELECT * FROM yoneticiler WHERE uye_adi = '$tmp_uye_adi' AND sifre = '$tmp_sifre'";
			
			$bag = baglan();
			$result = mysqli_query($bag, $query);
			if(!$result) rapor(FALSE, "mysql hatası");

			$rows = mysqli_num_rows($result);
			if($rows == 1) {
				$row = mysqli_fetch_array($result);
				$uyeBilgileri[0] = $row[0]; //uye_id
				$uyeBilgileri[1] = $row[1]; //uye_adi
				$uyeBilgileri[2] = $row[2]; //sifre
			}
			
			if( !empty($uyeBilgileri) )
			{
				if( isset($_POST['hatirla']) && $_POST['hatirla'] == 1 )
				{
					setcookie('uye_id', $uyeBilgileri[0], time() + 15 * 24 * 60 * 60, '/' );
					setcookie('uye_adi', $uyeBilgileri[1], time() + 15 * 24 * 60 * 60, '/' );
					setcookie('sifre', $uyeBilgileri[2], time() + 15 * 24 * 60 * 60, '/' );
				}
				else
				{	
					setcookie('uye_id', $uyeBilgileri[0], time() + 60 * 60, '/' );
					setcookie('uye_adi', $uyeBilgileri[1], time() + 60 * 60, '/' );
					setcookie('sifre', $uyeBilgileri[2], time() + 60 * 60, '/' );
				}	
				
				rapor(TRUE, "Başarıyla giriş yaptınız, {$uyeBilgileri[1]}!");
			}
			else
			{
				$veriler['mesaj'] = "Üye adınız veya şifreniz yanlış lütfen tekrar deneyiniz.";
			}
		}
		else
		{
			$veriler['mesaj'] = "Alanlardan birini eksik doldurunuz. Tekrar deneyiniz!";
		}
	}
	
	$veriler['baslik'] = "Ayetlerim - Yönetici girişi";
	require("templates/giris.php");
}

function cikis()
{
	session_start();
	$_SESSION = array();
	
	$uye_adi = $_COOKIE['uye_adi'];
	$uye_id = $_COOKIE['uye_id'];
	$sifre = $_COOKIE['sifre'];
	
	if( session_id() != "" || isset($_COOKIE['uye_adi']) )
	{
		setcookie('uye_adi', '$uye_adi', time() - 2592000, '/');
		setcookie('sifre', '$sifre', time() - 2592000, '/');
		setcookie('uye_id', '$uye_id', time() - 2592000, '/');
	}
	
	session_destroy();
	rapor(TRUE, "Başarıyla çıkış yapıldı!");
}
 
?>