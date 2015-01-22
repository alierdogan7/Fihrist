<?php
require("classes/simple_html_dom.php");

function getAutoAyetMeal($sure_index, $sure_no, $meal_secimi = 'elmali')
{
	//kuranmeali.com'daki tr15 gibi id'lere sahip sütunları bulmak için(her meal için ayrı tr var)
	switch($meal_secimi)
	{
		case 'elmali':
			$meal_index = 15;
			break;
		case 'diyanet_eski':
			$meal_index = 11;
			break;
		case 'diyanet_yeni':
			$meal_index = 12;
			break;
		case 'diyanet_vakfi':
			$meal_index = 13;
			break;
		case 'arapca':
			$meal_index = 1;
			break;	
		case 'suat_yildirim':
			$meal_index = 22;
			break;	
		case 'hayrat':
			$meal_index = 18;
			break;	
		case 'yusuf_ali_eng':
			$meal_index = 27;
			break;	
		case 'turkce_transcript':
			$meal_index = 2;
			break;		
		default:
			$meal_index = 15;
	}
	
	$regex1 = '/^([1-9]\d{0,2})-([1-9]\d{0,2})$/'; // 5-13 gibi girdiler için
	$regex2 = '/^([1-9]\d{0,2})$/'; // 28 gibi girdiler için
	
	if(preg_match($regex1, $sure_no) )
	{
		preg_match_all($regex1, $sure_no, $results);

		$ayet_no1 = $results[1][0];
		$ayet_no2 = $results[2][0];
		$meal = "";
		
		//eğer 5-3 gibi birşey girilmemişse
		if($ayet_no1 <= $ayet_no2)
		{
			for($i=$ayet_no1; $i <= $ayet_no2; $i++)
			{
				$html = file_get_html("http://www.kuranmeali.com/ayetkarsilastirma.asp?sure=" . $sure_index . "&ayet=" . $i);
				
				if (method_exists($html,"find")) {
					 // then check if the html element exists to avoid trying to parse non-html
					 if ($html->find('tr[id=tr' . $meal_index . ']')) {
						  // and only then start searching (and manipulating) the dom
						$str = $html->find('tr[id=tr' . $meal_index . ']', 0)->find('td', 1)->plaintext;
						$str = trim($str, " \t\r\n");
						$meal .= $str . "\r\n\r\n"; //tr15'in ikinci sütunu(meal kısmı)nın düz text hali
					 }
					 else return "Belirli aralıkta bir veya birkaç ayet bulunamadı. Ayet aralığını kontrol ediniz"; //eğer sayfa bulunamazsa tüm meali komple yoket
				}
				else return "Ayet bulunamadı"; //eğer sayfa bulunamazsa tüm meali komple yoket
			}
			
			return substr($meal, 0, -4);; //loop'tan başarıyla çıkarsa meali return et
		}
		else return "İlk ayet numarası ikincisinden büyük olamaz.";
	}
	elseif(preg_match($regex2, $sure_no) )
	{
		$html = file_get_html("http://www.kuranmeali.com/ayetkarsilastirma.asp?sure=" . $sure_index . "&ayet=" . $sure_no);

		if (method_exists($html,"find")) {
			 // then check if the html element exists to avoid trying to parse non-html
			 if ($html->find('tr[id=tr' . $meal_index . ']')) {
				  // and only then start searching (and manipulating) the dom
				$meal = $html->find('tr[id=tr' . $meal_index . ']', 0);
				$meal = $meal->find('td', 1);
				$meal = $meal->plaintext;
				$meal = trim($meal, " \t\r\n");
				return $meal;
			 }
			 else return "Ayet bulunamadı";
		}
		else return "Ayet bulunamadı";
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