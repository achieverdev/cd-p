<?php
include 'DataConfig.php';

class CustomDesignService
 {
	
	// Connection function for accessing the database 
	protected $dataconfigObj;
	
	protected function connect() 
	{ 		
		$this->dataconfigObj = new DataConfig(); 	
	   
		$connection = mysql_connect($this->dataconfigObj->host,$this->dataconfigObj->username,$this->dataconfigObj->password) or die ("Unable to connect to database.");
		$db = mysql_select_db($this->dataconfigObj->db) or die ("Unable to select database.");
	} 
	
	public function loginUser($dataObj)
	{
		$this->connect();
		
		$username = $dataObj->user;
		$password = $dataObj->password;		
		
		$query = "SELECT * FROM tbl_user WHERE name = '$username' AND password = '$password'";

		$result = mysql_fetch_array(mysql_query($query));		
		if(!$result)
		{	// not authoriezed
			return 'not correct';
		}
		else
		{ //sucess
			session_start();
			$_SESSION['user'] 				= 'viinfo';
			$returnDataObjArr 				= array();			
			$userInfo['name'] 				= $result['name'];
			$userInfo['id']					= $result['id'];					
			$returnDataObjArr [] 			= $userInfo;
			
			return $returnDataObjArr;
		}		
	}
	
	public function getUserName()
	{		
		session_start();
		$var =  $_SESSION['user'];
		return $var;		
	}
	
	public function getProdcutColorOptions($param)
	{		
		$this->connect();		
		$query = "SELECT * FROM tbl_product_variant WHERE product_id=$param";
		$result = (mysql_query($query)) or die ('getProdcutColorOptions->query problem'.mysql_error()) ;	
		
		$recordObjArr = array();
	        while ($row = mysql_fetch_object($result)) 
			{	
				$fetchObj['id']						 	 = $row->id;
				$fetchObj['product_id'] 				 = $row->product_id;
				$fetchObj['front_image'] 				 = 'assets/images/product/' .$row->product_id .'/' .$row->color_id .'/'.$row->front_image;
				$fetchObj['back_image'] 				 = 'assets/images/product/' .$row->product_id .'/' .$row->color_id .'/'.$row->back_image;
				$fetchObj['subcategories_id']			 = $row->subcategories_id;
				$fetchObj['size_id']					 = $row->size_id;
				$fetchObj['color_id']					 = $row->color_id;
				$colorcode_query  = "SELECT code FROM tbl_color WHERE id=$row->color_id";		
				$colorcode_result = (mysql_query($colorcode_query)) or die ('getProdcutColorOptions->colorcode_query'.mysql_error()) ;				
				$colorcode_row 	  = mysql_fetch_object($colorcode_result);
				$fetchObj['color_code']					 = $colorcode_row->code;			
							
				$recordObjArr [] 				 	 	 = $fetchObj;				
       		}
	        
			 mysql_free_result($result);
				 
	    return $recordObjArr;		
	}	
	
	public function getProductCategrories()
	{
		$this->connect();		
		$query 			= "SELECT * FROM tbl_subcategories";
		$result 		= (mysql_query($query)) or die ('getProductCategrories->query problem'.mysql_error()) ;	
		
		$recordObjArr   = array();
		while ($row 	= mysql_fetch_object($result)) 
		{	
			$fetchObj['id']						 	= $row->id;
			$fetchObj['name'] 			 			= $row->name;
			$fetchObj['category_id'] 			 	= $row->category_id;
			$fetchObj['description'] 			 	= $row->description;							
			// categories details
			$categories_query  	= "SELECT * FROM tbl_categories WHERE id=$row->category_id";		
			$categories_result 	= mysql_query($categories_query) or die ('getProductCategrories->categories_query'.mysql_error()) ;				
			$categories_row 	= mysql_fetch_object($categories_result);
			$fetchObj['category_name']				= $categories_row->name;			
						
			$recordObjArr [] 				 	 	= $fetchObj;				
		}	
		mysql_free_result($result);
				 
	   return $recordObjArr;				
	}
	
	public function getProductItem($param)
	{
		$this->connect();		
		$query 			= "SELECT * FROM tbl_product_variant WHERE id=$param";
		$result 		= (mysql_query($query)) or die ('getProdcutColorOptions->query problem'.mysql_error()) ;	
		
		$recordObjArr   = array();
		while ($row 	= mysql_fetch_object($result)) 
		{	
			$fetchObj['id']						 	 = $row->id;
			$fetchObj['product_id'] 				 = $row->product_id;
			$fetchObj['front_image'] 				 = 'assets/images/product/' .$row->product_id .'/' .$row->color_id .'/'.$row->front_image;
			$fetchObj['back_image'] 				 = 'assets/images/product/' .$row->product_id .'/' .$row->color_id .'/'.$row->back_image;
			$fetchObj['subcategories_id']			 = $row->subcategories_id;
			$fetchObj['size_id']					 = $row->size_id;
			$fetchObj['color_id']					 = $row->color_id;
			$colorcode_query  = "SELECT code FROM tbl_color WHERE id=$row->color_id";		
			$colorcode_result = (mysql_query($colorcode_query)) or die ('getProdcutColorOptions->colorcode_query'.mysql_error()) ;				
			$colorcode_row 	  = mysql_fetch_object($colorcode_result);
			$fetchObj['color_code']					 = $colorcode_row->code;	
							
			$recordObjArr [] 				 	 	 = $fetchObj;				
		}	
		mysql_free_result($result);
				 
	   return $recordObjArr;	
	}
	
    public function testMessage($param)
	{
		$this->connect();
        return $param .' =>From php'.$this->dataconfigObj->db;
    }
}
?>
