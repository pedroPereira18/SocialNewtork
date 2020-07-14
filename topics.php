<?php 
include_once('./classes/DB.php');
include_once('login.php');
include_once('./classes/cookie_login.php');
include_once('./classes/post.php');
include_once('./classes/image.php');


if (Login::IsLoggedIn()) 
{
  $userid=Login::IsLoggedIn();

}
else
{
	die(header("Location: /index.html"));
   
}
if(isset($_GET['topic']))
{
	
		
		$posts = DB::query("SELECT * FROM posts WHERE FIND_IN_SET(:topic,topics)", array(':topic'=>$_GET['topic']));

		foreach ($posts as $post ) {
		/*	echo "<pre>";
			print_r($post);
			echo "</pre>";*/
			echo $post['body'].'<br/>';
		}
	
}

