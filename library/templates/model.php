<link href="css/bootstrap.css" rel="stylesheet">
<div class="container">
    <div class="row">
        <form action="rozetka.php" method="post">
            <input type="submit" class="btn btn-default" name="re" value="Назад к списку">
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="container">
            <div class="row">
                <h2 class="text-center">Обработанная оригинальная страница</h2>
            </div>
        </div>

        <?php
        /**
         * Created by PhpStorm.
         * User: Denis
         * Date: 17.10.2016
         * Time: 22:56
         */
        $patterns = array();
        $replacements = array();
        $model = $arguments;
        if (isset($model->newReplies)) {
            foreach ($model->newReplies as $i => $word) {
                $patterns[] = $word;
                $replacements[] = "<span style='background-color: #ff6600'>$word</span>";
            }
        }

        if (isset($model->newComments)) {
            foreach ($model->newComments as $i => $word) {
                $patterns[] = $word;
                $replacements[] = "<span style='background-color: #ff9999'>" . $word . "</span>";
            }
        }

        header('Content-Type: text/html; charset=utf-8');

        $string = file_get_contents($model->link);
        if (!empty($patterns) OR !empty($replacements)) {
            $page = str_replace($patterns, $replacements, $string);
            echo $page;
        }
        echo $string;
        ?>
    </div>
</div>