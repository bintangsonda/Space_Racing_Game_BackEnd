<?php
    session_start();
    if(!isset($_SESSION["userData"])) $_SESSION['userData']=array('username' => "", 'password' => "", 'pp' => "", 'score' => 0, 'nation'=>""); //store the data of the logged user in the server
    
    if(isset($_POST['status'])) $status=$_POST["status"]; //to run specific code based on the event
    $conn=mysqli_connect("localhost","id18025047_ssipmbs1234","Satria123&&&&","id18025047_ssipmbs123");

    function bubble_Sort($my_array)
    { 
        do
        {
            $swapped = false;
            for($i=0, $c = count($my_array) - 1; $i < $c; $i++)
            {
                if($my_array[$i]["score"] < $my_array[$i+1]["score"] )
                {
                    list($my_array[$i+1], $my_array[$i] ) =
                        array($my_array[$i], $my_array[$i + 1] );
                    $swapped = true;
                }
            }
        }
        while($swapped);
    return $my_array;
    }

    if($status == "registration") //to handle registration
    {
        if(isset($_POST['username'])) $username=$_POST["username"];
        if(isset($_POST['password'])) $password=$_POST["password"];
        if(isset($_POST['nation'])) $nation=$_POST["nation"];
        if(isset($_FILES['photo']['name'])) {
    	$filename = $_FILES['photo']['name'];
    	$location = 'pp/'.$filename;
    	move_uploaded_file($_FILES['photo']['tmp_name'],$location); //store the photo in the server
        }
        
        // if($conn)
        // {
        //     echo "connected";
        //     echo $username;
        //     echo $password;
        //     echo $location;
        // } else
        // {
        //     echo "not connected";
        // }
        
        $sql ="insert into mbs_final(username,password,pp,score,nation) VALUES ('$username','$password','$location','0','$nation')";
        // $res=mysqli_query($conn,$sql) or die(mysqli_connect_error());
        mysqli_query($conn,$sql) or die(mysqli_connect_error());
        echo "data has been inserted!";
    }
    
    if($status == "validate") //will run in login page to validate account
    {
        if(isset($_POST['username'])) $username=$_POST["username"];
        if(isset($_POST['password'])) $password=$_POST["password"];
        $userData=array('username' => "", 'password' => "", 'pp' => "", 'score' => 0,'nation'=>""); //store user data temporarily
        
        $sql ="select * from mbs_final where username='$username'"; //get all data from database with specified username
        $res = mysqli_query($conn,$sql) or die; //if there is no matching username, then exit from this php code
        while($row = mysqli_fetch_array($res)){
            $userData['username']=$row["username"];
            $userData['password']=$row["password"];
            $userData['pp']=$row["pp"];
            $userData['score']=$row["score"];
            $userData['nation']=$row["nation"];
        }
        if($password == $userData['password'])
        {
            $_SESSION['userData']=$userData;
            echo "y";
        }
        else echo "n";
        
    } 
    
   
    else if($status == "getdata")//will run in main page to pass data from database into leaderboard and user profile
    {
        $allData = array(); //store all data retrieved from the database
        $temp=array('username' => "", 'password' => "", 'pp' => "", 'score' => 0,'nation'=>"");
        $allDataQuery ="select * from mbs_final";
        $res = mysqli_query($conn,$allDataQuery);
        while($row = mysqli_fetch_array($res)){
            $temp['username']=$row["username"];
            $temp['pp']=$row["pp"];
            $temp['score']=$row["score"];
            $temp['nation']=$row["nation"];
            array_push($allData, $temp);
        }
        $allData = bubble_Sort($allData);
        $sendData = array($_SESSION['userData'],$allData);//send data of logged user and all users
        print_r(json_encode($sendData,JSON_UNESCAPED_SLASHES));
    } 
    
   
    else if($status == "savescore")//will run when the user achieved new highscore
    {
        $currentUser = $_SESSION['userData']['username'];
        if(isset($_POST['score'])) $newscore=$_POST["score"];
        
        $scoreQuery ="select score from mbs_final where username = '$currentUser'";
        $res = mysqli_query($conn,$scoreQuery);
        $row = mysqli_fetch_assoc($res);
        $oldScore = $row['score'];
        
        if($newscore > $oldScore){
        $_SESSION['userData']['score'] = $newscore;
        $sql2 ="UPDATE mbs_final SET score='$newscore' WHERE username='$currentUser'";
        $res2 = mysqli_query($conn,$sql2) or die;
        echo "New Highscore!!!";
        }else
        {
            echo "Your previous highscore is $oldScore.";
            echo " Keep Fighting!!!";
        }
        
    } 
    
    
    else if($status == "delete")//will run when the user delete the account
    {
        $currentUser = $_SESSION['userData']['username'];
        $sql3 ="DELETE FROM mbs_final WHERE username='$currentUser'";
        $res3 = mysqli_query($conn,$sql3) or die;
        echo "account deleted";
    }
    else if($status == "search")
    {
        if(isset($_POST['player'])) $player=$_POST["player"];
        $sql4 ="SELECT * FROM mbs_final WHERE username='$player'";
        $res4 = mysqli_query($conn,$sql4) or die;
        $row = mysqli_fetch_array($res4);
        print_r(json_encode($row,JSON_UNESCAPED_SLASHES));
    }
?>