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

    public function validate() {
        $reg = '/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/';
        if (!preg_match($reg, $this->value)) {
            $this->msg = "match";
            return false;
        }
        return true;
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
        return "<input type='text' maxlength='11' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'
            onchange='EQ.formCheckDate();'
            /><span id='{$formname}{$this->name}'></span><br></br>";
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

    public function validate() {
        $reg = '/^[1-9]\d{3}$/';
        if (!preg_match($reg, $this->value)) {
            $this->msg = "match";
            return false;
        }
        return true;
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
