<?php
require_once 'library/Validator.php';
/**
 * Description of Regex
 *
 * @author alex
 */
class Validator_Regex extends Validator
{
    var $pattern;

    function Validator_Regex($options = null)
    {
        parent::Validator($options);

        if(!array_key_exists('pattern',$options)){
            die('Invalid options passed into Validator_Regex::construct()');
        }

        $this->pattern = $options['pattern'];
    }

    function isValid($value, $context = null)
    {
        $this->value = $value;
        $status = @preg_match($this->pattern, $value);

        if (!$status) {
            return false;
        }

        return true;
    }
}

?>
