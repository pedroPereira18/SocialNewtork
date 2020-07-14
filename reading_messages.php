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



if (isset($_GET['mid'])) {
        $message = DB::query('SELECT * FROM messages WHERE id=:mid AND (receiver=:receiver OR sender=:sender)', array(':mid'=>$_GET['mid'], ':receiver'=>$userid, ':sender'=>$userid))[0];
        echo '<h1>View Message</h1>';
        echo htmlspecialchars($message['body']);
        echo '<hr />';

        if ($message['sender'] == $userid) {
                $id = $message['receiver'];
        } else {
                $id = $message['sender'];
        }
        DB_update::query_update('UPDATE messages SET `read`=1 WHERE id=:mid', array (':mid'=>$_GET['mid']));
        ?>
        <form action="send_messages.php?receiver=<?php echo $id; ?>" method="post">
                <textarea name="body" rows="8" cols="80"></textarea>
                <input type="submit" name="send" value="Send Message">
        </form>
        <?php
} else {

?>
<h1>My Messages</h1>
<?php
$messages = DB::query('SELECT messages.*, users.username FROM messages, users WHERE receiver=:receiver OR sender=:sender AND users.id = messages.sender', array(':receiver'=>$userid, ':sender'=>$userid));
foreach ($messages as $message) {

        if (strlen($message['body']) > 10) {
                $m = substr($message['body'], 0, 10)." ...";
        } else {
                $m = $message['body'];
        }

        if ($message['read'] == 0) {
                echo "<a href='reading_messages.php?mid=".$message['id']."'><strong>".$m."</strong></a> sent by ".$message['username'].'<hr />';
        } else {
                echo "<a href='reading_messages.php?mid=".$message['id']."'>".$m."</a> sent by ".$message['username'].'<hr />';
        }

}
}
?>