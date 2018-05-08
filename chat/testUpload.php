<?php
require __DIR__.'/session.php';

if(empty($session->get('user_id')))
{
    header("Location: user/login.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link href="dist/animate-css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/fontawesome5.0.10/web-fonts-with-css/css/fontawesome-all.min.css" >
    <link rel="stylesheet" href="dist/jquery.fileupload.css" >
    <link href="cdn/chat.css" rel="stylesheet"/>
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous">

    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>
    <script src="cdn/ws.js"></script>
    <script type="text/javascript" src="dist/bootstrap-notify/bootstrap-notify.js"></script>
    <script type="text/javascript" src="cdn/defaultNotify.js"></script>
    <script src="cdn/scrollMessages.js"></script>
    <script src="cdn/chat.js"></script>
    <script src="cdn/embedded.js"></script>
    <script src="cdn/emoji.js"><</script>
    <title>Chat en ligne</title>
</head>
<body>
<div class="container">
    <center><h1>Chat en ligne</h1></center>
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
                <form id="msgForm">
                    <input type="text" id="textMessage"/>
                    <button class="btn btn-outline-dark">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div style="height: 500px;"></div>
<!-- The fileinput-button span is used to style the file input field as button -->
<span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
    <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
<br>
<br>
<!-- The global progress bar -->
<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success"></div>
</div>
<!-- The container for the uploaded files -->
<div id="files" class="files"></div>
<br>
</body>
<script type="text/javascript" src="dist/jquery/jquery.ui.widget.js"></script>
<script type="text/javascript" src="dist/jquery/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="dist/jquery/jquery.fileupload.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

</html>