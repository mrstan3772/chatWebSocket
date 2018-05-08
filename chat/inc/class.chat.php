<?php
namespace MyApp;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Embed\Embed;
use Embed\Http\CurlDispatcher;
use Ratchet\RFC6455\Messaging\MessageInterface;
use \Datetime;
ini_set('memory_limit', '4024M');
setlocale (LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set("Europe/Paris");

class ChatServer implements MessageComponentInterface {
    protected $clients;
    private $dbh;
    private $users = array();
    private $sessionsUsers = array();

    public function __construct() {
        global $dbh, $docRoot;
        $this->clients 	= new \SplObjectStorage;
        $this->dbh 		= $dbh;
        $this->root 	= $docRoot;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $conn->Session->start();
        $id = $conn->Session->get('user_id');
        $username  = $conn->Session->get('username');
        $avatar = $conn->Session->get('avatar');
        $category = $conn->Session->get('category');
        $categoryName = $conn->Session->get('categoryName');
        if (!in_array($id, $this->sessionsUsers)) {
            array_push($this->sessionsUsers, $id);
        }
        echo "Nouvelle connection! ({$conn->resourceId})\n";
        echo "L'ip de l'utilisateur est : ({$conn->remoteAddress})\n";
        if(!$this->userExist($id)) {
            $this->users[$id] = array(
                "id" => $id,
                "name" => $username,
                "avatar" => $avatar,
                "category" => $category,
                "categoryName" => $categoryName,
                "seen" => time(),
                "client" => array(),
            );
        }
        array_push($this->users[$id]['client'], $conn);
        $this->personnalInformation($conn, $this->users[$id]);
        $this->createPopup();
        $nbClient = count($this->users[$id]['client']);
        foreach($this->users as $keyUser=>$user){
            if($keyUser != $id){
                foreach ($user['client'] as $keySessCl=>$sessionCl) {
                    if($nbClient == 1) {
                        $this->send($sessionCl, "single", array("name" => "M.X", "id" => 999999999999999999999999999999999999, "avatar" => "bot_logo.png", "msg" => "L'utilisateur <b>" . $username . "</b> vient de nous rejoindre !", "posted" => date("d-m-Y H:i:s")));
                    }
                }
            }
        }
        $this->checkOnliners($conn);
        print_r("{$this->users[$id]['name']}\n");
        //print_r($this->sessionsUsers);
        //print_r($this->users[$id]['client']);
        $this->send($conn, "fetch", $this->fetchMessages());
        /**
        foreach ($this->clients as $keyCl=>$client) {
            foreach($this->users as $keyUser=>$user) {
                if($keyUser != $id) {
                    $this->send($client, "fetchPrivate", $this->fetchPrivateMessages($id, $keyUser));
                }
            }
        }
         **/
    }

    public function onMessage(ConnectionInterface $from, $data) {
        $data = json_decode($data, true);
        $id	  = $from->Session->get('user_id');
        $username  = $this->users[$id]['name'];
        $avatar = $this->users[$id]['avatar'];
        $category = $this->users[$id]['category'];
        $categoryName = $this->users[$id]['categoryName'];

        if(isset($data['data']) && count($data['data']) != 0){
            $type = $data['type'];
            $user = isset($this->users[$id]) ? $this->users[$id]['name'] : false;

            if($type == "send" && $user !== false){
                $msg = strip_tags($data['data']['msg']);
                if(strlen($msg) <= 2548 && count($this->users) > 1) {
                    $sql = $this->dbh->prepare("INSERT INTO CHAT.wsMessages (idUser, name, avatar, N.msg, posted) VALUES(?, ?, ?, ?, ?)");
                    $sql->execute(array($id, $username, $avatar, $this->toEmbedded($msg), date('d-m-Y H:i:s')));
                    foreach ($this->clients as $client) {
                        $this->send($client, "single", array("name" => $username, "id" => $id, "avatar" => $avatar, "msg" => $this->toEmbedded($msg), "posted" => date("d-m-Y H:i:s")));
                    }
                }elseif(strlen($msg) <= 2548 && count($this->users) === 1){
                    $sql = $this->dbh->prepare("INSERT INTO CHAT.wsMessages (idUser, name, avatar, N.msg, posted) VALUES(?, ?, ?, ?, ?)");
                    $sql->execute(array($id, $username, $avatar, $this->toEmbedded($msg), date('d-m-Y H:i:s')));
                    foreach ($this->clients as $client) {
                        $this->send($client, "single", array("name" => $username, "id" => $id, "avatar" => $avatar, "msg" => $this->toEmbedded($msg), "posted" => date("d-m-Y H:i:s")));
                        $this->send($client, "single", array("name" => "M.X", "id" => $id, "avatar" => "bot_logo.png", "msg" => "Vous êtes seul...", "posted" => date("d-m-Y H:i:s")));
                    }
                }else{
                    foreach ($this->clients as $client) {
                        if (count($this->users) === 1) {
                            $this->send($client, "single", array("name" => "M.X", "id" => $id, "avatar" => "bot_logo.png", "msg" => "Vous êtes seul et votre message est trop long...", "posted" => date("d-m-Y H:i:s")));
                        }else {
                            $this->send($client, "single", array("name" => "M.X", "id" => $id, "avatar" => "bot_logo.png", "msg" => "Message trop long...", "posted" => date("d-m-Y H:i:s")));
                        }
                    }
                }
            }elseif($type == "fetch"){
                $this->send($from, "fetch", $this->fetchMessages());
            }elseif($type == "sendToUser" && $user !== false){
                $idReceiver = $data['data']['receiver'];
                $msg = strip_tags($data['data']['msg']);
                if(strlen($msg) <= 2548) {
                    if(!isset($data['data']['msgFile'])) {
                        if ($id != $idReceiver) {
                            $date = new DateTime( "NOW" );
                            $sql = $this->dbh->prepare("INSERT INTO CHAT.wsPrivateMessages(idUser, avatar, name, N.msg, receiver, categoryUser, posted, postedHour, currTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
                            $sql->execute(array($id, $avatar, $username, $this->toEmbedded($msg), $idReceiver, $category, strftime("%A %d %B %Y"), strftime("%X")));
                            $currTime = substr($date->format( "Y-m-d H:i:s.u" ), 0, 21);
                            foreach ($this->users[$id]['client'] as $keyCl => $client) {
                                $this->send($client, "sendTo", array("name" => $username, "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => $avatar, "msg" => $this->toEmbedded($msg), "categoryName" => $categoryName, "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X"), "currTime" => $currTime));
                            }
                            if(isset($this->users[$idReceiver])) {
                                foreach ($this->users[$idReceiver]['client'] as $keyCl => $client) {
                                    $this->send($client, "sendTo", array("name" => $username, "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => $avatar, "msg" => $this->toEmbedded($msg), "categoryName" => $categoryName, "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X"), "currTime" => $currTime));
                                }
                            }
                        } else {
                            foreach ($this->users[$id]['client'] as $keyCl => $client) {
                                $this->send($client, "sendTo", array("name" => "M.X", "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => "bot_logo.png", "msg" => "Vous vous sentez seul... ?", "categoryName" => "Bot", "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X"), "currTime" => '2099-05-06 20:55:28.57'));
                            }
                        }
                    }else{
                        $msgFile = $data['data']['msgFile'];
                        if ($id != $idReceiver) {
                            $sql = $this->dbh->prepare("INSERT INTO CHAT.wsPrivateMessages(idUser, avatar, name, N.msg, receiver, categoryUser, posted, postedHour, currTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
                            $sql->execute(array($id, $avatar, $username, $msgFile, $idReceiver, $category, strftime("%A %d %B %Y"), strftime("%X")));
                            foreach ($this->users[$id]['client'] as $keyCl => $client) {
                                $this->send($client, "sendTo", array("name" => $username, "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => $avatar, "msg" => "Vous venez d'envoyez un fichier : ".$msgFile, "categoryName" => $categoryName, "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X")));
                            }
                            foreach ($this->users[$idReceiver]['client'] as $keyCl => $client) {
                                $this->send($client, "sendTo", array("name" => $username, "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => $avatar, "msg" => "Vous venez de recevoir un fichier : ".$msgFile, "categoryName" => $categoryName, "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X")));
                            }
                        } else {
                            foreach ($this->users[$id]['client'] as $keyCl => $client) {
                                $this->send($client, "sendTo", array("name" => "M.X", "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => "bot_logo.png", "msg" => "Vous vous sentez seul... ?", "categoryName" => "Bot", "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X")));
                            }
                        }
                    }
                }else{
                    foreach ($this->users[$id]['client'] as $keyCl=>$client) {
                        $this->send($client, "sendTo", array("name" => "M.X", "idTransmitter" => $id, "idReceiver" => $idReceiver, "avatar" => "bot_logo.png", "msg" => "Message trop long...", "categoryName"=> "Bot", "posted" => strftime("%A %d %B %Y"), "postedHour" => strftime("%X")));
                    }
                }
            }elseif($type == "fetchPrivate"){
                $idReceiver = $data['data']['idReceiver'];
                if(!isset($data['data']['firstMsg'])) {
                    foreach ($this->users[$id]['client'] as $client) {
                        $this->send($client, "fetchPrivate", $this->fetchPrivateMessages($id, $idReceiver));
                    }
                }else{
                    $firstMsg = $data['data']['firstMsg'];
                    foreach ($this->users[$id]['client'] as $client) {
                        $this->send($client, "fetchPrivate", $this->fetchPrivateMessagesSpecific($id, $idReceiver, $firstMsg));
                    }
                }
            }elseif($type == "fetchPrivatePrevious"){
                $firstMsg = $data['data']['firstMsg'];
                $idReceiver = $data['data']['idReceiver'];
                $this->send($from, "fetchPrevious", $this->fetchPrivateMessagesPrevious($id, $firstMsg, $idReceiver));
            }
        }
        $this->checkOnliners($from);
    }

    public function onClose(ConnectionInterface $conn) {
        $id = $conn->Session->get('user_id');

        foreach($this->users as $keyID=>$user){
            foreach($user['client'] as $KeyInfo=>$userInfo){
                if ($conn->resourceId == $userInfo->resourceId) {
                    unset($this->users[$keyID]['client'][$KeyInfo]);
                    if (empty($this->users[$keyID]['client'])) {
                        foreach ($this->clients as $client) {
                            $this->send($client, "single", array("name" => "M.X", "id" => 999999999999999999999999999999999999, "avatar" => "bot_logo.png", "msg" => "L'utilisateur <b>" . $this->users[$keyID]['name'] . "</b> vient de quitter le canal !", "posted" => date("d-m-Y H:i:s")));
                            $this->removePopup($client, $this->users[$keyID]['id']);
                        }
                        unset($this->users[$keyID]);
                        $this->clients->detach($conn);
                    }
                }
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    /* My custom functions */
    public function userExist($userID){
        if(!empty($this->users[$userID])) {
            return true;
        }else{
            return false;
        }
    }

    public function fetchMessages(){
        $sql = $this->dbh->query("SELECT * FROM CHAT.wsMessages");
        $msgs = $sql->fetchAll();
        return $msgs;
    }

    public function fetchPrivateMessages($idUser, $idReceiver){
        try{
            $sql = $this->dbh->query("SELECT * FROM (SELECT TOP 10 * FROM CHAT.wsPrivateMessages 
                                      WHERE (idUser=$idUser AND receiver=$idReceiver) OR (idUser=$idReceiver AND receiver=$idUser) 
                                      ORDER BY id DESC) AS WSPM
                                      INNER JOIN CHAT.category AS C ON WSPM.categoryUser = C.id 
                                      ORDER BY WSPM.id DESC");
            $msgs = $sql->fetchAll();
            return $msgs;
        }catch(\Exception $e){
            echo "Erreur SQL pour afficher les messages en début d'ouverture de session\n";
            echo "{$e->getMessage()}\n";
        }
    }

    public function fetchPrivateMessagesSpecific($idUser, $idReceiver, $firstMsg){
        try{
            $sql = $this->dbh->query("SELECT * FROM (SELECT TOP 10 * FROM CHAT.wsPrivateMessages 
                                      WHERE (idUser=$idUser AND receiver=$idReceiver) OR (idUser=$idReceiver AND receiver=$idUser) 
                                      AND CAST([currTime] as time) < CAST('$firstMsg' as time)
                                      ORDER BY id DESC) AS WSPM
                                      INNER JOIN CHAT.category AS C ON WSPM.categoryUser = C.id 
                                      ORDER BY WSPM.id DESC");
            $msgs = $sql->fetchAll();
            return $msgs;
        }catch(\Exception $e){
            echo "{$firstMsg}\n";
            echo "Erreur SQL pour afficher les messages en début d'ouverture de session\n";
            echo "{$e->getMessage()}\n";
        }
    }

    public function fetchPrivateMessagesPrevious($id, $firstMsg, $idReceiver){
        try{
            $sql = $this->dbh->query("SELECT * FROM (SELECT TOP 10 * FROM CHAT.wsPrivateMessages WHERE id < $firstMsg ORDER BY id DESC) AS WSPM                                      
                                      INNER JOIN CHAT.category AS C ON WSPM.categoryUser = C.id
                                      WHERE (WSPM.idUser=$id AND WSPM.receiver=$idReceiver) OR (WSPM.idUser=$idReceiver AND WSPM.receiver=$id)  
                                      ORDER BY WSPM.id DESC");
            $msgs = $sql->fetchAll();
            return $msgs;
        }catch(\Exception $e){
            echo "Erreur SQL pour afficher la suite des messages\n";
            echo "{$e->getMessage()}\n";
        }
    }


    public function checkOnliners($curUser = ""){
        /**
        date_default_timezone_set("UTC");
        if( $curUser != "" && isset($this->users[$curUser->resourceId]) ){
            $this->users[$curUser->resourceId]['seen'] = time();
        }

        $curtime 	= strtotime(date("Y-m-d H:i:s", strtotime('-5 seconds', time())));
        foreach($this->users as $id => $user){
            $usertime 	= $user['seen'];

            if($usertime < $curtime){
                unset($this->users[$id]);
            }
        }
         **/

        /* Send online users to evryone */
        $data = $this->users;
        foreach ($this->clients as $client) {
            $this->send($client, "onliners", $data);
        }
    }

    public function personnalInformation($conn, $userData) {
        $this->send($conn, "infoUser", $userData);
    }

    public function createPopup() {
        $data = $this->users;
        foreach ($this->clients as $client) {
            $this->send($client, "createPopup", $data);
        }
    }

    public function removePopup($client, $id) {
        $this->send($client, "removePopup", $id);
    }

    public function toEmbedded($str){
        try {
            $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
            preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);

            $dispatcher = new CurlDispatcher([
                /**
                CURLOPT_MAXREDIRS => 20,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => '',
                CURLOPT_AUTOREFERER => true,
                CURLOPT_USERAGENT => 'Embed PHP Library',
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_PROXY => '10.0.210.3:3128', **/
            ]);

            if(count($matches[0]) == 1) {
                $finalContent = "";
                foreach ($matches[0] as $key=>$url) {
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                            $finalContent .= $this->embbedURL($url, $dispatcher);
                    }
                }
                $finalContent = preg_replace("/\r|\n/", "", $finalContent);
                $msg = $this->convertToURL($str) . $finalContent;
                return $msg;
            }elseif(count($matches[0]) > 1){
                    $finalContent = "";
                    $listLink = array();
                    foreach($matches[0] as $key=>$url) {
                        if (!in_array($url, $listLink)) {
                            array_push($listLink, $url);
                        }
                    }
                    foreach ($listLink as $key => $url) {
                        if (filter_var($url, FILTER_VALIDATE_URL)) {
                            $finalContent .= $this->embbedURL($url, $dispatcher);
                        }
                    }
                    $finalContent = preg_replace("/\r|\n/", "", $finalContent);
                    $msg = $this->convertToURL($str) . $finalContent;
                    return $msg;
            }else{
                return $str;
            }
        }catch(\Exception $e){
            return $str;
        }
    }

    public function convertToURL($input){
        $pattern = '@(http(s)?://)?(([a-zA-Z0-9])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        return $output = preg_replace($pattern, '<a href="http$2://$3">$0</a>', $input);
    }

    public function embbedURL($url, $dispatcher){
        $info = Embed::create($url, [
            'min_image_width' => 100,
            'min_image_height' => 100,
            'choose_bigger_image' => true,
            'images_blacklist' => 'example.com/*',
            'url_blacklist' => 'example.com/*',
            'follow_canonical' => true,

            'html' => [
                'max_images' => 10,
                'external_images' => true
            ],

            'oembed' => [
                'parameters' => [],
                'embedly_key' => 'YOUR_KEY',
                'iframely_key' => 'YOUR_KEY',
            ],

            'google' => [
                'key' => 'YOUR_KEY',
            ],

            'soundcloud' => [
                'key' => 'YOUR_KEY',
            ],

            'facebook' => [
                'key' => 'YOUR_KEY',
                'fields' => 'field1,field2,field3' // default : cover,description,end_time,id,name,owner,place,start_time,timezone
            ],
        ], $dispatcher);
        $myArrray = $info->tags;
        $imgURL = $info->image;
        if (empty($imgURL)) {
            $imgURL = "img/no-image-found.png";
        }
        $content = "<div class='embeddedAdd row' id='" . uniqid() . "'>
                                      <section class='infoEmbeddedAdd col-md-12' data-url='" . $info->url . "'>
                                        <section class='wrapper_descriptionURL col-md-8'>
                                        <header>
                                          <h1><a href='" . $info->url . "' target='_blank' class='pageURL'>" . $info->title . "</a></h1>
                                          <span>Type : " . $info->type . "</span>
                                        </header>
                                          <article>
                                            <div class='containerDescriptionURL'>
                                              <p class='descriptionURL'>
                                                <span>Description : </span>
                                                        " . substr($info->description, 0, 150) . "
                                              </p>
                                              <div class='followingContent'>
                                                  <p class='tagURL'>
                                                    " . implode(',', $myArrray) . "
                                                  </p>
                                                  <p class='authorNameURL'> <a href='" . $info->authorUrl . "' title='Cliquer ici pour acceder' class='authorUrl'>" . $info->authorName . "</a></p> 
                                            </div>
                                                <p><a class='toggleButton' href='javascript:void(0);'>VOIR LA SUITE</a></p>
                                            </div>
                                          </article>
                                          </section>
                                          <aside class='containerImgURL col-md-4'>
                                            <img class='imgURL' src='" . $imgURL . "' alt='Image URL'>  
                                          </aside>      
                                      </section>
                                    <div class='embeddedContainer col-md-12'>
                                      " . $info->code . "
                                         <div class='infoProviderURL'>
                                              <figure>
                                                <a href='" . $info->providerUrl . "' title='Visiter le site'>
                                                  <img class='iconURL' src='" . $info->providerIcon . "' alt='icon du site'>
                                                  <figcaption>" . $info->providerName . "</figcaption>
                                                </a>
                                              </figure>
                                          </div>
                                     </div>
                                  </div>";
        return $content;
    }
    public function send($client, $type, $data){
        $send = array(
            "type" => $type,
            "data" => $data
        );
        $send = json_encode($send, true);
        $client->send($send);
    }
}
?>