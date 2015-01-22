<?php
//önce functions'ı sonra classes'ı include et çünkü classlar functions'ı kullanıyor
require_once("functions.php");
require_once("classes.php");

$action = isset($_GET['action']) ?  string_duzelt($_GET['action']) :  "";
$giris_yapmis = girisYapmis();

switch($action)
{
	case 'ayetGoster':
		ayetGoster();
		break;
	case 'ayetDuzenle':
		if($giris_yapmis) ayetDuzenle();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	case 'ayetEkle':
		if($giris_yapmis) ayetEkle();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	case 'ayetSil':
		if($giris_yapmis) ayetSil();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	default:
		ayetListele();
}

function ayetGoster()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( !isset($_GET['ayetId']) || !is_numeric($_GET['ayetId']) )
		rapor(FALSE, "Ayet bulunamadı" . $_GET['ayetId']);
	
	$veriler = array();
	
	$veriler['ayet'] = Ayet::getById($_GET['ayetId']);
	$veriler['baslik'] = "Ayetlerim - " . $veriler['ayet']->sure_adi . " " . $veriler['ayet']->sure_no . "";
	$veriler['tagler'] = AyetTag::getTagListByAyet($veriler['ayet']->ayet_id);
	
	require("templates/ayetGoster.php");
}

function ayetSil()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( !isset($_GET['ayetId']) || !is_numeric($_GET['ayetId']) )
		rapor(FALSE, "Ayet bulunamadı");
	
	$ayet = Ayet::getById($_GET['ayetId']);
	$ayet_id = $ayet->ayet_id;
	$ayet->delete();
	
	$ayet_tagler = AyetTag::getListByAyet($ayet_id);
	foreach($ayet_tagler as $ayet_tag)
	{
		$ayet_tag->delete();
	}
	
	rapor(TRUE, "Ayet başarıyla silindi");	
}

function ayetEkle()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( isset($_POST['ayetEkle']))
	{
		$tum_sureler = tum_sureleri_ver();
		$sure_index = --$_POST['sure_index']; //Fatiha index'i 1 olarak geliyor geri 0'a indirelim
		$data['sure_adi'] = $tum_sureler[$sure_index];

		$data['sure_no'] = $_POST['sure_no'];
		
		/*
		//ayet daha önceden ekli mi kontrol et
		if( Ayet::checkAyetExists($data['sure_adi'], $data['sure_no']) )
			rapor(FALSE, "Bu ayet daha önce eklenmiş!", "ayetler.php?action=ayetEkle");
		*/
		
		$data['ayet_meal'] = $_POST['ayet_meal'];
		$data['ek_not'] = $_POST['ek_not'];
		$data['tarih'] = time();
		
		$ayet = new Ayet($data);
		$ayet_id = $ayet->insert();
		
		$data['secilen_tagler'] = $_POST['tagler'];
		
		foreach($data['secilen_tagler'] as $tag_id)
		{
			$arr = array("ayet_id" => $ayet_id, "tag_id" => $tag_id);
			$ayet_tag = new AyetTag($arr);
			$ayet_tag->insert();
		}
		
		rapor(TRUE, $data['sure_adi'] . " " . $data['sure_no'] . " ayeti başarıyla eklendi!", "ayetler.php?action=ayetGoster&ayetId={$ayet->ayet_id}");
	}
	
	$veriler['baslik'] = "Ayetlerim - Ayet Ekleme";
	$veriler['tagler'] = Tag::getList("", "tag_adi ASC");
	
	require("templates/ayetEkle.php");
}

function ayetDuzenle()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if(!isset($_GET['ayetId']) || !is_numeric($_GET['ayetId']))
	{	
		if(isset($_POST['ayetDuzenle']))
		{
			$tum_sureler = tum_sureleri_ver();
			$sure_index = --$_POST['sure_index']; //Fatiha index'i 1 olarak geliyor geri 0'a indirelim
			$data['sure_adi'] = $tum_sureler[$sure_index];
		
			$data['sure_no'] = $_POST['sure_no'];
			$data['ayet_meal'] = $_POST['ayet_meal'];
			$data['ek_not'] = $_POST['ek_not'];
			$data['tarih'] = time();
			$data['ayet_id'] = $_POST['ayet_id'];
			
			$ayet = new Ayet($data);
			$ayet->update();
			
			$data['eklenecek_tagler'] = $_POST['eklenecek_tagler'];
			$data['silinecek_tagler'] = $_POST['silinecek_tagler'];
			
			foreach($data['eklenecek_tagler'] as $tag_id)
			{
				$arr = array("ayet_id" => $ayet->ayet_id, "tag_id" => $tag_id);
				$ayet_tag = new AyetTag($arr);
				$ayet_tag->insert();
			}
			
			foreach($data['silinecek_tagler'] as $tag_id)
			{
				$arr = array("ayet_id" => $ayet->ayet_id, "tag_id" => $tag_id);
				$ayet_tag = new AyetTag($arr);
				$ayet_tag->delete();
			}
			
			rapor(TRUE, "Ayet güncellendi", "ayetler.php?action=ayetGoster&ayetId={$ayet->ayet_id}");
		}
		else
		{
			rapor(FALSE, "Ayet bulunamadı");
		}
	}
	
	$ayet_id = $_GET['ayetId'];
	
	$veriler['baslik'] = "Ayetlerim - Ayet Düzenleme";
	$veriler['ayet'] = Ayet::getById($ayet_id);
	$veriler['sahip_olunan_tagler'] = AyetTag::getTagListByAyet($ayet_id, TRUE, "", "tag_adi ASC");
	$veriler['sahip_olunmayan_tagler'] = AyetTag::getTagListByAyet($ayet_id, FALSE, "", "tag_adi ASC");
	
	require("templates/ayetDuzenle.php");
}

