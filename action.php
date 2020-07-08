<?php 
 	
 	include("functions.php");
 	    

 	$error="";
 
 	if($_GET['action'] == "login")
 	{
        
 		if(!($_POST['email_']))
 		{
 		    $error .="Email required  \n";
 		}
 		
 	    	if(!($_POST['password_']))
 		{
 		    $error .="Password required \n";
 		}
 		
 		if($_POST['email_'])
 		{
 		        if (!(filter_var($_POST['email_'], FILTER_VALIDATE_EMAIL))) {
                  $error .="Enter a valid email id \n";

 	    }   

    }
    	if($_POST['password_'])
 		{
 		        if ((strlen($_POST['password_']))<8 || (strlen($_POST['password_']))>16) {
                  $error .="Invalid Passowrd \n ";

 	    }   
 	    

    }
    echo("$error");
}
 	if($_GET['action'] == "signup")
 	{

 		
 		if(!($_POST['firstname_']))
 		{
 		    $error .="Firstname required \n";
 		}
 			if(!($_POST['lastname_']))
 		{
 		    $error .="Lastname required \n";
 		}
 	     		if(!($_POST['email_']))
 		{
 		    $error .="Email Required \n";
 		}
 		
 	    	if(!($_POST['password_']))
 		{
 		    $error .="Password Required \n";
 		}
 		
 		if($_POST['email_'])
 		{
 		        if (!(filter_var($_POST['email_'], FILTER_VALIDATE_EMAIL))) {
                  $error .="Enter a valid email id \n";

 	    }   

    }
    	if($_POST['password_'])
 		{
 		        if ((strlen($_POST['password_']))<8 || (strlen($_POST['password_']))>16) {
                  $error .=" PASSWORD should contain minimum 8 and maximum 16 digits \n";

 	    }   
 	    

    }
    
echo($error);
    
 }
 if(!($error))
 {
 	if($_GET['action']=="signup")
 	{       
 	    
 	    
 	    
 	       $query = "SELECT * FROM `users` WHERE `username` = '".mysqli_real_escape_string($link,$_POST['email_'])."' LIMIT 1";
 	       $result = mysqli_query($link,$query);
 	       if(mysqli_num_rows($result)>0)
 	       {
 	           $error = "Email is already taken \n";
 	       }
 	        else
 	        {
 	                  $query = "INSERT INTO `users` (`firstname`, `lastname`, `username`, `password`) VALUES ('".mysqli_real_escape_string($link,$_POST['firstname_'])."', '".mysqli_real_escape_string($link,$_POST['lastname_'])."', '".mysqli_real_escape_string($link,$_POST['email_'])."', '".mysqli_real_escape_string($link,$_POST['password_'])."')";
                  if(mysqli_query($link,$query))
                {
                    // echo("done");
                }
                else{
                        $error = "could not signup Please try again later \n";
                    }
        
 	      }
 	  }
 	  echo($error);
 }	
 	
if(!($error))
 {
 	if($_GET['action']=="login")
 	{       
 	   
 	    
 	    
 	       $query = "SELECT * FROM `users` WHERE `username` = '".mysqli_real_escape_string($link,$_POST['email_'])."' LIMIT 1";
 	       $result = mysqli_query($link,$query);
 	       $row = mysqli_fetch_assoc($result);
 	       if($_POST['password_'] == $row['password'])
 	       {
 	                	  if(session_status() !== PHP_SESSION_ACTIVE)
            {
                     $error .= "session not working";
         	} 
         	else{
 	                $_SESSION["id"] = $row['id'];   
 	               
         	}
 	       }
 	     else{
 	         $error = "Email/Login Invalid";
 	     }   
 	
 	echo($error);
 	
 	}
 }
 
 
 if($_GET['action']=='togglefollow')
 {
         $query = "SELECT * FROM `follow` WHERE `follower`='".mysqli_real_escape_string($link,$_SESSION['id'])."' AND `following`='".mysqli_real_escape_string($link,$_POST['userid'])."' LIMIT 1";
 	       $result = mysqli_query($link,$query);
 	       $row=mysqli_fetch_assoc($result);
 	       if(mysqli_num_rows($result) > 0)
 	       {
 	           
 	           $delete = "DELETE FROM `follow` WHERE `id` = ".$row['id']." LIMIT 1";
 	           if(mysqli_query($link,$delete))
 	           {
 	               echo("2");
 	           }
 	       }
 	       else{
 	           $add = "INSERT INTO `follow` (`follower`, `following`) VALUES ('".mysqli_real_escape_string($link,$_SESSION['id'])."', '".mysqli_real_escape_string($link,$_POST['userid'])."'); ";
 	            if(mysqli_query($link,$add))
 	            {
 	                echo("1");
 	            }
 	           
 	       }
 }
 
 
 if($_GET['action'] == 'posttweet')
 {
     if(!($_POST['tweetcontent']))
     {
         echo("tweet is empty");
     }
     else if(strlen($_POST['tweetcontent'])>=140)
     {  
        echo(" Tweet should have maximum 140 characters"); 
     }
     else{      
         
                $addplus = str_replace("saniyaplus","+",$_POST['tweetcontent']);
                $addplus = str_replace("saniyaand","&", $addplus);
                $removed = mysqli_real_escape_string($link,$addplus);
               $add = "INSERT INTO `tweets` (`tweet`, `datetime`, `userid`) VALUES  ('".$removed."',NOW(),".mysqli_real_escape_string($link,$_SESSION['id'])."); ";
 	            if(mysqli_query($link,$add))
 	            {
 	                echo("1");
 	                
 	            }

     }
 }
 
 if($_GET['action'] == "deletetweet")
 
 {
     $query= "DELETE FROM `tweets` WHERE `tweets`.`id` = ".$_POST['tweetid'];
     if(mysqli_query($link,$query))
     {
         echo("1");
     }
 }
 
 
?>