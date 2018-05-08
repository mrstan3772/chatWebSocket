<?php
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

function shutdown(){
    global $docRoot;
    file_put_contents("$docRoot/inc/serverStatus.txt", "0");
    require_once "$docRoot/inc/startServer.php";
}

register_shutdown_function('shutdown');
if( isset($startNow) ){
    require "$docRoot/inc/vendor/autoload.php";
    require_once "$docRoot/inc/class.chat.php";
    $ini = parse_ini_file("$docRoot/inc/app.ini");
    $loop   = React\EventLoop\Factory::create();
    $pusher = new MyApp\ChatServer;
    $webSock = new React\Socket\Server($ini['ws_server_address'].':'.$ini['ws_server_port'], $loop);

    $memcache = new Memcache;
    $memcache->connect($ini['mem_server_adress'], $ini['mem_server_port']);

    $blackList = new Ratchet\Server\IpBlackList($pusher);
    $blackList->blockAddress($ini['black_list_ip1']);
    $blackList->blockAddress($ini['black_list_ip2']);
    $blackList->blockAddress($ini['black_list_ip3']);
    $blackList->blockAddress($ini['black_list_ip4']);
    $blackList->blockAddress($ini['black_list_ip5']);
    $blackList->blockAddress($ini['black_list_ip6']);
    $blackList->blockAddress($ini['black_list_ip7']);
    $blackList->blockAddress($ini['black_list_ip8']);
    $blackList->blockAddress($ini['black_list_ip9']);
    $blackList->blockAddress($ini['black_list_ip10']);
    $blackList->blockAddress($ini['black_list_ip11']);
    $blackList->blockAddress($ini['black_list_ip12']);
    $blackList->blockAddress($ini['black_list_ip13']);
    $blackList->blockAddress($ini['black_list_ip14']);
    $blackList->blockAddress($ini['black_list_ip15']);
    $blackList->blockAddress($ini['black_list_ip16']);
    $blackList->blockAddress($ini['black_list_ip17']);
    $blackList->blockAddress($ini['black_list_ip18']);
    $blackList->blockAddress($ini['black_list_ip19']);
    $blackList->blockAddress($ini['black_list_ip20']);

    $server =  new Ratchet\Server\IoServer(
        new \Ratchet\Http\HttpServer(
            new Ratchet\Session\SessionProvider(
                new Ratchet\WebSocket\WsServer(
                    $blackList,
                    $pusher
                ),
                new Handler\MemcacheSessionHandler($memcache)
            )
        ),
        $webSock
    );

    $loop->run();
}
?>