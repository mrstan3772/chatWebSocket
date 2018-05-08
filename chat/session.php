<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;
use MyApp\ChatServer;

require_once __DIR__.'/inc/vendor/autoload.php';
$ini = parse_ini_file( __DIR__.'/inc/app.ini');

$memcache = new Memcache;

$memcache->connect($ini['mem_server_adress'], $ini['mem_server_port']);

$storage = new NativeSessionStorage(
    array(),
    new MemcacheSessionHandler($memcache)
);

$session = new Session($storage);

$session->start();