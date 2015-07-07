<?php include "templates/include/header.php"; 
require("classes/simple_html_dom.php");?>


    <div id="content">
		  
		<div id="ayet">
			<p id="baslik"><?php echo $veriler['ayet']->sure_adi . " " . $veriler['ayet']->sure_no ?>

			<table style="width: 100%;">
  			<tr>
   			<td style="width: 33%;"><i><?php echo tarih_ver($veriler['ayet']->tarih) ?></i></td>
 			
 			<?php if (sureIndexBul($veriler['ayet']->sure_adi) != -1) {
 			?>
   			<td style="width: 33%;">
   			 	<center>Dinle: <br>
   			 	<audio controls="" style="width: 45px;">
				 <?php echo '<source src="http://webdosya.diyanet.gov.tr/kuran/Sound/ar_ishakdemir/' . 
				 			sureIndexBul($veriler['ayet']->sure_adi) . "_" . $veriler['ayet']->sure_no . '.mp3" type="audio/mpeg">';
				 ?>
				  Browser'ınız bunu desteklemiyor.
				  </audio></center>
			</td>
			<?php
			}
			?>
			
			<td style="width: 33%;">
			<!--- PAYLAŞIM -->
			<div id="___plus_0" style="text-indent: 0px; margin: 0px; padding: 0px; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 94px; height: 24px; background: transparent;"><iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 94px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 24px;" tabindex="0" vspace="0" width="100%" id="I0_1433877625386" name="I0_1433877625386" src="https://apis.google.com/u/0/se/0/_/+1/sharebutton?plusShare=true&amp;usegapi=1&amp;action=share&amp;annotation=bubble&amp;height=24&amp;hl=tr&amp;origin=http%3A%2F%2Flocalhost&amp;url=http%3A%2F%2Flocalhost%2FAyetlerim%2Fayetler.php%3Faction%3DayetGoster%26ayetId%3D117&amp;gsrc=3p&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.en.utjGQShWxzw.O%2Fm%3D__features__%2Fam%3DAQ%2Frt%3Dj%2Fd%3D1%2Ft%3Dzcms%2Frs%3DAGLTcCP2OuliJPRPjWZHaVKTj26RhlNGzA#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Cdrefresh%2Cerefresh%2Conload&amp;id=I0_1433877625386&amp;parent=http%3A%2F%2Flocalhost&amp;pfname=&amp;rpctoken=16288840" data-gapiattached="true" title="+Paylaş"></iframe></div>
			<a href="javascript:void(0)" title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href)+'&amp;t='+'Ayetlerim - Alâ 14-17');return false;"><img src="http://www.gundogmus.gov.tr/ortak_icerik/gundogmus/Facebook%20payla%C5%9F/1k.png" alt="Facebook'a Paylaş"></a>
			<!--- PAYLAŞIM -->
			</td>
			
 			</tr>
 			</table>
 			</p>


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