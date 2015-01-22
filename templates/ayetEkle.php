<?php include "templates/include/header.php" ?>
 
    <div id="content">
	<div id="title">Ayet Ekle</div>
	  
	<form action="ayetler.php?action=ayetEkle" method="POST">
	
	<table>
		<tr>
			<td><label for="sure_index">Sure adı: </label></td>
			<td><select name="sure_index" id="sure_index">
			<option value="-27" disabled>-- Sure Adı --</option>
			<?php
			$sureler = tum_sureleri_ver();
			$ayet_sayilari = tum_ayet_sayilari_ver();
			foreach($sureler as $index => $sure)
				echo "<option value='" . ($index+1) . "'>$sure (" . ($index+1) . "/" . $ayet_sayilari[$index] . ")</option>\n";
			?></select>
			</label></td>
		</tr>
		
		<tr>
			<td><label for="sure_no">Sure no: </label></td>
			<td><input type="text" name="sure_no" id="sure_no" onfocusout="checkForm()" /></td>
		</tr>

		<tr>
			<td>
				Tag ekleyin: <br>
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla eklemek için Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="tagler[]" size="5" multiple>
					<?php
					
					if(count($veriler['tagler']) > 0)
					{
						foreach($veriler['tagler'] as $tag)
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
					<option value="diyanet_eski">Diyanet İşleri (Eski)</option>
					<option value="diyanet_vakfi">Diyanet Vakfı</option>
					<option value="arapca">Arapça</option>
					<option value="suat_yildirim">Suat Yıldırım</option>
					<option value="hayrat">Hayrat Neşriyat</option>
					<option value="yusuf_ali_eng">Yusuf Ali (İngilizce)</option>
					<option value="turkce_transcript">Türkçe Transkript</option>
			</select>
			<button id="meal_yukle" style="display:none;">Meal yükle!</button>
			<span id="loading" style="font-weight:bold; display:none;"><img src="images/ajax-load.gif"> Yükleniyor...</span>
		</td>
		</tr>
		
		<tr>
			<td></td>
			<td>
			<textarea id="ayet_meal" name="ayet_meal" cols="50" rows="10"></textarea>
			</td>
		</tr>
		
		<tr>
			<td style="vertical-align:top;"><label for="ek_not">Not: </label></td>
			<td><textarea name="ek_not" cols="50" rows="5"></textarea></td>
		</tr>
		
		<tr>
			<td>
			</td><td><input type="submit" name="ayetEkle" value="Ekle!" class="button" style="float:right;" id="form_submit" onclick="checkForm()"><input type="reset" value="Sıfırla" class="button" style="float:left;" id="sifirla_button"></td>
		</tr>
	</table>
	</form>
	  
	</div>
	
 
<?php include "templates/include/footer.php" ?>