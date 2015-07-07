<?php include "templates/include/header.php" ?>
 
	<?php 
		$ayetler = $veriler['ayetler']; 
		$taglerler = $veriler['tagler'];
		$sonuc_sayisi = $veriler['sonuc_sayisi'];
	?>
	
	 <div id="content">
	 <div id="title"><?php echo $veriler['baslik'] ?></div>
	 
	 <div class="kutu" id="gri">
	 <form action="ayetler.php?action=ayetListele" method="GET">
		<table style="width: 100%;">
			<tbody>
			<tr>
				<td colspan="3">Arama: <br><input type="text" name="ara"></td>
			</tr>
			<tr>
				<td style="width: 35%;">
					Tarih Sıralaması: <br />
					<select name="tarih">
					<?php
						$options = array('<option value="">Seçim Yapınız</option>', '<option value="yeni">Yeniden Eskiye</option>',
										'<option value="eski">Eskiden Yeniye</option>', '<option value="" selected>Seçim Yapınız</option>',
										'<option value="yeni" selected>Yeniden Eskiye</option>', '<option value="eski" selected>Eskiden Yeniye</option>');
										
						if($veriler['tarih'] == "eski") echo $options[0] . "\n" . $options[1] . "\n" . $options[5];
						elseif($veriler['tarih'] == "yeni") echo $options[0] . "\n" . $options[4] . "\n" . $options[2];
						else echo $options[3] . "\n" . $options[1] . "\n" . $options[2];
					?>
					 </select>
				</td>
				<td style="width: 35%;">
					Alfabetik Sıralama: <br />
					<select name="alfabetik">
					<?php
						$options = array('<option value="">Seçim Yapınız</option>', '<option value="a-z">A\'dan Z\'ye</option>',
										'<option value="z-a">Z\'den A\'ya</option>', '<option value="" selected>Seçim Yapınız</option>',
										'<option value="a-z" selected>A\'dan Z\'ye</option>', '<option value="z-a" selected>Z\'den A\'ya</option>');
						if($veriler['alfabetik'] == "z-a") echo $options[0] . "\n" . $options[1] . "\n" . $options[5];
						elseif($veriler['alfabetik'] == "a-z") echo $options[0] . "\n" . $options[4] . "\n" . $options[2];
						else echo $options[3] . "\n" . $options[1] . "\n" . $options[2];
					?>
					 </select>
				</td>
   
				<td style="text-align: right;">
					<input type="submit" value="Listele!" class="button">
				</td>
			</tr>
			</tbody>
		</table>
	 </form>
	 </div>
	 
	<?php 
	
	//Sonuç kutusu
	if (isset($veriler['arama_metni']))
	{
		$arama_metni = $veriler['arama_metni'];
		echo "<div class='alert' id='basari'><p><strong>$arama_metni</strong> için <strong>" . $sonuc_sayisi . "</strong> sonuç bulundu.</p>";
	}
	else
	{
		echo "<div class='alert' id='basari'> Toplam <strong>" . $sonuc_sayisi . "</strong> sonuç bulundu.";
	}
	
	if($sonuc_sayisi < ROWS_PER_PAGE)
	{
		$ilk = (($veriler['page'] - 1) * $sonuc_sayisi) + 1;
		$son = $veriler['page'] * $sonuc_sayisi;
	}
	else
	{
		$ilk = (($veriler['page'] - 1) * ROWS_PER_PAGE) + 1;
		$son = $veriler['page'] * ROWS_PER_PAGE;
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
			foreach($taglerler[$index] as $tag)
			{
				echo "<a href='tagler.php?action=tagGoster&tagId=" . $tag->tag_id . "' class='button' id='tag'>" . $tag->tag_adi . "</a>";
				
			}
			?>
			
		</p>
		<div id="clearer"></div>
		
		<p><strong>Meal: </strong><?php echo metin_goster($ayet->ayet_meal) ?></p>
		
		<?php if( $ayet->ek_not != "" ) { ?>
		<p><strong><u><a href='ayetler.php?action=ayetGoster&ayetId=<?php echo $ayet->ayet_id; ?>' class='link'>Ek Not...</a></u></strong></p>
		<?php } ?>

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
	$link = "<a href='ayetler.php?action=ayetListele";
	if($veriler['tarih'] != "") $link .= "&tarih={$veriler['tarih']}";
	if($veriler['alfabetik'] != "") $link .= "&alfabetik={$veriler['alfabetik']}";
	
	if( isset($veriler['arama_metni']))
		$link .=  "&ara=" . str_replace(" ", "+", $veriler['arama_metni']) . "";
	
	if($veriler['page'] == 1)
	{
		$link_prev = "<a style='color:#777' class='link'>«« Önceki Sayfa</a>";
	}
	else
	{
		$link_prev = $link . "&page=" . ($veriler['page']-1) .
					"' class='link'>«« Önceki Sayfa</a>";
	}
	
	$max_page_num = ($sonuc_sayisi / ROWS_PER_PAGE) + 1;
	
	if(  ($veriler['page'] + 1) < $max_page_num )
	{
		$link_next = $link . "&page=" . ($veriler['page']+1) .
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
