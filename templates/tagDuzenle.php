<?php include "templates/include/header.php" ?>
 
	<?php
	
	$tag = $veriler['tag'];
	$sahip_olunan_ayetler = $veriler['sahip_olunan_ayetler'];
	$sahip_olunmayan_ayetler = $veriler['sahip_olunmayan_ayetler'];
	
	?>
	
    <div id="content">
	<div id="title">Tag Düzenleme</div>
	  
	<form action="tagler.php?action=tagDuzenle" method="POST">
	<input type="hidden" name="tag_id" value="<?php echo $tag->tag_id ?>" />
	
	<table>
		<tr>
			<td><label for="tag_adi">Tag adı: </td>
			<td><input type="text" name="tag_adi" id="tag_adi" value="<?php echo $tag->tag_adi ?>"/></label></td>
		</tr>
		
		<tr>
			<td>
				Ayet ekleyin: <br />
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla seçmek için Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="eklenecek_ayetler[]" size="5" multiple>
					<?php
					
					if(count($sahip_olunmayan_ayetler) > 0)
					{
						foreach($sahip_olunmayan_ayetler as $ayet)
						{
							echo "<option value='" . $ayet->ayet_id . "'>" .
								$ayet->sure_adi . " " . $ayet->sure_no . "</option>\n";
						}
					}
					else
					{
						echo "<option disabled='disabled'>Ayet yok!</option>";
					}
					?>
				</select>
			</td>
		</tr>
	
		<tr>
			<td>
				Ayetleri silin: <br />
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla seçmek için Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="silinecek_ayetler[]" size="5" multiple>
					<?php
					
					if(count($sahip_olunan_ayetler) > 0)
					{
						foreach($sahip_olunan_ayetler as $ayet)
						{
							echo "<option value='" . $ayet->ayet_id . "'>" .
								$ayet->sure_adi . " " . $ayet->sure_no . "</option>\n";
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
			<td />
			<td><input type="submit" name="tagDuzenle" value="Kaydet!" class="button" style="float:right;"/></td>
		</tr>
		
		<tr>
			<td />
			<td><a href="<?php echo "tagler.php?action=tagSil&ayetId=" . $tag->tag_id ?>" class="button" id="kirmizi" style="float:right;">Tag Sil</a></td>
		</tr>
		
	</table>
	</form>
	  
	</div>
 
<?php include "templates/include/footer.php" ?>