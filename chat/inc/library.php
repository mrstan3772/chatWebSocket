<?php

class DemoLib
{

    /*
     * Register New User
     *
     * @param $name, $email, $username, $password
     * @return ID
     * */
    public function Register($email, $username, $category, $password)
    {
        try {
            $db = DB();
            $avatar = $this->upload();
            $query = $db->prepare("INSERT INTO CHAT.users(email, username, password, avatar, userCategory) VALUES (:email,:username,:password, :avatar, :category)");
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("username", $username, PDO::PARAM_STR);
            $query->bindParam("category", $category, PDO::PARAM_INT);
            $enc_password = password_hash($password, PASSWORD_DEFAULT);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);
            $query->bindParam("avatar", $avatar, PDO::PARAM_STR);
            $query->execute();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * Check Username
     *
     * @param $username
     * @return boolean
     * */
    public function isUsername($username)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT COUNT(user_id) FROM CHAT.users WHERE username=:username");
            $query->bindParam("username", $username, PDO::PARAM_STR);
            $query->execute();
            if ($query->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * Check Email
     *
     * @param $email
     * @return boolean
     * */
    public function isEmail($email)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT COUNT(user_id) FROM CHAT.users WHERE email=:email");
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->execute();
            if ($query->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }


    public function upload()
    {
        $ds          = DIRECTORY_SEPARATOR;  //1

        $storeFolder = '../uploads';   //2

        $tempFile = $_FILES['file']['tmp_name'];          //3

        $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

        $path_parts = pathinfo($_FILES['file']['name']);

        $ext = $path_parts['extension'];

        $name = $this->random_string(150).'.'.$ext;

        $targetFile =  $targetPath. $name;  //5

        move_uploaded_file($tempFile,$targetFile); //6

        return   $name;
    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    /*
     * Login
     *
     * @param $username, $password
     * @return $mixed
     * */
    public function Login($username, $password)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT user_id, password FROM CHAT.users WHERE (username=:username OR email=:email)");
            $query->bindParam("username", $username, PDO::PARAM_STR);
            $query->bindParam("email", $username, PDO::PARAM_STR);
            $query->execute();
            $res = $query->fetch(PDO:: FETCH_OBJ);
            if (isset($res->user_id) &&  $res->user_id > 0) {
                if (password_verify($password, $res->password)) {
                    return $res->user_id;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * get User Details
     *
     * @param $user_id
     * @return $mixed
     * */
    public function UserDetails($user_id)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT U.user_id, U.username, U.email, U.avatar, U.userCategory, C.categoryName
                                            FROM CHAT.users AS U 
                                            INNER JOIN CHAT.category AS C ON U.userCategory = C.id 
                                            WHERE user_id=:user_id");
            $query->bindParam("user_id", $user_id, PDO::PARAM_STR);
            $query->execute();
            //if ($query->fetchColumn() > 0) {
                return $query->fetch(PDO::FETCH_OBJ);
            //}
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}