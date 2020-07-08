<?php 
include_once('./classes/DB.php');

include_once('./classes/cookie_login.php');
include_once('./classes/post.php');
include_once('./classes/image.php');
include_once('./classes/notifyClass.php');
$username = "";
$isFollowing = False;
$followerid =Login::isLoggedIn();
if(isset($_GET['u']))
{
	if (DB::query('SELECT username from users where username =:username', array(':username'=>$_GET['u']))) 
	{
			$username = DB::query('SELECT username from users where username =:username', array(':username'=>$_GET['u']))[0]['username'];
			$userid = DB::query('SELECT id from users where username=:username', array(':username'=>$_GET['u']))[0]['id'];
				
			
			if(isset($_POST['Seguir']))
			{
				if ($userid != $followerid) 
					{							
			
				if (!DB::query('SELECT follower_id FROM seguidores WHERE user_id=:userid AND follower_id=:followerid',array(':userid' =>$userid,':followerid'=>$followerid))) 
				{
						DB_update::query_update('INSERT INTO seguidores VALUES (\'\', :userid, :followerid)',array(':userid' =>$userid,':followerid'=>$followerid));
				}
				
			 }

			}
			$isFollowing = True;
			if(isset($_POST['unfollow']))
			{
				if ($userid != $followerid) 
					{
				if (DB::query('SELECT follower_id FROM seguidores WHERE user_id=:userid AND follower_id=:followerid',array(':userid' =>$userid,':followerid'=>$followerid))) 
				{
						DB_update::query_update('DELETE FROM seguidores WHERE user_id=:userid AND follower_id=:followerid  ',array(':userid' =>$userid,':followerid'=>$followerid));
				}
				
			   }
			}
            $isFollowing = False;
			if (DB::query('SELECT follower_id FROM seguidores WHERE user_id=:userid AND follower_id=:followerid  ',array(':userid' =>$userid,':followerid'=>$followerid)))
				{
						$isFollowing = True;
				}


				if(isset($_POST['deletepost']))
				{
					if(DB::query('SELECT id FROM posts WHERE id =:postid AND user_id =:userid', array(':postid'=>$_GET['postid'],':userid'=>$followerid)))
					{
						DB_update::query_update('DELETE FROM posts WHERE id =:postid AND user_id =:userid',array(':postid'=>$_GET['postid'],':userid'=>$followerid));
						DB_update::query_update('DELETE FROM post_likes WHERE post_id =:postid',array(':postid'=>$_GET['postid']));
					}
				}



				if (isset($_POST['post'])) {
                        if ($_FILES['postimg']['size'] == 0) {
                                Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
                        } else {
                                $postid = Post::createIMGPost($_POST['postbody'], Login::isLoggedIn(), $userid);
                                Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array(':postid'=>$postid));
                        }
                }

                if (isset($_GET['postid']) && !isset($_POST['deletepost'])) {
                        post::likePost($_GET['postid'], $followerid);
                }

                $posts = post::displayPosts($userid, $username, $followerid);


        } else {
                die('User not found!');
        }
		
}

?>


