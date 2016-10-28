<?php

/**
 * Description of Form
 *
 * @author alex
 */
class Form
{
    /**
     * Form elements
     * @var array
     */
    var $fields = array();

    var $values = array();

    /**
     * Custom form-level error messages
     * @var array
     */
    var $errors = array();

    /**
     * constructor
     */
    function Form()
    {
        $this->init();
    }

    /**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    function init()
    {
        trigger_error('Not implemented', E_USER_ERROR);
    }

    /**
     * Adding field to Form
     *
     * @param Field $field
     * @return Counter
     */
    function addField(&$field)
    {
        $filedName = $field->getName();
        if (!empty($filedName)) {
            $this->fields[$filedName] = $field;
        }

        return $this;
    }

    /**
     * Returns validation errors
     *
     * @return array
     */
    function getErrors()
    {
        return $this->errors;
    }

    /**
     * returns form fields
     *
     * @return array
     */
    function getFields()
    {
        return $this->fields;
    }

    /**
     * Returns field by name
     *
     * @param string $name
     * @return Field or null if field does not exists
     */
    function getField($name)
    {
        if(array_key_exists($name, $this->fields)){
            return $this->fields[$name];
        }

        return null;
    }

    /**
     * Retrieve all form element values
     *
     * @return array
     */
    function getValues()
    {
        return $this->values;
//        $values = array();
//        foreach($this->fields as $field){
//            $values[$field->getName()] = $field->getValue();
//        }
//
//        return $values;
    }

    /**
     * Retrieve value for single element
     *
     * @param  string $name
     * @return mixed
     */
    function getValue($name)
    {
        if(array_key_exists($name, $this->values)){
            return $this->values[$name];
        }
//        if(($field = $this->getField($name))){
//            return $field->getFilteredValue();
//        }

        return null;
    }

    /**
     * Validate the Form
     *
     * @param array $data
     * @return boolean
     */
    function isValid($data)
    {
        $valid = true;
        foreach ($this->fields as $field) {
            if (!isset($data[$field->getName()]) || $data[$field->getName()] === '') {
                if ($field->isRequired()) {
                    $valid = false;
                    $this->errors[$field->getName()][] = $field->getName(). ' это поле обязательно для заполнения' ;
                }
                $this->values[$field->getName()] = '';
                continue;
            }
            $this->values[$field->getName()] = $data[$field->getName()];
            if (!$field->isValid($data[$field->getName()], $data)) {
                $valid = false;
                $this->errors[$field->getName()] = $field->getErrors();
            }
        }
        //echo"Страница library/Form.php ";
        //var_dump($this->errors);
        return $valid;
    }

    /**
     * Populate form
     *
     * @param array $values
     */
    function populate($values)
    {
        foreach($values as $name=>$value){
            if(!($field = $this->getField($name))){
                continue;
            }

            $field->setValue($value);
        }
    }
}

?>
