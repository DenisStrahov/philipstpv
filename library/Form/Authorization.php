<?php
require_once 'library/Form.php';
require_once 'library/Field.php';
require_once 'library/Validator/EmailAddress.php';
require_once 'library/Validator/Regex.php';

/**
 * Description of Authorization
 *
 * @author veber
 */
class Form_Authorization extends Form{
    
function init(){
        
        $email = new Field('email');
        $email->setRequired();
        $email->addValidator(new Validator_EmailAddress());
        
        $passw = new Field('passw');
        $passw->setRequired();
        $passw->addValidator(new Validator_Regex(
                        array(
                            'pattern' => '/^.{6,}$/',
                            'message' => 'Минимальная длина пароля 6 символов'
                        )
                )
        );         

        $this->addField($email);  
        $this->addField($passw);  
    }
    
    
    
}

?>
