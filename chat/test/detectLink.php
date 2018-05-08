<?php
/**
 * Created by PhpStorm.
 * User: MrStan
 * Date: 02/04/2018
 * Time: 21:41
 */


$str = "https://amazone.com dzdzd dzdz zdzdzd et http://google.com";
$pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';
preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);

//print_r($matches[0][1]);

foreach($matches[0] as $matche){
    echo $matche."<br>";
}