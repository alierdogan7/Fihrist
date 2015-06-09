<?php include "templates/include/header.php"; 
require("classes/simple_html_dom.php");?>
 
    <div id="content">
		  
		<div id="ayet">
			<p id="baslik"><?php echo $veriler['ayet']->sure_adi . " " . $veriler['ayet']->sure_no ?>

			<!--- PAYLAŞIM -->
<!-- Place this tag where you want the share button to render. -->
<div class="g-plus" data-action="share" data-annotation="bubble" data-height="24"></div>

<a href="javascript:void(0)" title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href)+'&amp;t='+<?php "encodeURIComponent(document.title))"; echo "'". $veriler['baslik'] . "'";?>);return false;"><img src="http://www.gundogmus.gov.tr/ortak_icerik/gundogmus/Facebook%20payla%C5%9F/1k.png" alt="Facebook'a Paylaş"></a>

			<!--- PAYLAŞIM -->
			
			</p>
			
			<p><i><?php echo tarih_ver($veriler['ayet']->tarih) ?></i></p>

			<p id="arapca"><?php echo getArabicText( sureIndexBul($veriler['ayet']->sure_adi), $veriler['ayet']->sure_no ); ?></p>

			<p><div id="tagler-yazisi"><strong>Tag'ler: </strong></div>
			
			<?php 
			foreach($veriler['tagler'] as $tag) 
				echo "<a href='tagler.php?action=tagGoster&tagId=" . $tag->tag_id . "' class='button' id='tag'>" . $tag->tag_adi . "</a>";
			?>
			
			</p>
			<div id="clearer"></div>
			
			<p><strong>Meal:</strong></p>
			
			<p>
			<?php echo metin_goster($veriler['ayet']->ayet_meal); ?>
			</p>
			
			<p><strong>Not: </strong></p>
			
			<p>
			<?php echo metin_goster($veriler['ayet']->ek_not); ?>
			</p>
			
			<?php if($giris_yapmis) { ?>
			<p style="float:right;"><a href="<?php echo "ayetler.php?action=ayetDuzenle&ayetId=" . $veriler['ayet']->ayet_id ?>" class="button" id="yesil">Ayet Düzenle</a>
			<a href="<?php echo "ayetler.php?action=ayetSil&ayetId=" . $veriler['ayet']->ayet_id ?>" class="button" id="kirmizi">Ayet Sil</a></p>
			<?php } ?>
			
			<div id="clearer"> </div>
		</div>
	</div>


<?php include "templates/include/footer.php" ?>


<?php

function getArabicText($sure_index, $sure_no)
{
	if($sure_index == -1)
		return "Arapça metin bulunamadı0";

	$regex1 = '/^([1-9]\d{0,2})-([1-9]\d{0,2})$/'; // 5-13 gibi girdiler için
	$regex2 = '/^([1-9]\d{0,2})$/'; // 28 gibi girdiler için
	
	if(preg_match($regex1, $sure_no) ) //birden çok ayet varsa
	{
		preg_match_all($regex1, $sure_no, $results);

		$ayet_no1 = $results[1][0];
		$ayet_no2 = $results[2][0];
		$text = "";
		
		//eğer 5-3 gibi birşey girilmemişse
		if($ayet_no1 <= $ayet_no2)
		{
			for($i=$ayet_no1; $i <= $ayet_no2; $i++)
			{
				$html = file_get_html("http://kuran.diyanet.gov.tr/KuranHandler.ashx?l=ar&a=" . $sure_index . ":" . $i);
				
				if (method_exists($html,"find")) {
					 // then check if the html element exists to avoid trying to parse non-html
					 if ($html->find('span[id=ar_' . $sure_index . '-' . $i . ']')) {
						  // and only then start searching (and manipulating) the dom
						$str = $html->find('span[id=ar_' . $sure_index . '-' . $i . ']', 0)->plaintext;
						$str = trim($str, " \t\r\n");
					 }
					 else return "Arapça metin bulunamadı1"; //eğer sayfa bulunamazsa tüm meali komple yoket
				}
				else return "Arapça metin bulunamadı2"; //eğer sayfa bulunamazsa tüm meali komple yoket
			}
			
			return substr($text, 0, -4); //loop'tan başarıyla çıkarsa meali return et
		}
		else return "İlk ayet numarası ikincisinden büyük olamaz.";
	}
	elseif(preg_match($regex2, $sure_no) ) //tek ayet varsa
	{
		$html = file_get_html("http://kuran.diyanet.gov.tr/KuranHandler.ashx?l=ar&a=" . $sure_index . ":" . $sure_no);
				
		if (method_exists($html,"find")) {
			 // then check if the html element exists to avoid trying to parse non-html
			 if ($html->find('span[id=ar_' . $sure_index . '-' . $sure_no . ']')) {
				  // and only then start searching (and manipulating) the dom
				$text = $html->find('span[id=ar_' . $sure_index . '-' . $sure_no . ']', 0)->plaintext;
				$text = trim($text, " \t\r\n");
				return $text;
			 }
			 else return "Arapça metin bulunamadı3";
		}
		else return "Arapça metin bulunamadı4";
	}
	else
	{
		return "Arapça metin bulunamadı(regex)";
	}
}

?>