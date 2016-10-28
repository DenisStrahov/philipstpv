<?php
require_once 'library/db_connect.php';

if (session_id() == '') session_start();
$sid = session_id();

if (isset($_COOKIE['Time'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo "Все плохо Bad autorization, you mast wait 10 seconds";
    die;
}
require_once 'library/Form/Authorization.php';
require_once 'library/RegAuth.php';

$regauth = new RegAuth();
$regauth->logout_user(); // принудительное разлогирование пользователя, вышедшего на эту страницу
//echo $regauth->getMD5Hash('philipstpv1');
$errors = array();
$mod_errors = '';
$inputs = array();

$form = new Form_Authorization();

if (isset($_POST['submit'])) {
    if ($form->isValid($_POST)) {
        $values = $form->getValues();
        if ($regauth->auth_user($values['email'], $values['passw'])) {
            header('location: rozetka.php');
            exit;
        } else {
            if ($regauth->count_bad_auth_user() >= 3) {
                setcookie("Time", 1, time() + 20);
                unset($_SESSION['counts']);
                header('location: index.php');
                exit;
            };
            $mod_errors = 'Неправильный логин или пароль, после 3х неправидьных попыток авторизации вы будете вынуждены ожидать 10 секунд'.
                "<br/>у Вас осталось " . (3 - $_SESSION['counts']) . " неправильные попытки авторизации";
        }
    } else {
        $errors = $form->getErrors();
        $values = $form->getValues();
        //var_dump($errors);
    }
} else {
    $values['email'] = '';
    $values['passw'] = '';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Philips Rozetka</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<div class="container">
    <div class="row">
        <div class="container">
            <p class='text-danger'>
                <?php if(!empty($mod_errors)){echo $mod_errors;} ?>
            </p>
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4 ">
                    <img src="library/img/philips.png" alt="" class="img-rounded img-responsive center-block">
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <form class="form-horizontal form-signin" role="form" method="post">
                <?php if (!empty($errors)): ?>
<!--                    <div class="container">-->
                        <div class="row text-center">
                                <?php foreach($errors as $error){
                                         echo "<p class='text-danger'>$error[0]</p>";
                                      }
                                ?>
                        </div>
<!--                    </div>-->
                <?php endif; ?>
                <h2 class="form-signin-heading">Авторизируйтесь</h2>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control"
                               id="inputEmail3" name="email"
                               value="<?php echo $values['email']; ?>"
                               placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control"
                               id="inputPassword3" name="passw"
                               placeholder="Password">
                    </div>
                </div>
<!--                <div class="form-group">-->
<!--                    <div class="col-sm-offset-2 col-sm-10">-->
<!--                        <div class="checkbox">-->
<!--                            <label>-->
<!--                                <input type="checkbox"> Запомнить меня-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button name="submit" type="submit" class="btn btn-lg btn-primary btn-block">Войти</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
</html>