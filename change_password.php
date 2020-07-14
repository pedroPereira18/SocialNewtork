<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php 
include('./classes/DB.php');
include('./classes/cookie_login.php');
$tokenisvalid = False;
if (Login::isLoggedIn()) 
{

	if (isset($_POST['changepassword'])) 
	{
		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];
		$newpassword2 = $_POST['newpassword2']; 
		$userid = Login::isLoggedIn();
		$hashedpassword = password_hash($newpassword, PASSWORD_DEFAULT);
		var_dump($userid);
		if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])) 
		{

				if ($newpassword == $newpassword2) 
				{
					DB_update::query_update('UPDATE users SET password=:newpassword WHERE id =:userid', array(':newpassword'=>$hashedpassword,':userid'=>$userid));
					echo "password changed";
				}
		}
			else{
				
				
			}

	}

else
{
	if (isset($_GET['token'])) {
		# code...
		$tokenisvalid = true;
	$token = $_GET['token'];	
	$user_id = DB::query('SELECT user_id FROM password_token WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
	if (isset($_POST['changepassword'])) 
	{
		
		$newpassword = $_POST['newpassword'];
		$newpassword2 = $_POST['newpassword2']; 
		
		$hashedpassword = password_hash($newpassword, PASSWORD_DEFAULT);
		

				if ($newpassword == $newpassword2) 
				{
					DB_update::query_update('UPDATE users SET password=:newpassword WHERE id =:userid', array(':newpassword'=>$hashedpassword,':userid'=>$userid));
					echo "password changed";
				}
		}
	}
	
}
}



//$2y$10$GJJN7p/Li7zYCoDy6VEyhuKgioPYNf5CNoxLZBDOa6yqlfyi5LSBK
//$2y$10$GJJN7p/Li7zYCoDy6VEyhuKgioPYNf5CNoxLZBDOa6yqlfyi5LSBK
?>



<<h1>Change your Password</h1>
<form action="<?php if (!$tokenisvalid) { echo 'change_password.php'; } else { echo 'change_password.php?token='.$token.''; } ?>" method="post">
        <?php if ($tokenisvalid) { echo '<input type="password" name="oldpassword" value="" placeholder="Current Password ..."><p />'; } ?>
        <input type="password" name="newpassword" value="" placeholder="New Password ..."><p />
        <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat Password ..."><p />
        <input type="submit" name="changepassword" value="Change Password">
</form>