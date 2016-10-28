<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 17.10.2016
 * Time: 22:56
 */
$models = $this->modelsObj;
$comments = $this->comments;
$dateTime = $this->dateTime;
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
<body>
<?php //var_dump($comments);?>
<form action="" method="post">
    <div class="container">
        <div class="row">
            <br/>
            <table>
                <tr>
                    <td style="padding: 5px">
                        <input style="float: left" class="btn btn-default" type="submit" name="save" value="Сохранить">
                    </td>

                    <td style="padding: 5px">
                        <input type="submit" class="btn btn-default" name="re" value="Обновить">
                    </td>

                    <td style="padding: 5px">
                        <input type="submit" class="btn btn-default" name="quit" value="Выход">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
<div class="container">
    <div class="row">
        <h1>Модели Philips на розетке</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <TABLE class="table table-hover table-bordered">
            <?php if (!$models): ?>
                <TR>
                    <TD align='center'>
                        <h2>Сохраненных данных нет</h2>
                    </TD>
                </TR>
            <?php endif; ?>
            <?php if ($models): ?>
            <TR class="active">
                <TD width='60px' align='center'>Номер</TD>
                <TD width='110px' align='center'>Модель (прямая ссылка)</TD>
                <TD width='70px' align='center'>комментарии</TD>
                <TD width='170px' align='center'>статус сравнения на дату : <?php echo $dateTime ?> </TD>
            </TR>
            <?php endif; ?>
            <?php foreach ($models as $model): ?>
                <TR>
                    <TD width='60px' align='center'>
                        <?php echo($model->index + 1); ?>
                    </TD>
                    <TD width='110px' align='center'>
                        <a href="<?php echo $model->link; ?>" target="_blank">
                            <?php echo substr($model->name, 0, -3); ?>
                        </a>
                    </TD>
                    <TD width='70px' align='center'>
                        <?php echo (!empty($comments[$model->index])) ? 'есть' : 'нет'; ?>
                    </TD>
                    <TD width='170px' align='center'>
                        <?php
                        $buttomText = array();
                        empty($model->newModel) ?: $buttomText[] = 'Модель';
                        empty($model->newComments) ?: $buttomText[] = 'Комментарии';
                        empty($model->newReplies) ?: $buttomText[] = 'Ответы';
                        $buttomText = implode(', ', $buttomText);
                        ?>
                        <?php if (!empty($buttomText)): ?>
                            <form action="" method="post">
                                <input name="modelValue" type="hidden"
                                       value="<?php echo htmlspecialchars(json_encode($model)); ?>">
                                <input type="submit" class="btn btn-default" name="model"
                                       value="<?php echo $buttomText; ?>">
                            </form>
                        <?php endif; ?>
                    </TD>
                </TR>
            <?php endforeach; ?>
        </TABLE>
    </div>
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.js"></script>
</html>