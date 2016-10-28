<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 13.09.2016
 * Time: 9:40
 */
require_once 'library/ViewPage.php';

if(session_id() == '') session_start();


require_once 'library/RegAuth.php';

$regauth = new RegAuth();
if(!$regauth->isAuthUser()){
    header('location: index.php');
    exit;
}

$savedData = $regauth->getUserSaveData($_SESSION['user_id']);
$page = new ViewPage($savedData);

if (!empty($_POST) AND  isset($_POST['save'])){
    $regauth->saveUserData($_SESSION['user_id'], $page->getAllData());
    header('location: rozetka.php');
    exit;
}
if (!empty($_POST) AND  isset($_POST['quit'])){
    $regauth->logout_user();
    header('location: index.php');
    exit;
}
if (!empty($_POST) AND isset($_POST['modelValue']) AND isset($_POST['model'])){
    $model = json_decode($_POST['modelValue']);
    $page->view('model',$model);
} else{
    $page->view('list');
}