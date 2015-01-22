<?php

class Ayet {

	public $ayet_id = null;
	public $tarih = null;
	public $sure_no = null;
	public $sure_adi = null;
	public $ayet_meal = null;
	public $ek_not = null;
	public static $last_result_num = null;
	
	public function __construct( $data = array() )
	{
		/*if ( isset($data['ayet_id']) ) $this->ayet_id = (int) $data['ayet_id'];
		if ( isset($data['tarih']) )$this->tarih = (int) $data['tarih'];
		if ( isset($data['sure_no'])) $this->sure_no = string_duzelt($data['sure_no']);
		if ( isset($data['sure_adi'])) $this->sure_adi = string_duzelt($data['sure_adi']);
		if ( isset($data['ayet_meal'])) $this->ayet_meal = string_duzelt($data['ayet_meal']);
		if ( isset($data['ek_not'])) $this->ek_not = string_duzelt($data['ek_not']);*/
		
		if ( isset($data['ayet_id']) ) $this->ayet_id = (int) $data['ayet_id'];
		if ( isset($data['tarih']) )$this->tarih = (int) $data['tarih'];
		if ( isset($data['sure_no'])) $this->sure_no = $data['sure_no'];
		if ( isset($data['sure_adi'])) $this->sure_adi = $data['sure_adi'];
		if ( isset($data['ayet_meal'])) $this->ayet_meal = $data['ayet_meal'];
		if ( isset($data['ek_not'])) $this->ek_not = $data['ek_not'];
	}
	
	public function setValues( $data = array() )
	{
		$this->__construct($data);
	}
	
	public static function getById( $ayet_id )
	{
		$bag = baglan();
		$query = "SELECT * FROM ayet WHERE ayet_id = '$ayet_id'";
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "MYSQL error at Ayet::getById : " . mysqli_error($bag) );

