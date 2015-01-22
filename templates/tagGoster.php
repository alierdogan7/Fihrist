<?php include "templates/include/header.php" ?>
 
	<?php 
	
		$ayetler = $veriler['ayetler'];
		$tagler = $veriler['tagler'];
		$tag = $veriler['tag'];

		$page = $veriler['page'];
		$sonuc_sayisi = $veriler['sonuc_sayisi'];
	?>
	
	 <div id="content">
	 <div id="title"><?php echo "Tag: " . $tag->tag_adi . ""; ?>
	 
	<?php 
	if($giris_yapmis) 
	{
		echo "<div style='float:right'><a href='tagler.php?action=tagDuzenle&tagId={$tag->tag_id}' class='button' id='yesil' style='font-size:.7em;'>Tag Düzenle</a>
	<a href='tagler.php?action=tagSil&tagId={$tag->tag_id}' class='button' id='kirmizi' style='font-size:.7em;'>Tag Sil</a></div>";
	} 
	?>
	</div>
	
	<div class="kutu" id="gri">
	 <form action="tagler.php" method="GET">
	 
	 <input type="hidden" name="action" value="tagGoster" />
	 <input type="hidden" name="tagId" value="<?php echo $tag->tag_id ?>" />
	 
	 <label>Tarih: 
	 <select name="tarih">
	 <option value="eski">Eskiden Yeniye</option>
	 <option value="yeni">Yeniden Eskiye</option>
	 </select></label>&nbsp;&nbsp;&nbsp;
	 <input type="submit" value="Sırala!" class="button" />
	 </form>
	 </div>
	
	<?php 
	//Sonuç kutusu
	echo "<div class='alert' id='basari'><p><a href=\"tagler.php?action=tagGoster?tagId={$tag->tag_id}\" class='button' id='tag' style='float:none;'> {$tag->tag_adi} </a><strong>" . $sonuc_sayisi . "</strong> sonuç bulundu.</p>";
	
	if($sonuc_sayisi < ROWS_PER_PAGE)
	{
		$ilk = (($page - 1) * $sonuc_sayisi) + 1;
		$son = $page * $sonuc_sayisi;
	}
	else
	{
		$ilk = (($page - 1) * ROWS_PER_PAGE) + 1;
		$son = $page * ROWS_PER_PAGE;
	}
		
	echo "<p>Şu an $ilk - $son arası sonuçları görüntülemektesiniz.</p></div>";
	?>
	
	<?php
	//Bulunan ayetleri listele
	foreach($ayetler as $index => $ayet) 
	{
	?>
		
		<div id="ayet_kucuk">
		
		<p><strong><?php echo "<a href='ayetler.php?action=ayetGoster&ayetId=" . $ayet->ayet_id . "' class='link'>" . $ayet->sure_adi . " " . $ayet->sure_no . "</a>"?></strong></p>
		
		<p><div id="tagler-yazisi"><strong>Tag'ler: </strong></div>
			
			<?php 
			//Şu anki ayet'in taglerini yazdır
			foreach($tagler[$index] as $t)
			{
				echo "<a href='tagler.php?action=tagGoster&tagId=" . $t->tag_id . "' class='button' id='tag'>" . $t->tag_adi . "</a>";
				
			}
			?>
			
		</p>
		<div id="clearer"></div>
		
		<p><strong>Meal: </strong><?php echo metin_goster($ayet->ayet_meal) ?></p>

		<?php if($giris_yapmis) { ?>
		<p style="float:right; font-size:.7em;"><a href="<?php echo "ayetler.php?action=ayetDuzenle&ayetId=" . $ayet->ayet_id ?>" class="button" id="yesil">Ayet Düzenle</a>
		<a href="<?php echo "ayetler.php?action=ayetSil&ayetId=" . $ayet->ayet_id ?>" class="button" id="kirmizi">Ayet Sil</a></p>
		<?php } ?>
		
		</div>
	
		
	<?php
	 }
	?>
	  
	  <div class="kutu" id="gri">
	<?php
	//Sayfa değiştirme paneli
	$link = "<a href='tagler.php?action=tagGoster";
	$link .= "&tarih={$veriler['tarih']}";
	$link .= "&tagId={$tag->tag_id}";
	
	if($page == 1)
	{
		$link_prev = "<a style='color:#777' class='link'>«« Önceki Sayfa</a>";
	}
	else
	{
		$link_prev = $link . "&page=" . ($page-1) .
					"' class='link'>«« Önceki Sayfa</a>";
	}
	
	$max_page_num = ($sonuc_sayisi / ROWS_PER_PAGE) + 1;
	
	if(  ($page + 1) < $max_page_num )
	{
		$link_next = $link . "&page=" . ($page+1) .
					"' class='link'>Sonraki Sayfa »»</a>";
	}
	else
	{
		$link_next = "<a style='color:#777' class='link'>Sonraki Sayfa »»</a>";
	}
	
	echo $link_prev . "&nbsp;&nbsp;&nbsp;" . $link_next;
	?>
	  </div>
	  
	 </div>
	
	  
 
<?php include "templates/include/footer.php" ?>