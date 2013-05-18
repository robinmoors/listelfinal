<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");
class ECP_FormObj_Select extends ECP_FormObj {

    protected $select = false;
    protected $options = false;
    protected $disabled = false;

    public function __CONSTRUCT($fieldname, $options = array(), $select = true) {
        $this->name = $fieldname;
        $this->select = $select;
        $this->options = $options;
        $this->disabled = array();
        if ($this->select) {
            $this->script = "1,999,false";
        } else {
            $this->script = "0,999,false";
        }

        $this->type = "select";
    }

    public function insertOptions($options = array()) {
        $this->options = $options;
        return $this;
    }

    public function disableOption($optionkey) {
        foreach ($this->options as $key => $value) {
            if ($optionkey == $key)
                $this->disabled[$key] = $value;
        }
        return $this;
    }

    public function validate() {
        if ($this->is_option($this->value) && $this->select) {
            return true;
        } elseif (!$this->select) {
            return true; //selectie moest niet gemaakt worden
        } else {
            return false;
        }
    }

    protected function is_option($value) {
        if (!$value)
            $value = 0;
        return array_key_exists($value, $this->options);
    }

    protected function is_disabled($optionkey) {
        if (array_key_exists($optionkey, $this->disabled))
            return true;
        else
            return false;
    }

    public function getHtml($formname, $class) {
        $html = "<span class='description {$class}'>{$this->placeholder}</span><select name='{$this->name}' class='{$class}'>";
        $first = false;
        $disabled = '';
        foreach ($this->options as $key => $value) {
            if ($this->is_disabled($key))
                $disabled = "disabled='disabled'";
            else
                $disabled = '';
            if (!$first && $disabled === '') { //kan zijn dat 1ste value geblokkeerd is en die mag dus niet selected zijn!
                $html.="<option value='$key' selected='selected'>$value</option>";
                $first = true;
            }
            else
                $html.="<option value='$key' $disabled>$value</option>";
        }
        $html.="</select><span id='{$formname}{$this->name}'></span></br>";
        return $html;
    }

}

class ECP_FormObj_Radio extends ECP_FormObj_Select {

    public function __CONSTRUCT($fieldname, $options = array(), $select = true) {
        parent::__CONSTRUCT($fieldname, $options, $select);
        $this->type = "radio";
    }

    public function getHtml($formname, $class) {
        $html = "<span class='description {$class}'>{$this->placeholder}</span>";
        $first = true;
        foreach ($this->options as $key => $value) {
            if ($first) {
                $first = false;
                $html.="<input type='radio' name='{$this->name}' value='$key' class='{$class}' checked='checked'>$value<br/>";
            }
            else
                $html.="<input type='radio' name='{$this->name}' value='$key' class='{$class}'>$value<br/>";
        }
        $html.="<span id='{$formname}{$this->name}'></span></br>";
        return $html;
    }

}

class ECP_FormObj_Hulpverlener extends ECP_FormObj_Input {

    public function getHtml($formname, $class) {
        return "<input type='text' name='{$this->name}' value='' placeholder='{$this->placeholder}' 
            onkeyup='' 
            onclick='' 
            class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}
?>
