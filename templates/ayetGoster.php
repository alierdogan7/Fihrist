<?php include "templates/include/header.php";?>
 
    <div id="content">
		  
		<div id="ayet">
			<p id="baslik"><?php echo $veriler['ayet']->sure_adi . " " . $veriler['ayet']->sure_no ?>

			<!--- PAYLAŞIM -->
<!-- Place this tag where you want the share button to render. -->
<div class="g-plus" data-action="share" data-annotation="bubble" data-height="24"></div>

<a href="javascript:void(0)" title="Facebook" onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href)+'&amp;t='+<?php "encodeURIComponent(document.title))"; echo "'". $veriler['baslik'] . "'";?>);return false;"><img src="http://unutulmazfilmler.com/images/facebook.gif" alt="Facebook'a Paylaş"></a>

			<!--- PAYLAŞIM -->
			
			</p>
			
			<p><i><?php echo tarih_ver($veriler['ayet']->tarih) ?></i></p>
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