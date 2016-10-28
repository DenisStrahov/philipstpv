<?php

//require_once 'Validator/Interface.php';

/**
 * Base Validator class
 *
 * @author alex
 */
class Validator
{
    var $value;
    var $message = '';
    var $_value_ = '%value%';

    /**
     * Constructor
     *
     * @param array $options
     */
    function Validator($options = null)
    {
        if (isset($options['message'])) {
            $this->message = $options['message'];
        }
    }

    /**
     * Returns validation error
     *
     * @return string
     */
    function getMessage()
    {
        return str_replace('%value%', $this->value, $this->message);
    }

    /**
     * Validate the value
     * Abstract method
     *
     * @param type $value
     */
    function isValid($value, $context = null)
    {
        die('it\'s a abstract method');
    }

}

?>
