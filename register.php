<?php 
include('./classes/DB.php');

if (isset($_POST['submit'])) 
{
	$PrimeiroNome = $_POST['PrimeiroNome'];
	$UltimoNome = $_POST['UltimoNome'];
	$Username = $_POST['Username'];
	$Email = $_POST['Email'];
	$Email_repeat = $_POST['Email_repeat'];
	$Password =$_POST['Password'];
	$Password_repeat =$_POST['Password_repeat'];
	$hashed_password = password_hash($Password, PASSWORD_DEFAULT);
	
	if ($Password != $Password_repeat ) 
	{
		die(header('location: registar.php?palavra_passe_differente'));

	}
	if (!DB::query('SELECT email FROM users WHERE email=:email',array(':email'=>$Email))) {
		
	DB_update::query_update('INSERT INTO users VALUES (\'\',:PrimeiroNome,:UltimoNome,:Username,:Email,:Password,\'\')', array(':PrimeiroNome'=>$PrimeiroNome,':UltimoNome'=>$UltimoNome,':Username'=>$Username,':Email'=>$Email,':Password'=>$hashed_password ));

		}
		else
		{
		die(header('location: registar.php'));	
		}
	
}
 	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Registar</title>
</head>
<body>
<form action="registar.php" method="POST">
<input type="text" name="PrimeiroNome" placeholder="Primeiro Nome"> <br/><br/>
<input type="text" name="UltimoNome" placeholder="Ultimo Nome"><br/><br/>
<input type="text" name="Username" placeholder="Username"><br/><br/>
<input type="Email" name="Email" placeholder="Email"><br/><br/>
<input type="Email" name="Email_repeat" placeholder="Email Repeat"><br/><br/>
<input type="Password" name="Password" placeholder="Password"><br/><br/>
<input type="Password" name="Password_repeat" placeholder="Password Repeat"><br/><br/>
<input type="submit" name="submit">
</form>
</body>
</html>