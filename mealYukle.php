<?php
require("classes/simple_html_dom.php");
require_once("functions.php");

function getAutoAyetMeal($sure_index, $sure_no, $meal_secimi = 'elmali')
{
	//diyanet kuran portalındaki meal indexleri
	switch($meal_secimi)
	{
		case 'elmali':
			$meal_index = 6;
			break;
		case 'diyanet_yeni':
			$meal_index = 1;
			break;
		case 'diyanet_vakfi':
			$meal_index = 4;
			break;		
		default:
			$meal_index = 1;
	}
	
	$regex1 = '/^([1-9]\d{0,2})-([1-9]\d{0,2})$/'; // 5-13 gibi girdiler için
	$regex2 = '/^([1-9]\d{0,2})$/'; // 28 gibi girdiler için
	
	if(preg_match($regex1, $sure_no) ) //birden çok ayet varsa
	{
		preg_match_all($regex1, $sure_no, $results);

		$ayet_no1 = $results[1][0];
		$ayet_no2 = $results[2][0];
		$meal = "";
		
		//before php 5.4 array accesses like getArray()[3] is invalid, so this temporary variable is to be used
		$tmp = tum_ayet_sayilari_ver();
		$sure_son_ayet = $tmp[$sure_index + 1];
		
		//eğer 5-3 gibi birşey girilmemişse ve ayet indexleri doğru aralıktaysa
		if( ($ayet_no1 <= $ayet_no2) && ($ayet_no1 >= 1 && $ayet_no2 <= $sure_son_ayet ) )
		{
			for($i=$ayet_no1; $i <= $ayet_no2; $i++)
			{
				$html = file_get_html("http://kuran.diyanet.gov.tr/KuranHandler.ashx?lid=" . $meal_index . "&a=" . $sure_index . ":" . $i);
				
				if (method_exists($html,"find")) {
					 // then check if the html element exists to avoid trying to parse non-html
					 if ($html->find('span[id=tr_' . $sure_index . '-' . $i . ']')) {
						  // and only then start searching (and manipulating) the dom
						$str = $html->find('span[id=tr_' . $sure_index . '-' . $i . ']', 0)->plaintext;
						$str = trim($str, " \t\r\n");
						$meal .= $str . "\r\n\r\n"; //tr15'in ikinci sütunu(meal kısmı)nın düz text hali
					 }
				}
				else return "Ayet bulunamadı1"; //eğer sayfa bulunamazsa tüm meali komple yoket
			}
			
			return substr($meal, 0, -4);; //loop'tan başarıyla çıkarsa meali return et
		}
		else return "Ayet aralığını kontrol ediniz.";
	}
	elseif(preg_match($regex2, $sure_no) ) //tek ayet varsa
	{
		$html = file_get_html("http://kuran.diyanet.gov.tr/KuranHandler.ashx?lid=" . $meal_index . "&a=" . $sure_index . ":" . $sure_no);
				
		if (method_exists($html,"find")) {
			 // then check if the html element exists to avoid trying to parse non-html
			 if ($html->find('span[id=tr_' . $sure_index . '-' . $sure_no . ']')) {
				  // and only then start searching (and manipulating) the dom
				$meal = $html->find('span[id=tr_' . $sure_index . '-' . $sure_no . ']', 0)->plaintext;
				$meal = trim($meal, " \t\r\n");
				return $meal;
			 }
			 else return "Ayet bulunamadı2";
		}
		else return "Ayet bulunamadı3";
	}
	else
	{
		return "Ayet bulunamadı(regex)";
	}
}
?>
<?php
/*
//simple_html_dom object
$html = file_get_html("http://www.kuranmeali.com/ayetkarsilastirma.asp?sure=16&ayet=1");

$isim = $html->find('a[href^=sureler.asp?sureno]', 0)->plaintext;
echo $isim;

$meal = $html->find('tr[id=tr15]', 0);
$meal = $meal->find('td', 1);
$meal = $meal->plaintext;

echo "isim: " . $isim . " - Meal: " . $meal . "";
*/
?>
<?php
if(isset($_GET['sure_index']) && isset($_GET['sure_no']) 
	&& isset($_GET['meal_sec']) )
{
	$meal = getAutoAyetMeal($_GET['sure_index'], $_GET['sure_no'], $_GET['meal_sec']);
	$meal = trim($meal, " \t\r\n");
	echo $meal;
}
?>