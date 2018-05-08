<?php
// ini_set("display_errors","on");
$docRoot    = realpath(dirname(__FILE__));

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;



if( !isset($dbh) ){
    require_once __DIR__.'/inc/vendor/autoload.php';

    require_once __DIR__.'/session.php';


    require_once "$docRoot/inc/db_connect.php";

    include_once "$docRoot/inc/startServer.php";

    $dbh = DB();
}
?>