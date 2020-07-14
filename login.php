<?php 
include_once('./classes/DB.php');

if(isset($_POST['login']))
{
   $email = $_POST['email'];
   $password = $_POST['password'];

   if (DB::query('SELECT email from users WHERE email=:email',array(':email'=>$email))) 
   {
      if (password_verify($password, DB::query('SELECT password FROM users WHERE email=:email', array(':email'=>$email))[0]['password'])) 
      {
            $cstrong = TRUE;
            $token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
            $user_id = DB::query('SELECT id from users where email=:email', array(':email' => $email))[0]['id'];
            DB_update::query_update('INSERT INTO tokens VALUES (\'\',:token,:user_id)',array(':token'=>sha1($token),':user_id'=>$user_id));
            setcookie("SNID",$token, time() + 60*60*24*7, '/',NULL,NULL,TRUE);
            setcookie("SNID:",'1',time() + 60*60*24*3, '/',NULL,NULL,TRUE);
            header('location: homepage.php');
      }
      else
      {
         echo "incorrect password";
         echo $password;
      }
   }
   else 
   {
      die(header('location: /site/login.php'));
   }




}

/*if ($stmt = $con->prepare('SELECT id, password FROM utilizadores WHERE username = ?')) {
   
   $stmt->bind_param('s', $_POST['username']);
   $stmt->execute();s
  
   $stmt->store_result();

   if ($stmt->num_rows > 0) {
   $stmt->bind_result($id, $password);
   $stmt->fetch();
  
   if (password_verify($_POST['password'], $password)) {
      
      session_regenerate_id();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['name'] = $_POST['username'];
      $_SESSION['id'] = $id;
      header('location: ./homepage.php');

   } 
} 



   $stmt->close();
}
*/
?>
<!DOCTYPE html>
<html>
<head>
   <title>Login</title>
</head>
<body>
<form action="login.php" method="POST">
   <input type="email" name="email" placeholder="email">
   <input type="password" name="password">
   <input type="submit" name="login">
</form>
</body>
</html>