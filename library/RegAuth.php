<?php
require_once 'library/db_connect.php';

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 16.10.2016
 * Time: 12:34
 */
class RegAuth
{

    function getMD5Hash($str)
    {
        $solt = "philipsTPV";
        // echo md5(md5($str.$solt).$solt);
        return md5(md5($str . $solt) . $solt);
    }


    function count_bad_auth_user()
    {
        if (isset($_SESSION['counts'])) {
        } else {
            $_SESSION['counts'] = 0;
        }
        $counts = $_SESSION['counts'];
        $counts++;
        $_SESSION['counts'] = $counts;
        return $counts;
    }


    function auth_user($email, $passw)
    {

        $db = DB::getInstance();

        $res = $db->prepare("SELECT * FROM t_users 
            WHERE email =:email 
            AND password = :passw
            AND status > 0");
        $res->bindParam(':passw', $this->getMD5Hash($passw));
        $res->bindParam(':email', $email);
        $res->execute();

        if ($res->rowCount() == 1) {
            $row = $res->fetch();
            $_SESSION['user_id'] = $row['id'];
            return true;
        } else {
            return false;
        }
    }

    function logout_user()
    {
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
    }

    // проверка разных типов авторизации пользователя
    function isAuthUser()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    function getUserSaveData($idUser)
    {
        $db = DB::getInstance();

        $res = $db->prepare("SELECT * FROM t_data  WHERE id_user =:id_user");
        $res->bindParam(':id_user', $idUser);
        $res->execute();
        if ($res->rowCount() == 1) {
            $result = $res->fetch(PDO::FETCH_ASSOC);
            $data['dateTime'] = $result['dateTime'];
            $data['models'] = json_decode($result['models'], true);
            $data['links'] = json_decode($result['links'], true);
            $data['comments'] = json_decode($result['comments'], true);
            $data['replies'] = json_decode($result['replies'], true);
            return $data;
        }
        return false;
    }

    function saveUserData($idUser, $data)
    {
        $models = json_encode($data['models']);
        $links = json_encode($data['links']);
        $comments = json_encode($data['comments']);
        $replies = json_encode($data['replies']);

        $db = DB::getInstance();

        $res = $db->prepare("SELECT * FROM t_data  WHERE id_user =:id_user");
        $res->bindParam(':id_user', $idUser);
        $res->execute();
        if ($res->rowCount()) {
            $upRes = $db->prepare("UPDATE `t_data` 
                                    SET `links`=:links,`models`=:models, `comments`=:comments,
                                        `replies`=:replies, `dateTime` =:dateTime
                                    WHERE `id_user`=:id_user");
            $upRes->bindParam(':id_user', $idUser);
            $upRes->bindParam(':models', $models);
            $upRes->bindParam(':links', $links);
            $upRes->bindParam(':comments', $comments);
            $upRes->bindParam(':replies', $replies);
            $upRes->bindParam(':dateTime', date('Y-m-d H:i:s'));
            $upRes->execute();
        } else {
            $insRes = $db->prepare("INSERT INTO `t_data`(`id_user`, `links`, `models`, `comments`, `replies`)
                                      VALUES (:id_user, :links, :models, :comments, :replies)");
            $insRes->bindParam(':id_user', $idUser);
            $insRes->bindParam(':models', $models);
            $insRes->bindParam(':links', $links);
            $insRes->bindParam(':comments', $comments);
            $insRes->bindParam(':replies', $replies);
            $insRes->execute();
        }


    }

    function getUserById($idUser)
    {

        $db = DB::getInstance();

        $res = $db->prepare("SELECT * FROM t_users  WHERE id =:id");
        $res->bindParam(':id', $idUser);
        $res->execute();

        // если такой пользователь нашелся
        if ($res->rowCount()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

}