function ayetListele()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	$bag = baglan();
	
	define("ROWS_PER_PAGE", 10);
	
	//sayfa numarası
	if(isset($_GET['page']) && is_numeric($_GET['page']))
		$page =  $_GET['page'];
	else
		$page = 1;
	
	//limiti sayfa numarasına göre ayarla
	$limit = "LIMIT ";
	$limit .= (($page - 1) * ROWS_PER_PAGE);
	$limit .= ", " . ROWS_PER_PAGE . "";
	
	
	
	//alfabetik sıralama
	if(isset($_GET['alfabetik']) && $_GET['alfabetik'] == "z-a")
	{
		$order = "sure_adi DESC";
		$veriler['alfabetik'] = "z-a";
	}
	elseif(isset($_GET['alfabetik']) && $_GET['alfabetik'] == "a-z")
	{
		$order = "sure_adi ASC";
		$veriler['alfabetik'] = "a-z";
	}
	else
	{
		$order = "";
		$veriler['alfabetik'] = "";
	}
	
	
	//tarih sıralaması
	if(isset($_GET['tarih']) && $_GET['tarih'] == "eski")
	{
		//alfabetik seçim yapılmışsa virgül koy
		if($order != "") $order .= ",tarih ASC";
		else $order = "tarih ASC";
		
		$veriler['tarih'] = "eski";
	}
	elseif(isset($_GET['tarih']) && $_GET['tarih'] == "yeni")
	{
		//alfabetik seçim yapılmışsa virgül koy
		if($order != "") $order .= ",tarih DESC";
		else $order = "tarih DESC";
		
		$veriler['tarih'] = "yeni";
	}
	else
	{
		$order .= "";
		$veriler['tarih'] = "";
	}
	
	//search yapılmışsa
	if(isset($_GET['ara']) && $_GET['ara'] != "")
	{	
		$str = string_duzelt($_GET['ara']);
		$words_array = explode(' ', $str);
		$count = count($words_array);
		
		$query = "SELECT SQL_CALC_FOUND_ROWS ayet_id FROM ayet WHERE";
		foreach($words_array as $index => $word)
		{
			$query .= " (ayet_meal LIKE '%$word%' OR sure_adi LIKE '%$word%' OR ek_not LIKE '%$word%')";
			if( $count != ($index+1) )
				$query .= " AND";
		}
		
		//eğer order command'i varsa
		if($order != "") $query .= " ORDER BY " . $order . " " . $limit;
		else $query .= $limit;
		
		$result = mysqli_query($bag, $query);
		if(!$result) rapor(FALSE, "hata");
		
		$sonuc_sayisi = getLastResultNum($bag);
		
		$ayetler = array();
		
		while ( $data = mysqli_fetch_array($result) )
		{
			$ayetler[] = Ayet::getById($data['ayet_id']);
		}
	
		$ayetler = $ayetler;
		$veriler['sonuc_sayisi'] = $sonuc_sayisi;
		$veriler['arama_metni'] = $str;
	}
	
	//search yapılmamışsa
	else
	{
		$ayetler =  Ayet::getList($limit, $order);
		$veriler['sonuc_sayisi'] = Ayet::$last_result_num; 
	}
	
	$tagler = array();
	
	foreach($ayetler as $ayet)
	{
		$tagler[] = AyetTag::getTagListByAyet($ayet->ayet_id, TRUE, "", "tag_adi ASC");
	}
	
	if($veriler['sonuc_sayisi'] == 0)
	{
		rapor(FALSE, $veriler['arama_metni'] . "için sonuç bulunamadı.");
	}
	
	$veriler['ayetler'] = $ayetler;
	$veriler['tagler'] = $tagler;
	$veriler['baslik'] = "Ayetlerim - Ayet Görüntüleme: Sayfa " . $page;
	$veriler['page'] = $page;
	$veriler['tarih'] = $veriler['tarih'];
	$veriler['sonuc_sayisi'] = $veriler['sonuc_sayisi'];
	
	require("templates/ayetListele.php");
}	 

?>
