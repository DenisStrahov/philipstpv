<?php

/**
 * Description of Field
 *
 * @author alex
 */
class Field
{
    var $name;
    var $value      = null;
    var $filteredValue = null;
    var $validators = array();
    var $filters    = array();
    var $errors     = array();
    var $required   = false;

    function Field($name)
    {
        $this->name = $name;
    }

    function getName()
    {
        return $this->name;
    }

    function setRequired($flag = true)
    {
        $this->required = $flag;
        return $this;
    }

    function isRequired()
    {
        return $this->required;
    }

    function addValidator(&$validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    function addFilter(&$filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    function getErrors()
    {
        return $this->errors;
    }

    function isValid($value, $context = null)
    {
        foreach ($this->filters as $filter) {
            $value = $filter->filter($value);
        }

        $this->value = $value;
        $this->filteredValue = $value;

        $valid = true;

        foreach ($this->validators as $validator) {
            if (!$validator->isValid($this->filteredValue, $context)) {
                $valid = false;
                $this->errors[] = $validator->getMessage();
            }
        }

        return $valid;
    }

    function getFilteredValue()
    {
        return $this->filteredValue;
    }

    function getValue()
    {
        return $this->value;
    }

    function setValue($value)
    {
        $this->filteredValue = (string) $value;
        $this->value = (string) $value;
    }

    function __toString()
    {
        $value = '';
        if($this->value){
            $value = $this->value;
        }
        $str = $this->name . ": ";
        $str .= '<input type="text" name="'.$this->name.'" value="'. $value .'"><br />';

        if(!empty($this->errors)){
            $str .= '<div class="errors">';
            foreach($this->errors as $error){
                $str .= $error;

            }
            $str .= '</div>';

        }
        return $str;
    }

}

?>
