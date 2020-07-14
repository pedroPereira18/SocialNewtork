<?php
session_start();
$cstrong = TRUE;
$token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
if (!isset($_SESSION['token'])) 
{
	$_SESSION['token'] = $token;
}



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
if (isset($_POST['send'])) {
	if ($_POST['security_token'] != $_SESSION['token']) 
	{
		die(header('location: homepage.php'));
	}
		if ($_POST['security_token'] != $_SESSION['token']) 
		{
			die(header('location: homepage.php'));
		}
        if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver']))) {

                DB_update::query_update("INSERT INTO messages VALUES ('', :body, :sender, :receiver, 0)", array(':body'=>$_POST['body'], ':sender'=>$userid, ':receiver'=>htmlspecialchars($_GET['receiver'])));
                echo "Message Sent!";
        }
        session_destroy();
}

?>


<h1>Send a Message</h1>
<form action="send_messages.php?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="post">
        <textarea name="body" rows="8" cols="80"></textarea>
        <input type="hidden" name="security_token" value="<?php echo $_SESSION['token'];?>">
        <input type="submit" name="send" value="Send Message">
</form>