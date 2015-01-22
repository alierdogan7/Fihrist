<?php

require_once("functions.php");

class Tag {

	public $tag_id = null;
	public $tarih = null;
	public $tag_adi = null;
	public static $last_result_num = null;
	
	public function __construct($data)
	{
		if(isset($data['tag_id'])) $this->tag_id = $data['tag_id'];
		if(isset($data['tarih'])) $this->tarih = $data['tarih'];
		if(isset($data['tag_adi'])) $this->tag_adi = $data['tag_adi'];
	}
	
	public function setValues($data)
	{
		$this->__construct($data);
	}
	
	public static function getById( $tag_id)
	{
		$bag = baglan();
		$query = "SELECT * FROM tag WHERE tag_id = '$tag_id'";
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "MYSQL error at Tag::getById : " . mysqli_error($bag) );
		
		$data = mysqli_fetch_array($result);
		
		return new Tag($data);
	}
	
	public static function getList( $limit = "LIMIT 20", $order = "tarih DESC" )
	{
		$bag = baglan();
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tag ORDER BY " . $order . " " . $limit;
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "Errot at Tag::getList : " . mysqli_error($bag) );
		
		$list = array();
		
		while ( $data = mysqli_fetch_array($result) )
		{
			$list[] = new Tag($data);
		}
	
		$num = getLastResultNum($bag);
		self::$last_result_num = $num;
		
		return $list;
	}
	
	public static function getTotalTagNumber()
	{
		$bag = baglan();
		
		$query = "SELECT COUNT(*) FROM tag";
		$result = mysqli_query($bag, $query);
		
		if (!$result) rapor(FALSE, "Errot at getTotalTagNumber : " . mysqli_error($bag) );
		
		$num = mysqli_fetch_array($result, MYSQLI_NUM);
		return $num[0];
	}
	
	public function insert()
	{
		$bag = baglan();
		
		if ( $this->tag_id != null ) rapor(FALSE, "Tag Objenin id'si var");
		
		$tag_adi = string_duzelt($this->tag_adi);
		$query = "INSERT INTO tag (tag_id, tarih, tag_adi ) VALUES (NULL, $this->tarih, '$tag_adi')";
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "Tag Insert başarısız");
		
		$this->tag_id = mysqli_insert_id($bag);
		return $this->tag_id;
	}

	public function update()
	{
		$bag = baglan();
		
		if ( $this->tag_id != null)
		{
			$tag_adi = string_duzelt($this->tag_adi);
			$query = "UPDATE tag SET tarih = " . time() . ", tag_adi = '$tag_adi' " . "WHERE tag_id = $this->tag_id";
			
			$result = mysqli_query($bag,$query);
			
			if(!$result) rapor(FALSE, "Tag Update başarısız");
		}
	}
	
	public function equals($tag)
	{
		return $this->tag_id == $tag->tag_id;
	}
	
	public function delete()
	{
		$bag = baglan();
		
		if ( $this->tag_id != null)
		{
			//delete tag
			$query = "DELETE FROM tag WHERE tag_id = $this->tag_id";
			$result = mysqli_query($bag,$query);
			if(!$result) rapor(FALSE, "Tag Delete başarısız");
			
			//delete ayet_tag
			$query = "DELETE FROM ayet_tag WHERE tag_id = $this->tag_id";
			$result = mysqli_query($bag,$query);
			if(!$result) rapor(FALSE, "Tag Delete başarısız");
		}
	}
}

?>