<?php
require_once 'library/Validator.php';

/**
 * Description of EmailAddress
 *
 * @author alex
 */
class Validator_EmailAddress extends Validator
{
    var $message = '"%value%" адрес электронного ящика имеет неправильный формат';
    var $pattern = '/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/i';

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
