<?php

require_once("functions.php");

class AyetTag {

	public $ayet_id = null;
	public $tag_id = null;
	public static $last_result_num = null;
	
	public function __construct($data)
	{
		if(isset($data['tag_id'])) $this->tag_id = $data['tag_id'];
		if(isset($data['ayet_id'])) $this->ayet_id = $data['ayet_id'];
	}
	
	public function setValues($data)
	{
		$this->__construct($data);
	}
	
	public static function getAyetListByTag($tag_id, $sahip_oldugu_ayetler = TRUE, $limit = "LIMIT 1000", $order = "tarih DESC")
	{
		$bag = baglan();
		
		if($sahip_oldugu_ayetler)
		{
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM ayet, ayet_tag WHERE ayet.ayet_id = ayet_tag.ayet_id AND tag_id = '$tag_id' ORDER BY " . $order . " " . $limit ;
		}
		else
		{
			$query = "SELECT SQL_CALC_FOUND_ROWS ayet_id FROM ayet WHERE ayet_id NOT IN ( SELECT ayet_id FROM ayet_tag WHERE tag_id = '$tag_id' ) ORDER BY " . $order . " " . $limit;
		}
		
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "MYSQL error at getAyetListByTag: " . mysqli_error($bag) );
		
		$list = array();
		
		while( $data = mysqli_fetch_array($result, MYSQLI_NUM) )
		{
			$list[] = Ayet::getById($data[0]);
		}
		
		$num = getLastResultNum($bag);
		self::$last_result_num = $num;
		
		return $list;
	}

	public static function getListByAyet( $ayet_id )
	{
		$bag = baglan();
		$query = "SELECT * FROM ayet_tag WHERE ayet_id = '$ayet_id'";
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "getListByAyet " . mysqli_error($bag) );
		
		$list = array();
		
		while ( $data = mysqli_fetch_array($result) )
		{
			$list[] = new AyetTag($data);
		}
	
		return $list;
	}
	
	public static function getListByTag( $tag_id )
	{
		$bag = baglan();
		$query = "SELECT * FROM ayet_tag WHERE tag_id = '$tag_id'";
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "getListByTag " . mysqli_error($bag) );
		
		$list = array();
		
		while ( $data = mysqli_fetch_array($result) )
		{
			$list[] = new AyetTag($data);
		}
	
		return $list;
	}
	
	public static function getTagListByAyet( $ayet_id, $sahip_oldugu_tagler = TRUE, $limit = "LIMIT 1000", $order = "tarih DESC")
	{
		$bag = baglan();
		
		if($sahip_oldugu_tagler)
		{
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tag, ayet_tag WHERE tag.tag_id = ayet_tag.tag_id AND ayet_id = '$ayet_id' ORDER BY " . $order . " " . $limit;
		}
		else
		{
			$query = "SELECT SQL_CALC_FOUND_ROWS tag_id FROM tag WHERE tag_id NOT IN ( SELECT tag_id FROM ayet_tag WHERE ayet_id = '$ayet_id' ) ORDER BY " . $order . " " . $limit;
		}
		
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "MYSQL error at getTagListByAyet: " . mysqli_error($bag) );
		
		$list = array();
		
		while( $data = mysqli_fetch_array($result, MYSQLI_NUM) )
		{
			$list[] = Tag::getById($data[0]);
		}
		
		$num = getLastResultNum($bag);
		self::$last_result_num = $num;
		
		return $list;
	}
	
	/*public static function getTagListListByAyetList($ayet_list, $sahip_oldugu_tagler = TRUE, $limit = "LIMIT 1000", $order = "tarih DESC")
	{
		$tag_list_list = array();
		
		foreach($ayet_list as $ayet)
		{
			$tag_list_list[] = getTagListByAyet($ayet->ayet_id, $sahip_oldugu_tagler, $limit, $order);
		}
		
		return $tag_list_list;
	}
	
	public static function getAyetListListByTagList($tag_list, $sahip_oldugu_tagler = TRUE, $limit = "LIMIT 1000", $order = "tarih DESC")
	{
		$ayet_list_list = array();
		
		foreach($tag_list as $tag)
		{
			$ayet_list_list[] = getAyetListByTag($tag->tag_id, $sahip_oldugu_ayetler, $limit, $order);
		}
		
		return $ayet_list_list;
	}*/
	
	public static function getAyetNumberByTag($tag_id)
	{
		$bag = baglan();
		
		$query = "SELECT COUNT(*) FROM ayet_tag WHERE tag_id = '$tag_id'";
		$result = mysqli_query($bag, $query);
		
		if(!$result) rapor(FALSE, "getAyetNumberByTag " . mysqli_error($bag));
		
		$num = mysqli_fetch_array($result, MYSQLI_NUM);
		return $num[0];
	}
	
	public function insert()
	{
		$bag = baglan();
		
		//if ( $this->tag_id != null && $this->ayet_id != null) rapor(FALSE, "AyetTag Objenin id'si var");
		
		$query = "INSERT INTO ayet_tag (ayet_id, tag_id ) VALUES ( $this->ayet_id, $this->tag_id)";
		
		$result = mysqli_query($bag,$query);
		
		if (!$result) rapor(FALSE, "Insert başarısız");
	}
	
	public function delete()
	{
		$bag = baglan();
		
		if ( $this->tag_id != null && $this->ayet_id != null)
		{
			$query = "DELETE FROM ayet_tag WHERE tag_id = $this->tag_id AND ayet_id = $this->ayet_id";
			
			$result = mysqli_query($bag,$query);
			
			if(!$result) rapor(FALSE, "Delete başarısız");
		}
		else
		{
			rapor(FALSE, "Tag: $tag_id Ayet: $ayet_id");
		}
	}
}

?>