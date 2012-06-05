<?php
class DataConfig
 {
	public $host; 
	public $username; 
	public $password; 
	public $db;
	
	public function __construct() 
	{
		$this->host								= "localhost"; 
		$this->username 						= "trainy"; 
		$this->password 						= "trainy"; 
		$this->db								= "db_tcustomdesign";		
	}
	
}

?>