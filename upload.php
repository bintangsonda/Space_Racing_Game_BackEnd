<?php
if(isset($_FILES['photo']['name'])){
    if ($_FILES['photo']["size"] > 40000) {
      exit("File must be below 40kb");
    }
    if(strtolower(pathinfo("foto/".$_FILES['photo']['name'],PATHINFO_EXTENSION)) != "png") {
      exit("File must be png type");
    }
    
	$filename = $_FILES['photo']['name'];
	$location = 'foto/'.$filename;
	if(move_uploaded_file($_FILES['photo']['tmp_name'],$location)){
	    echo "Upload file succeed";
	}
}
?>