<?php

// The Regular Expression filter
$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

// The Text you want to filter for urls
$text = "The text you want to filter goes here. http://google.com zdazdad https://oscarotero.com/embed3/demo/index.php?url=https%3A%2F%2Fwww.google.fr%2F%3Fgfe_rd%3Dcr%26dcr%3D0%26ei%3DrOvEWpGFMcrBgAeR7rngDQ";

// Check if there is a url in the text
if(preg_match($reg_exUrl, $text, $url)) {

       // make the urls hyper links
       echo preg_replace($reg_exUrl, "<a href=".$url[0].">".$url[0]."</a> ", $text);

} else {

       // if no urls in the text just return the text
       echo $text;

}

function convert($input) {
   $pattern = '@(http(s)?://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
   return $output = preg_replace($pattern, '<a href="http$2://$3">$0</a>', $input);
}

echo convert($text);

?>