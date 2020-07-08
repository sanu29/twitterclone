<?php
include("functions.php");
include("view/header.php");
if($_GET['page']=="timeline")
{
    include("view/timeline.php");
}
else if($_GET['page']=="yourtweets")
{
    include("view/yourtweets.php");
}
else if($_GET['page']=="publicprofile")
{
    include("view/publicprofile.php");
}
else if($_GET['page']=="search")
{
    include("view/search.php");
}
else
{
    include("home.php");

}
include("view/footer.php");
?>