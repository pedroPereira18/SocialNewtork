<?php
include_once('./classes/DB.php');

include_once('./classes/cookie_login.php');
include_once('./classes/post.php');
include_once('./classes/image.php');
if (Login::IsLoggedIn()) 
{
  $userid=Login::IsLoggedIn();
  
}
else
{
    die(header("Location: login.php"));
}

if (isset($_POST['confirm']))
{
    

        if (isset($_POST['alldevices']))
        {

            DB_update::query_update('DELETE FROM tokens WHERE user_id=:userid', array(
                ':userid' => Login::isLoggedIn()
            ));
            var_dump(Login::isLoggedIn());

        }
        else
        {
            if (isset($_COOKIE['SNID']))
            {

                DB_update::query_update('DELETE FROM tokens WHERE token=:token', array(
                    ':token' => sha1($_COOKIE['SNID'])
                ));
            }
            if (isset($_COOKIE['SNID']))
            {
                unset($_COOKIE['SNID']);
                unset($_COOKIE['SNID_']);
                setcookie('SNID', null, -1, '/');
                setcookie('SNID_', null, -1, '/');
            }

        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form action="logout.php" method="post"> 
    <input type="checkbox" name="alldevices" value="alldevices"> Sair de todos os dispositivos? <br/>
    <input type="submit" name="confirm" value="confirm">
    
</form>
</body>
</html>