<?php
//önce functions'ı sonra classes'ı include et çünkü classlar functions'ı kullanıyor
require_once("functions.php");
require_once("classes.php");

$action = isset($_GET['action']) ?  string_duzelt($_GET['action']) :  "";
$giris_yapmis = girisYapmis();

switch($action)
{
	case 'tagGoster':
		tagGoster();
		break;
	case 'tagDuzenle':
		if($giris_yapmis) tagDuzenle();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	case 'tagEkle':
		if($giris_yapmis) tagEkle();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	case 'tagSil':
		if($giris_yapmis) tagSil();
		else rapor(FALSE, "Giriş yapılmamış!");
		break;
	default:
		tagListele();
}

function tagGoster()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( !isset($_GET['tagId']) || !is_numeric($_GET['tagId']) )
		rapor(FALSE, "Tag bulunamadı" . $_GET['tagId']);
	
	$veriler = array();
	$tag_id = $_GET['tagId'];
	$tag = Tag::getById($tag_id);
	
	if(!isset($tag))
	{
		rapor(FALSE, "Tag bulunamadı");
	}
	
	//tag bulunduysa
	
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
	
	//tarih sıralaması
	if(isset($_GET['tarih']) && $_GET['tarih'] == "eski")
	{
		$order = "tarih ASC";
		$veriler['tarih'] = "eski";
	}
	else
	{
		$order = "tarih DESC";
		$veriler['tarih'] = "yeni";
	}
	
	$tagler = array();
	
	$ayetler = AyetTag::getAyetListByTag($tag_id, TRUE, $limit, $order);
	$veriler['sonuc_sayisi'] = AyetTag::$last_result_num; 
	
	foreach($ayetler as $ayet)
	{
		$tagler[] = AyetTag::getTagListByAyet($ayet->ayet_id, TRUE, "", "tag_adi ASC");
	}
	
	$veriler['ayetler'] = $ayetler;
	$veriler['tagler'] = $tagler;
	$veriler['tag'] = $tag;
	
	$veriler['baslik'] = "Ayetlerim - Tag: " . $tag->tag_adi;
	$veriler['page'] = $page;
	$veriler['tarih'] = $veriler['tarih'];
	$veriler['sonuc_sayisi'] = $veriler['sonuc_sayisi'];

	require("templates/tagGoster.php");
}

function tagDuzenle()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if(!isset($_GET['tagId']) || !is_numeric($_GET['tagId']))
	{	
		if(isset($_POST['tagDuzenle']))
		{
			$data['tag_adi'] = $_POST['tag_adi'];
			$data['tarih'] = time();
			$data['tag_id'] = $_POST['tag_id'];
			
			$tag = new Tag($data);
			$tag->update();
			
			$data['eklenecek_ayetler'] = $_POST['eklenecek_ayetler'];
			$data['silinecek_ayetler'] = $_POST['silinecek_ayetler'];
			
			foreach($data['eklenecek_ayetler'] as $ayet_id)
			{
				$arr = array("ayet_id" => $ayet_id, "tag_id" => $tag->tag_id);
				$ayet_tag = new AyetTag($arr);
				$ayet_tag->insert();
			}
			
			foreach($data['silinecek_ayetler'] as $ayet_id)
			{
				$arr = array("ayet_id" => $ayet_id, "tag_id" => $tag->tag_id);
				$ayet_tag = new AyetTag($arr);
				$ayet_tag->delete();
			}
			
			rapor(TRUE, "Tag güncellendi", "tagler.php?action=tagGoster&tagId={$tag->tag_id}");
		}
		else
		{
			rapor(FALSE, "Tag bulunamadı");
		}
	}
	
	$tag_id = $_GET['tagId'];
	
	$veriler['baslik'] = "Ayetlerim - Tag Düzenleme";
	$veriler['tag'] = Tag::getById($tag_id);
	$veriler['sahip_olunan_ayetler'] = AyetTag::getAyetListByTag($tag_id, TRUE);
	$veriler['sahip_olunmayan_ayetler'] = AyetTag::getAyetListByTag($tag_id, FALSE);
	
	require("templates/tagDuzenle.php");

}

function tagEkle()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( isset($_POST['tagEkle']))
	{
		$data['tag_adi'] = $_POST['tag_adi'];
		$data['ayet_idleri'] = $_POST['ayet_idleri'];
		$data['tarih'] = time();
		
		$tag = new Tag($data);
		$tag_id = $tag->insert();
		
		foreach($data['ayet_idleri'] as $ayet_id)
		{
			$arr = array("ayet_id" => $ayet_id, "tag_id" => $tag_id);
			$ayet_tag = new AyetTag($arr);
			$ayet_tag->insert();
		}
		
		rapor(TRUE, $data['tag_adi'] . " tag'i başarıyla eklendi!", "tagler.php?action=tagGoster&tagId={$tag->tag_id}");
	}
	
	$veriler['baslik'] = "Ayetlerim - Tag Ekleme";
	$veriler['ayetler'] = Ayet::getList("");
	
	require("templates/tagEkle.php");
}

function tagSil()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	if ( !isset($_GET['tagId']) || !is_numeric($_GET['tagId']) )
		rapor(FALSE, "Tag bulunamadı");
	
	$tag = Tag::getById($_GET['tagId']);
	$tag_id = $tag->tag_id;
	$tag->delete();
	
	$ayet_tagler = AyetTag::getListByTag($tag_id);
	foreach($ayet_tagler as $ayet_tag)
	{
		$ayet_tag->delete();
	}
	
	rapor(TRUE, "Tag başarıyla silindi");
}

function tagListele()
{
	global $giris_yapmis; //$giris_yapmis variable'ı fonksiyon içine aktarılır
	
	$veriler = array();
	
	$order = "tag_adi ASC";
	
	$tagler = Tag::getList("", $order);
	$veriler['sonuc_sayisi'] = count($tagler);
	
	if($veriler['sonuc_sayisi'] == 0)
	{
		rapor(FALSE, "Sonuç bulunamadı");
	}
	
	$veriler['ayet_sayilari'] = array();
	
	foreach($tagler as $tag)
	{
		$veriler['ayet_sayilari'][] = AyetTag::getAyetNumberByTag($tag->tag_id);
	}
	
	$veriler['tagler'] = $tagler;
	$veriler['baslik'] = "Ayetlerim - Tag Listele";
	$veriler['sonuc_sayisi'] = $veriler['sonuc_sayisi'];
	$veriler['ayet_sayilari'] = $veriler['ayet_sayilari'];
	
	require("templates/tagListele.php");
}