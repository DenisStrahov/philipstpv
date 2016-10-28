<?php

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 16.10.2016
 * Time: 19:38
 */
require_once 'library/Model.php';


class ViewPage
{
    const REG_REPLIES = "~<div class=\"pp-replies-text\">(.*?)</div~usi";
    const REG_СOMMENTS = "~<div class=\"pp-review-text-i\">(.*?)</div~usi";
    const REG_СOMMENTS_FULL = '~<article class="pp-review-i"(.*?)</article>~usi';
    const REG_MODElS = '~philips_([\d]{2}p[a-z]{2}[_]?[\d]{4})_(12||88)/(p[\d]{5,10})/comments/(#t_comments)?~u';
    const REG_LINKS = '|href="([^"]+philips_[^"]+comments[/]?)"|u';
    const PATH = 'http://rozetka.com.ua/all-tv/philips/c80037/v023/';
    public $content;
    public $models = array();
    public $dateTime;
    public $modelsObj = array();
    public $replies = array();
    public $comments = array();
    public $links = array();
    public $newModels = array();
    public $newReplies = array();
    public $newComments = array();
    public $newLinks = array();

    function __construct($savedData)
    {
        $this->content = @file_get_contents(self::PATH);

            $this->getLinks();
            $this->getModels();
            $this->getComments();
        if ($savedData){
            $this->dateTime = $savedData['dateTime'];
            $this->chekChanges($savedData);
        }

    }

    function __call($name, $array)
    {
        return true;
    }

    function getContent()
    {
        return $this->content;
    }

    function getAllData()
    {
        $data['links'] = $this->links;
        $data['models'] = $this->models;
        $data['comments'] = $this->comments;
        $data['replies'] = $this->replies;
        return $data;
    }

    function getLinks()
    {
        if (!$this->content)
            return false;
        preg_match_all(self::REG_LINKS, $this->content, $links);
        unset($links[0]);
        foreach ($links[1] as $key => $link) {
            $this->links[$key] = $link;
        }
        return $this->links;
    }

    function getModels()
    {
        if (!$this->content OR !$this->links)
            return false;
        $modelsMass = $this->models;
        $links = $this->links;
        for ($i = 0, $count = count($links); $i < $count; $i++) {
            preg_match(self::REG_MODElS, $links[$i], $modelsMass[$i]);
            unset($modelsMass[$i][0], $modelsMass[$i][3]);
            if (isset($modelsMass[$i][4])) {
                unset($modelsMass[$i][4]);
            }
            $models[$i] = implode('/', $modelsMass[$i]);
        }
        $this->models = $models;
        return $this->models;
    }

    function getComments()
    {
        if (!$this->links)
            return false;
        $links = $this->links;
        $replies = $this->replies;
        $comments = $this->comments;
        $fullCommentsMass = array();
        $textComments = array();
        $textReplies = array();

        //получение полных комментариев
        for ($i = 0, $count = count($links); $i < $count; $i++) {
            $contentComments[$i] = file_get_contents($links[$i]);
            preg_match_all(self::REG_СOMMENTS_FULL, $contentComments[$i], $fullCommentsMass[$i]);
            unset($fullCommentsMass[$i][1]);
            $fullComments[$i] = $fullCommentsMass[$i][0];

            //если нет ниодного комментария
            if (count($fullComments[$i]) == 0) {
                $replies[$i] = array();
                $comments[$i] = array();
                continue;
            }
            $replies[$i] = array();
            //парсинг полных комментариев и ответов на комментарии
            for ($j = 0, $countJ = count($fullComments[$i]); $j < $countJ; $j++) {
                preg_match_all(self::REG_СOMMENTS, $fullComments[$i][$j], $textComments[$i][$j]);
                unset($textComments[$i][$j][0]);
                foreach ($textComments[$i][$j][1] as $textCommentsSingl) {
                    $comments[$i][] = trim($textCommentsSingl);
                }
                //парсинг ответов на комментарии
                preg_match_all(self::REG_REPLIES, $fullComments[$i][$j], $textReplies[$i][$j]);
                unset($textReplies[$i][$j][0]);
                foreach ($textReplies[$i][$j][1] as $textRepliesSingl) {
                    $replies[$i][] = trim($textRepliesSingl);
                }
            }
        }
        $this->comments = $comments;
        $this->replies = $replies;
        $data = array(
            'replies' => $this->replies,
            'comments' => $this->comments,
        );
        return $data;
    }

    function chekChanges($savedData)
    {
        $resultcheck = true;
        foreach ($this->getAllData() as $keyData => $valueData) {
            $method = 'valid' . ucfirst($keyData);
            if (!$this->$method($savedData[$keyData], $savedData['models'])) {
                $resultcheck = false;
            }
        }
    }
//    function validLinks($valueData, $savedData){
//            var_dump($valuedata);
//            var_dump($savedData);
//        }
    function validModels($savedData, $savedModels)
    {
        foreach ($this->models as $keyModel => $valueModel) {
            $modelName = 'model' . $keyModel;
            $$modelName = new Model($keyModel, $this->links[$keyModel], $valueModel);
            $this->modelsObj[$keyModel] = $$modelName;
            if (array_search($valueModel, $savedData, true) === false) {
                $$modelName->newModel = $valueModel;
            }
        }
    }

    function validComments($savedData, $savedModels)
    {
        $valueData = $this->modelsObj;
        foreach ($valueData as $model) {
            $keyModel = $model->index;
            $valueModel = $model->name;
            $index = array_search($valueModel, $savedModels, true);
            //если модели нет с сохраненных данных все комментарии новые
            if ($index === false) {
                $model->newComments = $this->comments[$keyModel];
            } else {
                if (is_array($this->comments[$keyModel]) and !empty($savedData[$index])) {
                    foreach ($this->comments[$keyModel] as $keyCom => $valueComment) {
                        if (array_search($valueComment, $savedData[$index], true) === false) {
                            $model->newComments[] = $valueComment;
                        }
                    }
                }
            }
        }
    }

    function validReplies($savedData, $savedModels)
    {
        $valueData = $this->modelsObj;
        foreach ($valueData as $model) {
            $keyModel = $model->index;
            $valueModel = $model->name;
            $index = array_search($valueModel, $savedModels, true);
            //если модели нет с сохраненных данных все комментарии новые
            if ($index === false) {
                $model->newReplies = $this->replies[$keyModel];
            } else {
                if (is_array($this->replies[$keyModel]) and !empty($savedData[$index])) {
                    foreach ($this->replies[$keyModel] as $keyCom => $valueComment) {
                        if (array_search($valueComment, $savedData[$index], true) === false) {
                            $model->newReplies[] = $valueComment;
                        }
                    }
                }
            }
        }
    }

    function view($tamplate, $arguments = null)
    {
        switch ($tamplate) {
            case 'list':
                require_once "library/templates/$tamplate.php";
                break;
            case 'model':
                //здесь создние создние объекта model
                require_once "library/templates/$tamplate.php";
                break;
        }
    }
}