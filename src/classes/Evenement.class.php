<?php

class Evenement {
	public $id;
	public $date;
	public $lieu;
	public $nbParticipants;
	public $nbParticipantsMax;
	
	private static $tableName="EVENEMENT";
	
	public function __construct($evenement = false)
	{
		if($evenement != false)
			$this->fetch($evenement);
	}
	
	public function fetch($result)
	{
		$this->id = $result['id_evt'];
		$this->date = $result['date_evt'];
		$this->lieu = $result['lieu'];
		$this->nbParticipantsMax = $result['nbparticipants'];
		Database::query("SELECT COUNT(*)
			FROM EVENEMENT, PARTICIPE
			WHERE EVENEMENT.id_evt = PARTICIPE.id_evt
			AND EVENEMENT.id_evt = ".$this->id);
		$res=Database::fetch();
		$this->nbParticipants=$res['COUNT(*)'];
	}

	public static function get($i)
	{
		$query = "select * from ".Evenement::$tableName." where id_eleve=$i";
		$results = Database::query($query);
		return $results;
	}
	
	public static function getListe() 
	{

		$query = "select * from ".Evenement::$tableName;
		$results = Database::query($query);
		return $results;
	}
	
	public function getFromDatabase($i)
	{
		$query = "select * from ".Evenement::$tableName." where id_evt=$i";
		Database::query($query,true);
		$result = Database::fetch();
		$this->fetch($result);

	}
	
	public function deleteFromDatabase()
	{
		$query = "DELETE FROM ".Evenement::$tableName." WHERE id_evt=".$this->id;
		return Database::query($query);
	}
	
	
	public function addToDatabase()
	{
		if($this->date=="" || $this->lieu=="")
		{
			Core::addDebug("Il manque des arguments");
			return false;
		}
		$query="insert into ".Evenement::$tableName." (lieu, date_evt, nbparticipants) values ('".$this->lieu."', '".$this->date."', '".$this->nbParticipantsMax."')";

		return Database::query($query, true);
	}

	public function modifyDatabase()
	{
		if($this->date=="" || $this->lieu=="")
		{
			Core::addDebug("Il manque des arguments");
			return false;
		}
		$query="UPDATE ".Evenement::$tableName." SET lieu='".$this->lieu."', date_evt='".$this->date."', nbparticipants='".$this->nbParticipantsMax."' WHERE id_evt=".$this->id;
		
		return Database::query($query, true);
	}
}
?>
