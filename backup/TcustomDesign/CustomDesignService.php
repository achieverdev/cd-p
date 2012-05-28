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
				$fetchObj['color_id']					 = $row->color_ifd;
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
		$query 			= "SELECT * FROM tbl_categories";
		$result 		= (mysql_query($query)) or die ('getProductCategrories->query problem'.mysql_error()) ;	
		
		$recordObjArr   = array();
		while ($row 	= mysql_fetch_object($result)) 
		{	
			$fetchObj['id']						 	= $row->id;
			$fetchObj['name'] 			 			= $row->name;
			$fetchObj['description'] 			 	= $row->description;							
			// sub  categories details
			$subcategories_query  	= "SELECT * FROM tbl_subcategories WHERE category_id=$row->id";		
			$subcategories_result 	= mysql_query($subcategories_query) or die ('getProductCategrories->subcategories_query'.mysql_error()) ;				
			$sbucategores_record_arr = array();
			while ($subcategories_row 	= mysql_fetch_object($subcategories_result)) 
			{				
				$subcategoriesItem['subcategories_id']		= $subcategories_row->id;	
				$subcategoriesItem['subcategories_name']	= $subcategories_row->name;
				$sbucategores_record_arr [] 			= $subcategoriesItem;			
			}
			mysql_free_result($subcategories_result);
			
			$fetchObj['subcategories_arr']				= 	$sbucategores_record_arr;	
			$recordObjArr [] 				 	 		= $fetchObj;				
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
			$colorcode_query  						 = "SELECT code FROM tbl_color WHERE id=$row->color_id";		
			$colorcode_result = (mysql_query($colorcode_query)) or die ('getProdcutColorOptions->colorcode_query'.mysql_error()) ;				
			$colorcode_row 	  = mysql_fetch_object($colorcode_result);
			$fetchObj['color_code']					 = $colorcode_row->code;	
							
			$recordObjArr [] 				 	 	 = $fetchObj;				
		}	
		mysql_free_result($result);
				 
	   return $recordObjArr;	
	}
	
	public function getProductItems($param)
	{
		$this->connect();		
		$query = "SELECT * FROM tbl_product WHERE subcategories_id=$param";
		$result = (mysql_query($query)) or die ('getProductItems->query problem'.mysql_error()) ;	
		
		$recordObjArr = array();
	        while ($row = mysql_fetch_object($result)) 
			{	
				$fetchObj['id']						 	 = $row->id;
				$fetchObj['product_name'] 				 = $row->name;
				
				$colorvariant_query 			= "SELECT * FROM tbl_product_variant WHERE product_id= $row->id";
				$colorvariant_result 			= (mysql_query($colorvariant_query)) or die ('getProductItems->colorvariant_query problem'.mysql_error()) ;
				$colorvariant_objArr			= array();
				while ($colorvariant_row 	= mysql_fetch_object($colorvariant_result)) 
				{
					$colorvariantObj['front_image'] 				 = 'assets/images/product/' 
																		.$row->id .'/' 
																		.$colorvariant_row->color_id .'/'
																		.$colorvariant_row->front_image;
					$colorvariantObj['back_image'] 				 	 = 'assets/images/product/' 
																		.$row->id .'/' 
																		.$colorvariant_row->color_id .'/'
																		.$colorvariant_row->back_image;
																		
					$colorvariantObj['subcategories_id']			 = $colorvariant_row->subcategories_id;
					$colorvariantObj['size_id']					 	 = $colorvariant_row->size_id;
					$colorvariantObj['color_id']					 = $colorvariant_row->color_id;
					$colorvariantObj['id']					 		 = $colorvariant_row->id;
					
					$colorcode_query  = "SELECT code FROM tbl_color WHERE id=$colorvariant_row->color_id";		
					$colorcode_result = (mysql_query($colorcode_query)) or die ('getProductItems->colorcode_query'.mysql_error()) ;				
					$colorcode_row 	  = mysql_fetch_object($colorcode_result);
					$colorvariantObj['color_code']					 = $colorcode_row->code;
					$colorvariant_objArr []							 = $colorvariantObj;					
				}	
				$fetchObj['iteminfo']								 = $colorvariant_objArr;			
				$recordObjArr [] 				 	 	 			 = $fetchObj;				
       		}
	        
			 mysql_free_result($result);
				 
	    return $recordObjArr;	
	}
	
	/*
		function : getArtImages() - images of arts  
	*/
	public function getArtImages()
	{
		$this->connect();		
		$query 			= "SELECT * FROM tbl_artImages";
		$result 		= (mysql_query($query)) or die ('getArtImages->query problem'.mysql_error()) ;	
		
		$recordObjArr   = array();
		while ($row 	= mysql_fetch_object($result)) 
		{	
			if( $row->type == '0')
			{
				$fetchObj['id']						 	 = $row->id;
				$fetchObj['name']						 = $row->name;
				$fetchObj['path']						 = 'assets/images/arrow/'.$row->path;
								
				$recordObjArr [] 				 	 	 = $fetchObj;				
			}
		}	
		mysql_free_result($result);
				 
	   return $recordObjArr;	
	}
	
	public function insertUploadImageTodb($pathname)
	{		
		$this->connect();		
		$query 			= "INSERT INTO tbl_artImages(name,path,type) VALUES ('arrow','$pathname','1')";
		$result 		= (mysql_query($query)) or die ('upload->query problem'.mysql_error()) ;		
		$returnId 		= mysql_insert_id();		
		
		$fetchObj['id']		= $returnId;
		$fetchObj['name']	= 'arrow';
		$fetchObj['path']	= $pathname;
		$fetchObj['type']	= '1';	

		return $fetchObj;		
	}
	
    public function testMessage($param)
	{
		$this->connect();
        return $param .' =>From php'.$this->dataconfigObj->db;
    }
}
?>
