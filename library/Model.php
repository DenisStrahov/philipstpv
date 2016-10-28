<?php

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 23.10.2016
 * Time: 7:55
 */
class Model
{
    public $index;
    public $link ;
    public $name;
    public $newModel;
    public $replies = array();
    public $comments = array();
    public $newReplies = array();
    public $newComments = array();
//    public $newLinks = array();

    /**
     * @return array
     */
    public function __construct($index, $link, $name)
    {
        $this->index = $index;
        $this->link = $link;
        $this->name = $name;
    }

}