<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of specialobjects
 *
 * @author robindell
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_FormObj_Email extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false) {
        parent::__CONSTRUCT($fieldname);
        $this->script = "'x','x','email'";
        $this->type = "email";
    }
    
    
    /**
     * Vul de waarde van het veld in.
     * Omdat het om een email gaat wordt er meteen gefilterd op email-karakters!
     * @param mixed $value
     * @return \ECP_FormObj_Email
     */
    public function insert($value){
        $this->value = filter_var($value,FILTER_SANITIZE_EMAIL);
        return $this;
    }

    public function validate() {
        $validation = filter_var($this->value, FILTER_VALIDATE_EMAIL);
        if($validation===false) $this->msg="match"; //indien leeg is $validation null
        return $validation;
    }

    public function getHtml($formname, $class) {
        return "<input type='email' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_Date extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false, $minlength = 0, $maxlength = 30) {
        parent::__CONSTRUCT($fieldname, 8, 10);
        $this->type = "date";
        $this->script = "8,10,'date'";
    }

    public function validate() {
        $reg = '/^([1-9]|0[1-9]|[12][0-9]|3[01])([/])([1-9]|0[1-9]|1[012])\2(19|20)\d\d$/';
        if (!preg_match($reg, $this->value)) {
            $this->msg = "match";
            return false;
        }
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='text' maxlength='11' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br></br>";
    }

}



class ECP_FormObj_Telefoon extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false, $minlength = 11, $maxlength = 15) {
        parent::__CONSTRUCT($fieldname, $minlength, $maxlength);
        $this->type = "telephone";
        $this->script = "11,15,'telephone'";
    }

    public function validate() {
        $reg = '/^(0)([1-35-9][0-9]|[4][0-9][0-9])[/]((\d\d\d[.]\d\d\d)|(\d\d[.]\d\d[.]\d\d))$/';
        if (!preg_match($reg, $this->value)) {
            $this->msg = "match";
            return false;
        }
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='text' maxlength='15' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'
            onfocus=''
            onchange=''
            /><span id='{$formname}{$this->name}'></span><br></br>";
    }

}

class ECP_FormObj_Postcode extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false) {
        parent::__CONSTRUCT($fieldname, 4, 4);
        $this->type = "postal";
        $this->script = "4,4,'postal'";
    }

    /**
     * Vul de waarde van het veld in.
     * Vermits het om een postcode gaat wordt meteen gefilterd op integers
     * @param mixed $value
     * @return \ECP_FormObj_Postcode chainability
     */
    public function insert($value){
        $this->value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return $this;
    }
    
    /**
     * Valideer op geldige postcode (alleen op vorm, niet op effectief bestaande nummers!)
     * @return boolean true bij goed, false bij slecht
     */
    public function validate() {
        $int_options = array("options"=>array("min_range"=>1000, "max_range"=>9999));
        $validation = filter_var($this->value, FILTER_VALIDATE_INT, $int_options);
        if($validation===false) $this->msg="match"; //indien leeg is $validation null
        return $validation;
    }

    public function getHtml($formname, $class) {
        return "<input type='text' maxlength='4' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'
            onfocus=''
            onmouseup=''
            onkeyup=''
            /><span id='{$formname}{$this->name}'></span><br></br>";
    }

}

?>
