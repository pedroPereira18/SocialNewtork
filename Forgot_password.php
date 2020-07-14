<?php  
include('./classes/DB.php');

if(isset($_POST['resetpassword']))
{


	$cstrong = TRUE;
    $token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
    $email = $_POST['email'];
    $user_id = DB::query('SELECT id FROM users WHERE email=:email', array('email'=>$email))[0]['id'];
    DB_update::query_update('INSERT INTO password_token VALUES (\'\',:token,:user_id)',array(':token'=>sha1($token),':user_id'=>$user_id));


    echo "Email Sent!";

}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Esqueci-me Da Palavra-Passe</title>
</head>
<body>
<h1>Forgot Password</h1>
<form action="forgot_password.php" method="POST">
	<input type="text" name="email" value="" placeholder="email"><p/>
	<input type="submit" name="resetpassword" value="Reset Password">
	
</form>
</body>
</html>