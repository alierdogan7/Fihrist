<?php include "templates/include/header.php" ?>
 
      <?php
		$tagler = $veriler['tagler'];
		$son_5_ayet = $veriler['son_5_ayet'];
		$son_5_ayet_tagler = $veriler['son_5_ayet_tagler'];
		$gunun_ayeti = $veriler['gunun_ayeti'];
		$gunun_ayeti_tagler = $veriler['gunun_ayeti_tagler'];
	  ?>
	  
	  <div id="content">
		<div id="title">Ana Sayfa</div>

		<div id="ayet">
		<p id="baslik"><?php echo "<a href='ayetler.php?action=ayetGoster&ayetId={$gunun_ayeti->ayet_id}' class='link'>Günün Ayeti: " . $gunun_ayeti->sure_adi . " " . $gunun_ayeti->sure_no ."</a>" ?></p>
				
		<p><div id="tagler-yazisi"><strong>Tag'ler: </strong></div>
		
		<?php 
		foreach($gunun_ayeti_tagler as $t)
		{
			echo "<a class='button' id='tag' href='tagler.php?action=tagGoster&tagId={$t->tag_id}'>{$t->tag_adi}</a>";
		}
		?>
		<div id="clearer"></div>
		</p>
		
		<p><strong>Meal:</strong></p>
		<p><?php echo metin_goster($gunun_ayeti->ayet_meal); ?></p>
		</div>

		
		<br />
		
		<div id="ayet">
		<p id="baslik">Tüm Tag'ler</p>

		<?php 
		foreach($tagler as $t)
		{
			echo "<a class='button' id='tag' href='tagler.php?action=tagGoster&tagId={$t->tag_id}' style='float:left;'>{$t->tag_adi}</a>";
		}
		?>
		
		<div id="clearer"> </div>
		</div>

		<br />
		<br />
		<br />

		<div id="title">Son eklenen 5 ayet </div>

		<?php
		foreach($son_5_ayet as $index => $ayet)
		{
		?>
			<div id="ayet_kucuk">
					
					<?php echo "<p><strong><a class='link' href='ayetler.php?action=ayetGoster&ayetId={$ayet->ayet_id}'>{$ayet->sure_adi} {$ayet->sure_no}</a></strong></p>"; ?>
					
					<p><i><?php echo tarih_ver($ayet->tarih) ?></i></p>
					
					<p><div id="tagler-yazisi"><strong>Tag'ler: </strong></div>
						
						<?php
						foreach($son_5_ayet_tagler[$index] as $t2)
						{
						echo "<a class='button' id='tag' href='tagler.php?action=tagGoster&tagId={$t2->tag_id}'>{$t2->tag_adi}</a>";			
						}
						?>
					</p>
					<div id="clearer"> </div>
					
					<p><strong>Meal: </strong><?php echo metin_goster($ayet->ayet_meal) ?></p>	
			</div>
		<?php
		}
		?>
		
	</div>
 
<?php include "templates/include/footer.php" ?>