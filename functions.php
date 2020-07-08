<?php
   
    session_start();
    //  $_SESSION["favcolor"] = "green";
    $link = mysqli_connect("shareddb-v.hosting.stackcp.net","twitter-313437acee","Saniya29","twitter-313437acee");
  
    if(mysqli_connect_error())
    {
        print_r(mysqli_connect_error());
        exit();
    }
    
    
    if ($_GET['function'] == "logout") {
        
        session_unset();
        
    }
       function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }
    function displaytweets($type)
    {
        global $link;
        if(!$_SESSION['id'])
        {
            
            echo(" <div class='alert alert-danger'>PLEASE LOGIN OR SIGNUP TO EXPLORE</div>");
            echo("<div class='loginpl tweet '>Hey!! SANIYA HERE,<br>
                Here I am with a clone of Twitter (A Basic Version) which allows you to
                tweet , view tweets, search tweets, follow ,unfollow and also view public profiles.<br>
                Do signup and give it a try.</br>
                Egerly Waiting for your Feedback.<a href='https://docs.google.com/forms/d/e/1FAIpQLSdZzDGZCwh5r_qYttyFKWMvhiWWF2jHwJULnPQftLfAR5MiNw/viewform?usp=sf_link'>Click here for feedback form</a><br>
                THANKYOU!</div>");
                
        }
    else
    {
       if($type == "public")
       {
           $whereclause = "";
              // echo("SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10");
           $query = "SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10";
           $result = mysqli_query($link,$query);
           if(mysqli_num_rows($result) == 0)
           {
               echo " No tweets to display";
           }
           else
           {
               while($row = mysqli_fetch_assoc($result))
               {
                   
                   $userquery="SELECT * FROM `users` where `id` = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
                  
                   $userqueryresult = mysqli_query($link,$userquery);
                    $user = mysqli_fetch_assoc($userqueryresult);
                    // print_r($user);
                    echo("<div class='tweet'><p>".$user['firstname']." ".$user['lastname']."  <span class='time'> ".time_since(time()-strtotime($row['datetime']))." ago </span></p>");
                    echo("<p>".$row['tweet']."</p>");
                    echo('<p><a href="" class="togglefollow" data-userid="'.$user['id'].'">');
                        $isFollowingQuery = "SELECT * FROM follow WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                        $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
                        if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                
                            echo "Unfollow";
                
                        } else {
                    
                            echo "Follow";
                
                    }
                    echo('</a></p></div>');
               }
           }
       }
      else if($type == "isfollowing")
       {
          $isfollowing = "SELECT * FROM `follow` WHERE `follower`=".mysqli_real_escape_string($link,$_SESSION['id']).";";
          $isfollowingresult = mysqli_query($link,$isfollowing);
           $whereclause = "";
        //   echo(mysqli_num_rows($isfollowingresult));
           if(mysqli_num_rows($isfollowingresult) == 0)
           {
               echo("<p> YOUR TIMELINE IS EMPTY FOLLOW PEOPLE TO SEE TWEETS</p>");
           }
           else{
            //   echo($row = mysqli_fetch_assoc($result));
               
                while ($row = mysqli_fetch_assoc($isfollowingresult)) {
                        // echo($whereclause);
                       if ($whereclause == "") $whereclause = " WHERE ";
                        else $whereclause.= " OR ";
                        $whereclause.= " userid = ".$row['following']." ";
                        
                }
                        // echo("SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10");
                        $query = "SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10";
                       $result = mysqli_query($link,$query);
                        while($row=mysqli_fetch_assoc($result))
                        {
                           $userquery="SELECT * FROM `users` where `id` = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
                  
                           $userqueryresult = mysqli_query($link,$userquery);
                            $user = mysqli_fetch_assoc($userqueryresult);
                    // print_r($user);
               
                                echo("<div class='tweet'><p>".$user['firstname']." ".$user['lastname']."  <span class='time'> ".time_since(time()-strtotime($row['datetime']))." ago </span></p>");
                                echo("<p>".$row['tweet']."</p>");
                                echo("<p><a href='' class='togglefollow' data-userid='".$user['id']."'>");
                                      $isFollowingQuery = "SELECT * FROM follow WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                        $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
                        if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                                echo "Unfollow";
                        }
                                 echo('</a></p></div>');
                
                         
                   
                        }
                    }
                   
               }
               else if($type == "yourtweets")
       {
           $whereclause = " WHERE `userid` =".$_SESSION['id']." ";
              // echo("SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10");
           $query = "SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC ";
           $result = mysqli_query($link,$query);
           if(mysqli_num_rows($result) == 0)
           {
               echo " No tweets to display";
           }
           else
           {
               while($row = mysqli_fetch_assoc($result))
               {
                   
                   $userquery="SELECT * FROM `users` where `id` = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
                  
                   $userqueryresult = mysqli_query($link,$userquery);
                    $user = mysqli_fetch_assoc($userqueryresult);
                    // print_r($user);
                    echo("<div class='tweet'><p>".$user['firstname']." ".$user['lastname']."  <span class='time'> ".time_since(time()-strtotime($row['datetime']))." ago </span></p>");
                    echo("<p>".$row['tweet']."</p>");
                    echo('<p><button type="button" class="btn btn-danger" id="deletetweet" data-tweetid="'.$row['id'].'">Delete</button></p>');
                    echo('</div>');
               }
           }
       }
       else if(is_numeric($type))
      {    //echo($type);
           $query= "SELECT * FROM `users` WHERE `id` =".mysqli_real_escape_string($link,$type)." LIMIT 1";
          $result = mysqli_query($link,$query);
            $row = mysqli_fetch_assoc($result);
            echo("<p>".$row['firstname']." ".$row['lastname']."<br>");
            echo("".$row['username']."</p>");
             echo('<p><a href=""  id="togglefollow" data-userid="'.$type.'">');
                        $isFollowingQuery = "SELECT * FROM follow WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = ". mysqli_real_escape_string($link, $type)." LIMIT 1";
                        // $isFollowingQuery="";
                        $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
                        // $row=mysqli_fetch_assoc($$isFollowingQueryResult);
                        if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                            
                            echo "Unfollow";
                            
                
                        } else {
                    
                            echo "Follow";
                               
                
                    }
                    echo"</a></p>";
            $whereclause = " WHERE `userid` =".$row['id']." ";
              // echo("SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10");
           $query = "SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC ";
           $result = mysqli_query($link,$query);
           if(mysqli_num_rows($result) == 0)
           {
               echo " No tweets to display";
           }
           else
           {
               while($row = mysqli_fetch_assoc($result))
               {
                   
                   $userquery="SELECT * FROM `users` where `id` = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
                  
                   $userqueryresult = mysqli_query($link,$userquery);
                    $user = mysqli_fetch_assoc($userqueryresult);
                    // print_r($user);
                    echo("<div class='tweet'><p>".$user['firstname']." ".$user['lastname']."  <span class='time'> ".time_since(time()-strtotime($row['datetime']))." ago </span></p>");
                    echo("<p>".$row['tweet']."</p>");
                    echo('</div>');
               }
           }
            
            
       }
       
       else if($type == "search")
       {
           $whereclause = " WHERE tweet LIKE '%".mysqli_real_escape_string($link,$_GET['q'])."%' ";
              // echo("SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10");
           $query = "SELECT * FROM `tweets`".$whereclause."ORDER BY `datetime` DESC LIMIT 10";
           $result = mysqli_query($link,$query);
           if(!($_GET['q']))
           {
               echo " No match found";
           }
           else
           {
              echo"Showing results for ".mysqli_real_escape_string($link,$_GET['q']);
               while($row = mysqli_fetch_assoc($result))
               {
                   
                   $userquery="SELECT * FROM `users` where `id` = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
                  
                   $userqueryresult = mysqli_query($link,$userquery);
                    $user = mysqli_fetch_assoc($userqueryresult);
                    // print_r($user);
                    echo("<div class='tweet'><p>".$user['firstname']." ".$user['lastname']."  <span class='time'> ".time_since(time()-strtotime($row['datetime']))." ago </span></p>");
                    echo("<p>".$row['tweet']."</p>");
                    echo('<p><a href="" class="togglefollow" data-userid="'.$user['id'].'">');
                         $isFollowingQuery = "SELECT * FROM follow WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                        $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
                        if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                
                            echo "Unfollow";
                
                        } else {
                    
                            echo "Follow";
                
                    }
                        
                         echo('</a></p></div>');
       
           }
           }
       }
                         
    }
    
  
    }
  function displaysearch()
  {
      echo'      
   <form class="form " id="displaysearch" >
  <div class="form-group">
    <input type="hidden" name="page" value="search">
 
<input type="text" name="q" class="form-control" id="search" placeholder="Search">
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
 
  <p>
</form><hr class="grey">';
  }
  
  
  function posttweet()
  {
      if($_SESSION['id'])
      {
          echo'<div class="">
            <div class="alert alert-danger" id="failtweet"></div>
            <div class="alert alert-success" id="successtweet"></div>
            <div>
            
                <div class="mb-3">
                  <textarea class="form-control " id="tweetcontent" placeholder="Enter the tweet you want to post"></textarea>
              </div>
                <div class="col-auto">
              <button id="posttweet" class="btn btn-primary mb-2">Submit</button>
            </div></div>
            </div>';
      }
  }
  function displayprofiles()
  {
      global $link;
                // echo "SELECT * FROM `users` WHERE id !=".mysqli_real_escape_string($link,$_SESSION['id']);
       
      if($_SESSION['id'])
      { 
            $query = "SELECT * FROM `users` WHERE id !=".mysqli_real_escape_string($link,$_SESSION['id']);
           $result = mysqli_query($link,$query);
           while($row=mysqli_fetch_assoc($result))
           {
               echo'<p><a href="?page=publicprofile&userid='.mysqli_real_escape_string($link,$row['id']).'">'.$row['username'].'</a></p>';
           }
  
      }
      else{
          echo("you need to login or signup to view public profiles");
      }
      
      }
      
      
      function login()
      {
          echo("hie");
      }
    ?>