		if( mysqli_num_rows($result) > 0 )
		{
			$data = mysqli_fetch_array($result);
			return new Ayet($data);
		}
		else rapor(FALSE, "$ayet_id ID'li ayet bulunamadı.");
	}
	
	public static function getList( $limit = "LIMIT 5", $order = "tarih DESC" )
	{
		$bag = baglan();
		
		//if ORDER BY command is not empty
		if($order != "") $query = "SELECT SQL_CALC_FOUND_ROWS * FROM ayet ORDER BY " . $order . " " . $limit . "";
		else $query = "SELECT SQL_CALC_FOUND_ROWS * FROM ayet " . $limit . "";
		
		//FOR DEBUG: rapor(FALSE, "query: " . $query); 
		
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "Error at Ayet::getList :" . mysqli_error($bag) );
		
		$list = array();
		
		while ( $data = mysqli_fetch_array($result) )
		{
			$list[] = new Ayet($data);
		}

		
		$num = getLastResultNum($bag);
		self::$last_result_num = $num;
	
		return $list;
	}
	
	public static function getTotalAyetNumber()
	{
		$bag = baglan();
		
		$query = "SELECT COUNT(*) FROM ayet";
		$result = mysqli_query($bag, $query);
		
		if (!$result) rapor(FALSE, "MYSQL error at getTotalAyetNumber : " . mysqli_error($bag) );
		
		$num = mysqli_fetch_array($result, MYSQLI_NUM);
		return $num[0];
	}
	
	public static function getGununAyeti()
	{
		$bag = baglan();
		
		$query = "SELECT * FROM gunun_ayeti";
		$result = mysqli_query($bag, $query);
		$result = mysqli_fetch_array($result);
		
		$ulasilan_sayi = $result['ulasilan_sayi'];
		$duzenlenen_tarih = date("z", $result['duzenlenen_tarih']);
		$ayet_sayisi = self::getTotalAyetNumber();
		$bugun = date("z" ,time() ); //z yılın günleridir (0'dan 360'a)
		
		if($duzenlenen_tarih != $bugun)
		{
			if($ulasilan_sayi > $ayet_sayisi - 1)
			{
				$ulasilan_sayi = 0;
				$query = "UPDATE gunun_ayeti SET ulasilan_sayi = $ulasilan_sayi, duzenlenen_tarih = " . time();
				$result = mysqli_query($bag, $query);
				
				if(!$result) rapor(FAlSE, "Günün ayeti başarısız");
			}
			else
			{
				$ulasilan_sayi++;
				$query = "UPDATE gunun_ayeti SET ulasilan_sayi = $ulasilan_sayi, duzenlenen_tarih = " . time();
				$result = mysqli_query($bag, $query);
				
				if(!$result) rapor(FAlSE, "Günün ayeti başarısız");
			}
		}
		
		$list = self::getList("LIMIT $ulasilan_sayi, 1");
		
		return $list[0];
	}
	
	
	
	public static function getAutoAyetMeal($sure_index, $sure_no, $meal_secimi = 'elmali')
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
							$meal .= $html->find('tr[id=tr' . $meal_index . ']', 0)->find('td', 1)->plaintext . "\r\n"; //tr15'in ikinci sütunu(meal kısmı)nın düz text hali
						 }
						 else return "Ayet bulunamadı"; //eğer sayfa bulunamazsa tüm meali komple yoket
					}
					else return "Ayet bulunamadı"; //eğer sayfa bulunamazsa tüm meali komple yoket
				}
				
				return substr($meal, 0, -4);; //loop'tan başarıyla çıkarsa meali return et
			}
			else return "Ayet bulunamadı";
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
	
	/*public function insert() 
	{
 
		// Does the Article object already have an ID?
		if ( $this->ayet_id != null ) rapor(FALSE, "Ayet Objenin id'si var");
	 
		// Insert the Article
		$conn = new PDO( "mysql:host=localhost;dbname=test", DB_USERNAME, DB_PASSWORD );
		$sql = "INSERT INTO ayet (ayet_id, tarih, sure_no, sure_adi, ayet_meal, ek_not ) VALUES ( NULL, :tarih, :sure_no, :sure_adi, :ayet_meal, :ek_not )";
		$st = $conn->prepare ( $sql );
		
		$sure_no = string_duzelt($this->sure_no);
		$sure_adi = string_duzelt($this->sure_adi);
		$ayet_meal = string_duzelt($this->ayet_meal);
		$ek_not = string_duzelt($this->ek_not);
		
		$st->bindValue( ":tarih", $this->tarih, PDO::PARAM_INT );
		$st->bindValue( ":sure_no", $sure_no, PDO::PARAM_STR );
		$st->bindValue( ":sure_adi", $sure_adi, PDO::PARAM_STR );
		$st->bindValue( ":ayet_meal", $ayet_meal, PDO::PARAM_STR );
		$st->bindValue( ":ek_not", $ek_not, PDO::PARAM_STR );
		
		$st->execute();
		$id = $conn->lastInsertId();
		$conn = null;
		 
		return $id;
	}*/
	
	public function insert()
	{
		$bag = baglan();
		
		if ( $this->ayet_id != null ) rapor(FALSE, "Ayet Objenin id'si var");
		
		$sure_no = string_duzelt($this->sure_no);
		$sure_adi = string_duzelt($this->sure_adi);
		$ayet_meal = string_duzelt($this->ayet_meal);
		$ek_not = string_duzelt($this->ek_not);
		
		$query = "INSERT INTO ayet (ayet_id, tarih, sure_no, sure_adi, ayet_meal, ek_not ) VALUES (NULL, $this->tarih, '$sure_no', '$sure_adi', '$ayet_meal', '$ek_not')";
		
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "Ayet Insert başarısız");
		
		$this->ayet_id = mysqli_insert_id($bag);
		return $this->ayet_id;
	}
	
	public function equals($ayet)
	{
		return $this->ayet_id == $ayet->ayet_id;
	}
	
	public function update()
	{
		$bag = baglan();
		
		if ( $this->ayet_id != null)
		{
			$sure_no = string_duzelt($this->sure_no);
			$sure_adi = string_duzelt($this->sure_adi);
			$ayet_meal = string_duzelt($this->ayet_meal);
			$ek_not = string_duzelt($this->ek_not);

			$query = "UPDATE ayet SET tarih = $this->tarih, sure_no = '$sure_no', sure_adi = '$sure_adi', ayet_meal = '$ayet_meal', ek_not = '$ek_not' " . "WHERE ayet_id = $this->ayet_id";
			
			$result = mysqli_query($bag,$query);
			
			if(!$result) rapor(FALSE, "Update başarısız");
		}
	}
	
	public function delete()
	{
		$bag = baglan();
		
		if ( $this->ayet_id != null)
		{
			//delete from ayet
			$query = "DELETE FROM ayet WHERE ayet_id = $this->ayet_id";
			$result = mysqli_query($bag,$query);
			if(!$result) rapor(FALSE, "Delete başarısız");
		
			//delete from ayet_tag
			$query = "DELETE FROM ayet_tag WHERE ayet_id = $this->ayet_id";
			$result = mysqli_query($bag,$query);
			if(!$result) rapor(FALSE, "Delete başarısız");
		}
	}
	
	
}

?>
