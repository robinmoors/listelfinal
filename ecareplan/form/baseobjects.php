<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");
class ECP_FormObj {

    protected $name;
    protected $value;
    protected $type;
    protected $msg = "";
    protected $placeholder;
    protected $script;

    public function __CONSTRUCT($fieldname = false) {
        if (!$fieldname)
            $this->name = "field";
        else
            $this->name = $fieldname;

        $this->type = "input";
    }

    /**
     * Vul de waarde van het veld in.
     * Om de veiligheid te verhogen wordt de input meteen gefilterd met sanitize_full_special_chars
     * @param mixed $value
     * @return \ECP_FormObj chainability
     */
    public function insert($value) {
        $this->value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);   //trim($value);
        echo "value::".$this->value;
        return $this;
    }

    public function validate() {
        return true;
    }

    public function __GET($name) {
        switch ($name) {
            case "msg": return $this->msg;
                break;
            case "name": return $this->name;
                break;
            case "script": return $this->script;
                break;
            case "value": return $this->value;
                break;
            case "type": return $this->type;
            default:
                return false;
        }
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return true;
    }

    public function getHtml($formname, $class) {
        return "";
    }

}

class ECP_FormObj_Input extends ECP_FormObj {

    protected $minlength;
    protected $maxlength;

    public function __CONSTRUCT($fieldname = false, $minlength = 0, $maxlength = 30) {
        parent::__CONSTRUCT($fieldname);
        $this->minlength = $minlength;
        $this->maxlength = $maxlength;
        $this->script = "$minlength,$maxlength,false";
    }

    public function validate() {
        if (strlen($this->value) > $this->maxlength) {
            $this->msg = "long";
            return false;
        }if (strlen($this->value) < $this->minlength) {
            $this->msg = "short";
            return false;
        }
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='text' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_Checkbox extends ECP_FormObj {

    protected $checked = false;
    protected $tobechecked = false;

    public function __CONSTRUCT($fieldname, $checked = false, $tobechecked = false) {
        $this->name = $fieldname;
        $this->checked = $checked;
        $this->script = "0,999,false";
        $this->tobechecked = $tobechecked;

        $this->type = "checkbox";
    }
    
    /**
     * Voeg de waarde van dit veld toe.
     * Omdat het om een checkbox gaat wordt die waarde meteen gevalideerd met een booleanfilter.
     * @param mixed $value true, 1, yes of false,0, no
     * @return \ECP_FormObj_Button chainability
     */
    public function insert($value){
        $this->value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }

    public function validate() {
        if ($this->value === true && $this->tobechecked)
            return true;
        else if ($this->value === false && !$this->tobechecked)
            return true;
        else
            return false;
    }

    public function getHtml($formname, $class) {
        $html = "<span class='description {$class}'>{$this->placeholder}</span><input type='checkbox' name='{$this->name}' value='{$this->value}' class='{$class}'>"
                . "<span id='{$formname}{$this->name}'></span></br>";
        return $html;
    }

}

class ECP_FormObj_Password extends ECP_FormObj_Input {

    public function getHtml($formname, $class) {
        return "<input type='password' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_Button extends ECP_FormObj {

    private $text;

    public function __CONSTRUCT($text) {
        $this->name = "submit";
        $this->text = $text;
        $this->script = "0,99,false";
        $this->type = "button";
    }
    
    /**
     * Voeg waarde toe aan het buttonveld.
     * Vermits het om een knop gaat hoeven we geen waarde te hebben.
     * Om de veiligheid te verhogen wordt deze methode overschreven.
     * @return \ECP_FormObj_Button chainability
     */
    public function insert(){
        $this->value = $this->text;
        return $this;
    }

    public function validate() {
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='button' id='{$formname}-form' name='{$this->name}' value='{$this->text}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_NormalButton extends ECP_FormObj_Button {

    public function __CONSTRUCT($name, $text) {
        $this->name = $name;
        $this->text = $text;
        $this->script = "0,99,false";
        $this->type = "button";
    }

    public function getHtml($formname, $class) {
        return "<input type='button' id='{$formname}-{$this->name}' name='button' value='{$this->text}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}
?>