<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        .postimg
{
    opacity: 0;
    transition: all 2s ease-out;
    width: 100%;
}
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/typicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search-1.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search-2.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search.css">
    <link rel="stylesheet" href="assets/css/Search-Field-With-Icon.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-light navbar-expand-md shadow" style="border-color: #cbcbcb;">
            <div class="container-fluid"><a class="navbar-brand" href="#" style="background-image: url(&quot;assets/img/índice.png&quot;);"></a>
                <form class="form-inline">
                    <div><input class="form-control sbox" type="text" placeholder="Procurar" style="width: 150%;">
                        <ul class="list-group autocomplete"  style="position: absolute;z-index: 100;">
                            
                        </ul>
                    </div>
                </form><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-1" style="width: 563px;margin-right: 0px;">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation"><a class="nav-link active text-left" href="#" style="font-size: 20px;">Pagina Principal</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="font-size: 20px;">Mensagens</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="font-size: 20px;">Notificações</a></li>
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="font-size: 20px;">Utilizador</a>
                            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">First Item</a><a class="dropdown-item" role="presentation" href="#">Second Item</a><a class="dropdown-item" role="presentation" href="#">Third Item</a></div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header><br>
    <div class="container">
        <h1 style="color: rgb(0,0,0);"><?php echo $username; ?> Profile</h1>
                
        <
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xl-2" style="border-color: #cbcbcb;">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <p class="text-break" style="color: rgb(0,0,0);"><strong>About Me </strong>dsfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfdsfsdfsdfsdfsdfsdfsd<br></p>
                            
                        </li>
                    </ul>
                    <form action="profile.php?u=<?php echo $username; ?>" method="post">
        <?php
        if ($userid != $followerid) {
                if ($isFollowing ) {
                        echo '<input type="submit" name="unfollow" value="unfollow">';
                } else {
                        echo '<input type="submit" name="Seguir" value="Seguir">';
                }
        }
        ?>
                 </form>
                </div>
                <div class="col-xl-6" style="border-color: #000000;background-color: rgba(0,0,0,0);color: rgb(0,0,0);">
                    <ul class="list-group">
                        <div class="timelineposts">
                            
                             
                    </ul>
                        
                </div>
                <div class="col-md-6 col-xl-4"><button class="btn btn-primary" type="button" style="background-color: rgb(7,215,65);" onclick="showNewPostModal()">New Post</button>

                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="commentsmodal" role="dialog" tabindex="-1" style="color: rgb(0,0,0); padding: 200px">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Comentários</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                        <div class="modal-body">
                             <h3>Não há comentários</h3>   
                                
                        </div>
                        <div class="modal-footer"><button class="btn btn-light" type="button" data-dismiss="modal">Fechar</button></div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="newpost" role="dialog" tabindex="-1" style="color: rgb(0,0,0); padding: 200px; ">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Post</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                        <div style="max-height: 400px ;overflow-y: auto">
                            <form action="profile.php?u=<?php echo $username; ?>" method="post" enctype="multipart/form-data">
                                <textarea name="postbody" rows="5" cols="64"></textarea>
                                <br />Upload an image:
                                <input type="file" name="postimg">
                        </div>
                        <div class="modal-footer">
                                                <input type="submit" name="post" value="Post" class="btn btn-default" type="button" style="background-image:url(&quot;none&quot;);background-color:#2bda05;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;">
                            <button class="btn btn-light" type="button" data-dismiss="modal">Fechar</button></div>
                    </div>
                </div>
            </div>
            <div class="footer-dark" style="position: relative;">
        <footer>
            <div class="container">
                <p class="copyright">Social Media© 2020</p>
            </div>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script type="text/javascript">

     var start = 5;
    var working = false;
    $(window).scroll(function() {
            if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
                    if (working == false) {
                            working = true;
                            $.ajax({

                                    type: "GET",
                                    url: "restapi/profileposts?u=<?php echo $username; ?> &start="+start,
                                    processData: false,
                                    contentType: "application/json",
                                    data: '',
                                     success: function(r) {
                                var posts = JSON.parse(r)
                                $.each(posts, function(index) {

                                    if (posts[index].PostImage == "") 
                                    {



                                        $('.timelineposts').html(
                                                $('.timelineposts').html() +

                                                ' <li class="list-group-item" id="'+posts[index].PostId+'" style="border-color: #cbcbcb;"><blockquote class="blockquote"><p class="mb-0" style="color: rgb(0,0,0);">'+posts[index].PostBody+'</p><footer class="blockquote-footer">Posted by '+posts[index].PostedBy+' on '+posts[index].PostDate+'</footer></blockquote><button class="btn btn-primary" type="button" data-id="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;">&nbsp;<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+posts[index].Likes+' Likes</button><button      class="btn btn-primary" type="button" data-postid="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;" onclick="showCommentsModal()">&nbsp;<i class="typcn typcn-pencil" data-bs-hover-animate="rubberBand" style="color: rgb(255,0,0);"></i>&nbsp;Comentários</button>  </li>   '
                                              
                                        )
                                         }
                                         else
                                         {
                                            $('.timelineposts').html(
                                                $('.timelineposts').html() +

                                                ' <li class="list-group-item" id="'+posts[index].PostId+'" style="border-color: #cbcbcb;"><blockquote class="blockquote"><p class="mb-0" style="color: rgb(0,0,0);">'+posts[index].PostBody+'</p><img src="" data-tempsrc="'+posts[index].PostImage+'" class="postimg" id ="img'+posts[index].PostId+'"><footer class="blockquote-footer">Posted by '+posts[index].PostedBy+' on '+posts[index].PostDate+'</footer></blockquote><button class="btn btn-primary" type="button" data-id="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;">&nbsp;<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+posts[index].Likes+' Likes</button><button      class="btn btn-primary" type="button" data-postid="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;" onclick="showCommentsModal()">&nbsp;<i class="typcn typcn-pencil" data-bs-hover-animate="rubberBand" style="color: rgb(255,0,0);"></i>&nbsp;Comentários</button>  </li>   '
                                              
                                        )
                                         }
                                        
                                        $('[data-postid]').click(function() {
                                                var buttonid = $(this).attr('data-postid');

                                                $.ajax({

                                                        type: "GET",
                                                        url: "restapi/comments?postid=" + $(this).attr('data-postid'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r)
                                                                showCommentsModal(res);
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }

                                                });
                                        });




                                        $('[data-id]').click(function(r) {
                                                var buttonid = $(this).attr('data-id');
                                                $.ajax({

                                                        type: "POST",
                                                        url: "restapi/Likes?id=" + $(this).attr('data-id'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r);
                                                                 
                                                                $("[data-id='"+buttonid+"']").html('<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+res.Likes+' Likes</button> ')
                                                                console.log(r);
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }

                                                });
                                        })
                                })
                                    
                             $('.postimg').each(function() {
                                        this.src=$(this).attr('data-tempsrc')
                                        this.onload = function() {
                                                this.style.opacity = '1';
                                        }
                                })
                             scrollToAnchor(location.hash)

                                            start+=5;
                                            setTimeout(function() {
                                                    working = false;
                                            }, 4000)

                                    },
                                    error: function(r) {
                                            console.log(r)
                                    }

                            });
                    }
            }
    })
        function scrollToAnchor(aid){
    var aTag = $(aid);
        $('html,body').animate({scrollTop: aTag.offset().top},'slow');
    }

        $(document).ready(function() {
            $('.sbox').keyup(function() {
                        $('.autocomplete').html("")
                        $.ajax({

                                type: "GET",
                                url: "restapi/search?query=" + $(this).val(),
                                processData: false,
                                contentType: "application/json",
                                data: '',
                                success: function(r) {
                                        r = JSON.parse(r)
                                        for (var i = 0; i < r.length; i++) {
                                                console.log(r[i].body)
                                                $('.autocomplete').html(
                                                        $('.autocomplete').html() +
                                                        '<a href="profile.php?u='+r[i].username+'"><li class="list-group-item"><span>'+r[i].body+' Posted by '+r[i].username+'</span></li>'
                                                )
                                        }
                                },
                                error: function(r) {
                                        console.log(r)
                                }
                        })
                })
                $.ajax({

                        type: "GET",
                        url: "restapi/profileposts?u=<?php echo $username; ?>&start= 0",
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                                var posts = JSON.parse(r)
                                $.each(posts, function(index) {

                                    if (posts[index].PostImage == "") 
                                    {



                                        $('.timelineposts').html(
                                                $('.timelineposts').html() +

                                                ' <li class="list-group-item" id="'+posts[index].PostId+'" style="border-color: #cbcbcb;"><blockquote class="blockquote"><p class="mb-0" style="color: rgb(0,0,0);">'+posts[index].PostBody+'</p><footer class="blockquote-footer">Posted by '+posts[index].PostedBy+' on '+posts[index].PostDate+'</footer></blockquote><button class="btn btn-primary" type="button" data-id="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;">&nbsp;<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+posts[index].Likes+' Likes</button><button      class="btn btn-primary" type="button" data-postid="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;" onclick="showCommentsModal()">&nbsp;<i class="typcn typcn-pencil" data-bs-hover-animate="rubberBand" style="color: rgb(255,0,0);"></i>&nbsp;Comentários</button>  </li> </ul>  '
                                              
                                        )
                                         }
                                         else
                                         {
                                            $('.timelineposts').html(
                                                $('.timelineposts').html() +

                                                ' <li class="list-group-item" id="'+posts[index].PostId+'" style="border-color: #cbcbcb;"><blockquote class="blockquote"><p class="mb-0" style="color: rgb(0,0,0);">'+posts[index].PostBody+'</p><img src="" data-tempsrc="'+posts[index].PostImage+'" class="postimg" id ="img'+posts[index].PostId+'"><footer class="blockquote-footer">Posted by '+posts[index].PostedBy+' on '+posts[index].PostDate+'</footer></blockquote><button class="btn btn-primary" type="button" data-id="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;">&nbsp;<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+posts[index].Likes+' Likes</button><button      class="btn btn-primary" type="button" data-postid="'+posts[index].PostId+'" style="background-color: rgba(0,0,0,0);color: rgb(0,0,0);width: 142px;font-family: Alegreya, serif;" onclick="showCommentsModal()">&nbsp;<i class="typcn typcn-pencil" data-bs-hover-animate="rubberBand" style="color: rgb(255,0,0);"></i>&nbsp;Comentários</button>  </li> </ul>  '
                                              
                                        )
                                         }
                                        
                                        $('[data-postid]').click(function() {
                                                var buttonid = $(this).attr('data-postid');

                                                $.ajax({

                                                        type: "GET",
                                                        url: "restapi/comments?postid=" + $(this).attr('data-postid'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r)
                                                                showCommentsModal(res);
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }

                                                });
                                        });




                                        $('[data-id]').click(function(r) {
                                                var buttonid = $(this).attr('data-id');
                                                $.ajax({

                                                        type: "POST",
                                                        url: "restapi/likes?id=" + $(this).attr('data-id'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r);
                                                                 
                                                                $("[data-id='"+buttonid+"']").html('<i class="icon-fire" data-bs-hover-animate="rubberBand" style="color: rgb(36,0,255);"></i>&nbsp;'+res.Likes+' Likes</button> ')
                                                                console.log(r);
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }

                                                });
                                        })
                                })
                                    
                             $('.postimg').each(function() {
                                        this.src=$(this).attr('data-tempsrc')
                                        this.onload = function() {
                                                this.style.opacity = '1';
                                        }
                                })
                             scrollToAnchor(location.hash)
                        },
                        error: function(r) {
                                console.log(r)
                        }

                });

        });

  function showNewPostModal()
  {
    $('#newpost').modal('show')
  }

  function showCommentsModal(res) {
                $('#commentsmodal').modal('show')
                var output = "";
                for (var i = 0; i < res.length; i++) {
                        output += res[i].Comment;
                        output += " ~ Comment by  ";
                        output += res[i].CommentedBy;
                        output += "<hr />";
                }

                $('.modal-body').html(output)
        }
    </script>
</body>

</html>