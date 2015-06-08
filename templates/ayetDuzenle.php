<?php include "templates/include/header.php" ?>
 
	<?php
	
	$ayet = $veriler['ayet'];
	$sahip_olunan_tagler = $veriler['sahip_olunan_tagler'];
	$sahip_olunmayan_tagler = $veriler['sahip_olunmayan_tagler'];
	
	?>
	
    <div id="content">
	<div id="title">Ayet Düzenleme</div>
	  
	<form action="ayetler.php?action=ayetDuzenle" method="POST">
	<input type="hidden" name="ayet_id" value="<?php echo $ayet->ayet_id ?>" />
	
	<table>
		<tr>
			<td><label for="sure_index">Sure adı: </td>
			<td><select name="sure_index" id="sure_index">
			<option value="-27" disabled>--Sure Adı--</option>
			<?php
			$sureler = tum_sureleri_ver();
			$ayet_sayilari = tum_ayet_sayilari_ver();
			foreach($sureler as $index => $sure)
			{
				if($sure == $ayet->sure_adi)
					echo "<option value='" . ($index+1) . "' selected>$sure (" . ($index+1) . "/" . $ayet_sayilari[$index] . ")</option>\n";
				else
					echo "<option value='" . ($index+1) . "'>$sure (" . ($index+1) . "/" . $ayet_sayilari[$index] . ")</option>\n";
			}
			?></select>
			</label></td>
		</tr>
		
		<tr>
			<td><label for="sure_no">Sure no: </td>
			<td><input type="text" name="sure_no" id="sure_no" value="<?php echo $ayet->sure_no ?>" onfocusout="checkForm()" /></label></td>
		</tr>
		
		<tr>
			<td>
				Tag ekleyin: <br />
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla seçmek için Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="eklenecek_tagler[]" size="5" multiple>
					<?php
					
					if(count($sahip_olunmayan_tagler) > 0)
					{
						foreach($sahip_olunmayan_tagler as $tag)
						{
							echo "<option value='" . $tag->tag_id . "'>" .
								$tag->tag_adi . "</option>\n";
						}
					}
					else
					{
						echo "<option disabled='disabled'>Tag yok!</option>";
					}
					?>
				</select>
			</td>
		</tr>
	
		<tr>
			<td>
				Tag'leri silin: <br />
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla seçmek için Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="silinecek_tagler[]" size="5" multiple>
					<?php
					
					if(count($sahip_olunan_tagler) > 0)
					{
						foreach($sahip_olunan_tagler as $tag)
						{
							echo "<option value='" . $tag->tag_id . "'>" .
								$tag->tag_adi . "</option>\n";
						}
					}
					else
					{
						echo "<option disabled='disabled'>Tag yok!</option>";
					}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
		<td style="vertical-align:top;">Ayet meali: </td>
		<td>
			<select name="meal_sec" onchange="mealSec()" id="meal_sec">
					<option value="kendin" selected="true">Kendin yaz</option>
					<option value="elmali">Elmalılı Hamdi Yazır</option>
					<option value="diyanet_yeni">Diyanet İşleri (Yeni)</option>
					<option value="diyanet_vakfi">Diyanet Vakfı</option>
			</select>
			<button id="meal_yukle" style="display:none;">Meal yükle!</button>
			<span id="loading" style="font-weight:bold; display:none;"><img src="images/ajax-load.gif"> Yükleniyor...</span>
		</td>
		</tr>
		
		<tr>
			<td></td>
			<td>
			<textarea id="ayet_meal" name="ayet_meal" cols="50" rows="10"><?php echo $ayet->ayet_meal ?></textarea>
			</td>
		</tr>
		
		<tr>
			<td style="vertical-align:top;"><label for="ek_not">Not: </td>
			<td><textarea name="ek_not" cols="50" rows="5"><?php echo $ayet->ek_not ?></textarea></label></td>
		</tr>
		
		<tr>
			<td />
			<td><input type="submit" name="ayetDuzenle" value="Kaydet!" class="button" style="float:right;" id="form_submit" onclick="checkForm()"><input type="reset" value="Sıfırla" class="button" style="float:left;" id="sifirla_button"></td>
		</tr>
		
		<tr>
			<td />
			<td><a href="<?php echo "ayetler.php?action=ayetSil&ayetId=" . $ayet->ayet_id ?>" class="button" id="kirmizi" style="float:right;">Ayet Sil</a></td>
		</tr>
		
	</table>
	</form>
	  
	</div>
	
	
<?php include "templates/include/footer.php" ?>