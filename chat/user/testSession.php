<?php
use Embed\Embed;
use Embed\Http\CurlDispatcher;
require_once __DIR__.'../../inc/vendor/autoload.php';
require_once __DIR__.'../../inc/db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
$memcache = new Memcache;
$memcache->connect('127.0.0.1', 11211);
$memcache->set('zdzdzd', 'zdzdzd');
session_start();
**/
date_default_timezone_set("UTC");
echo "The time is " . date("h:i:sa");
/**
$str = "google.com";


$pattern = '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);
print_r($matches[0]);
**/
$str = "https://www.google.com/search?q=i%20like%20gizmodo&rct=j";
//$str = "Phasellus non lobortis lorem, at dapibus magna. Donec arcu massa, lobortis quis molestie eu, iaculis at turpis. Duis ac arcu vehicula, posuere massa in, vehicula sem. Nullam volutpat urna eget consectetur pharetra. Nullam imperdiet ipsum magna, sed tempus erat bibendum sed. Phasellus quis diam justo. Phasellus dignissim scelerisque tellus, non bibendum justo aliquam eget. Morbi nec urna massa. Proin eu arcu at elit ultrices consequat vitae nec felis. Fusce eu lacinia enim. Curabitur vel pretium erat. Vivamus mollis velit nec aliquet efficitur. Vestibulum nibh augue, laoreet vel sodales sed, commodo in nisi. Integer pulvinar mauris turpis, vel consectetur velit ullamcorper a. Nulla non hendrerit libero. Donec mollis vitae enim nec tincidunt.

//Integer et purus augue. Aliquam feugiat consectetur felis, et cursus erat luctus vitae. Phasellus a leo pellentesque, feugiat justo in, faucibus massa. In hac habitasse platea dictumst. Aliquam placerat nunc vitae nibh gravida porttitor. Integer non hendrerit urna. Integer vulputate fermentum cursus. Quisque malesuada commodo consectetur.";
$pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);

echo($matches[0][0]);

//echo strip_tags($str, '<br>');

$mystring = 'www.gizmodo.com';
$findme   = 'https://';
$pos = strpos($mystring, $findme);

$db = DB();
$sql = $db->query("SELECT * FROM CHAT.wsPrivateMessages");
$msgs = $sql->fetchAll();
print_r($msgs);
