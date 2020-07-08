<?php 
include('./classes/DB.php');
include('./classes/cookie_login.php');


$showTimeLine = False;

if (Login::IsLoggedIn()) 
{
  $userid=Login::IsLoggedIn();
  $showTimeLine = True;
}
else
{
   header("Location: /index.html");
}



        $notifications = DB::query('SELECT * FROM notifications WHERE receiver=:userid ORDER BY id DESC', array(':userid'=>$userid));

        foreach($notifications as $n) {

                if ($n['type'] == 1) {
                        $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(':senderid'=>$n['sender']))[0]['username'];

                        if ($n['Extra'] == "") {
                                echo "You got a notification!<hr />";
                        } else {
                                $Extra = json_decode($n['Extra']);

                                echo $senderName." mentioned you in a post! - ".$Extra->postbody."<hr />";
                        }

                }
                else if ($n['type'] == 2) {
                        $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(':senderid'=>$n['sender']))[0]['username'];
                        echo $senderName." liked your post!<hr />";
                }

        }



?>