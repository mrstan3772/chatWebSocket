<?php
require __DIR__.'/session.php';
$session->set('curURL', $_SERVER['REQUEST_URI']);
if(empty($session->get('user_id')))
{
    header("Location: user/login.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/x-icon" href="img/logo-tidio-chat.png" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <link href="dist/animate-css/animate.css" rel="stylesheet">
        <link rel="stylesheet" href="dist/fontawesome5.0.10/web-fonts-with-css/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="dist/jquery-confirm/jquery-confirm.min.css" >
        <link rel="stylesheet" href="dist/emojionearea/emojionearea.min.css">
        <link href="cdn/chat.css" rel="stylesheet"/>
        <script
                src="https://code.jquery.com/jquery-3.3.1.js"
                integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
                crossorigin="anonymous">

        </script>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript" src="dist/jqueryPlaySound/jquery.playSound.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>
        <script src="cdn/ws.js"></script>
        <script type="text/javascript" src="dist/bootstrap-notify/bootstrap-notify.js"></script>
        <script type="text/javascript" src="dist/jquery-confirm/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="cdn/defaultNotify.js"></script>
        <script type="text/javascript" src="dist/emojionearea/emojionearea.min.js"></script>
        <script type="text/javascript" src="dist/jquery.animate-shadow.js"></script>
        <script src="cdn/scrollMessages.js"></script>
        <script src="cdn/emoji.js"><</script>
        <script src="dist/PageTitleNotification.js"><</script>
		<script src="cdn/chat.js"></script>
        <script src="cdn/embedded.js"></script>
		<title>Chat en ligne</title>
	</head>
	<body>
		<div class="container">
			<h1 class="titlePageChat text-center">Chat en ligne</h1>
			<div class="chatWindow row">
                <div class="infoUsers bg-dark col-md-4">
                    <div class="online bg-dark row"></div>
                    <div class="users bg-dark row mx-auto">
                        <table class="table table-hover table-bordered table-dark table-responsive-md">

                            <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Avatar</th>
                                <th scope="col">Nom</th>
                                <th scope="col">ID</th>
                            </tr>
                            </thead>

                            <!--
                            <tr>
                                <th>Avatar</th>
                                <th>Nom</th>
                                <th>ID</th>
                            </tr>
                            </tfoot>
                            -->

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
				<div class="chatbox col-md-8">
                    <div class="infoConnection bg-secondary row">
                        <div class="containerStatus col-md-6 d-flex flex-wrap">
                            <div class="statusButton"></div>
                            <div class="status">Hors ligne</div>
                        </div>
                        <div class="nameUser col-md-6"></div>
                    </div>
					<div class="chat row">
                        <div class="notification col-md-12 position-fixed">Nouveaux Messages (<span class="countNewMessages"></span>)</div>
						<div class="msgs container"></div>
	 					<form id="msgForm" class="col-md-12">
							<input type="text" id="textMessage" class="col-md-10" autocomplete="off"/>
                            <div class="sk-fading-circle col-md-1 align-middle">
                                <div class="sk-circle1 sk-circle"></div>
                                <div class="sk-circle2 sk-circle"></div>
                                <div class="sk-circle3 sk-circle"></div>
                                <div class="sk-circle4 sk-circle"></div>
                                <div class="sk-circle5 sk-circle"></div>
                                <div class="sk-circle6 sk-circle"></div>
                                <div class="sk-circle7 sk-circle"></div>
                                <div class="sk-circle8 sk-circle"></div>
                                <div class="sk-circle9 sk-circle"></div>
                                <div class="sk-circle10 sk-circle"></div>
                                <div class="sk-circle11 sk-circle"></div>
                                <div class="sk-circle12 sk-circle"></div>
                            </div>
                            <button type="send" form="msgForm" class="btn btn-dark buttonSendChat" style="margin-left: 4%;font-weight: bold;margin-bottom: 2px">Envoyer</button>
						</form>
					</div>
				</div>
			</div>
		</div>
        <a href="user/profile.php" class="btn btn-primary fixed-top" style="border-radius: 0 !important;">Mon compte</a>
	</body>
    <script type="text/javascript" src="dist/jquery/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="dist/jquery/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="dist/jquery/jquery.fileupload.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script>
    </script>
</html>