
<?php
session_start();

   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
	  
	  $tmp = explode('.',$_FILES['image']['name']);
		$file_extension = end($tmp);


      $file_ext=strtolower($file_extension);
      
      $expensions= array("txt");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a TXT file.";
      }
   
      
      if(empty($errors)==true){
		  
		  $barcodequery = "";
		 $allbarcodes = file($file_tmp);//file in to an array
		 
		 foreach ($allbarcodes as $thisbarcode){
			 $barcodequery .= 'barcode = "' . trim($thisbarcode) . '" OR ';
		 }
		 
		 //remove last OR from query
		 $barcodequery = substr($barcodequery, 0, -3);
		 $barcodequery = str_replace(array("\r", "\n"), '', $barcodequery);
		 $_SESSION["uploadquery"] = $barcodequery;
		 
		 echo "<a href='barcodes.php'>" . count($allbarcodes) . " barcodes found. Go to analysis >> </a>";
		 
      }else{
         print_r($errors);
      }
   }
?>
<html>
   <body>
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input value="Upload file" type="submit"/>
      </form>
      
      
      

      
   </body>
</html>