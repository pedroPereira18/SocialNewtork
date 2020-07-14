
<?php
include('./classes/DB.php');
include('./classes/cookie_login.php');


if (Login::IsLoggedIn()) 
{
  $userid=Login::IsLoggedIn();

}
else
{
	die(header("Location: /index.html"));
   
}


if (isset($_POST['uploadprofileimg'])) 
{
	Image::uploadImage('profileimg',"UPDATE users SET profileimg =:profileimg WHERE id=:userid",array(':userid'=>$userid));
}
?>



<h1> My account </h1>

<form action="my_account.php" method="post" enctype="multipart/form-data">
	upload a profile image: 
		<input type="file" name="profileimg">
		<input type="submit" name="uploadprofileimg" value="Upload image">

</form>