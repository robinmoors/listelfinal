<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Form extends ECP_Object implements ECP_FactoryInterface {

    private $names = array();
    private $obj = array();
    private $name = "";
    private static $eqmessages = "EQ.messages = {
                    'form-length-short':'Te kort',
                    'form-length-max':'Te lang',
                    'form-match-email':'Dit is geen emailadres!',
                    'form-match-date':'Dit is geen datum 'dd/mm/jjjj',
                    'form-match-telephone':'Dit is geen telefoon 0xx/xx.xx.xx of gsm 04xx/xxx.xxx',
                    'form-match-postal':'Dit is geen postcode xxxx',
                    'form-nocopy':'Komt niet overeen!',
                    'form-uncomplete':'Niet volledig!',
                    'form-complete':'Ok!',
                    'form-wrong':'Ongeldig!'
                }";
    private $iterator;
    private static $instances = array();

    public function __construct($formname = false) {
        if (!$formname)
            $this->name = "form";
        else
            $this->name = $formname;

        $this->iterator = -1;
        return $this;
    }

    public static function getInstance($formname = false) {
        ecpimport("form.baseobjects");
        ecpimport("form.listobjects");
        ecpimport("form.specialobjects");
        if (!$formname)
            $formname = "form";
        if (empty(self::$instances[$formname])) {
            self::$instances[$formname] = new ECP_Form($formname);
        }
        return self::$instances[$formname];
    }

    public function __GET($fieldname) {
        if (in_array($fieldname, $this->names))
            return $this->obj[$fieldname];
        elseif ($fieldname == "name") {
            return $this->name;
        }
        else
            return null;
    }

    /**
     * Haalt een opeenvolgend veld op, te gebruiken in combinatie met de iterate methode!
     * @see iterate
     * @return ECP_FormObj
     */
    public function getField() {
        return $this->obj[$this->names[$this->iterator]];
    }

    /**
     * Itereert doorheen het objecten array van dit formulier
     * Bvb while($form->iterate()){$field = getField(); //doe iets}
     * @return boolean true bij volgende waarde, false bij einde iteratie, null wanneer er geen velden bestaan (form is leeg)
     */
    public function iterate() {
        $this->iterator++;
        if (array_key_exists($this->iterator, $this->names))
            return true;
        else {
            $this->iterator--;
            if ($this->iterator === -1)
                return null; //betekent dat er geen velden zijn in dit formulier!
            else
                return false;
        }
    }

    /**
     * Reset de iterator zodat er opnieuw kan geïtereerd worden
     * @return \ECP_Form het object voor chainability
     */
    public function resetIterator() {
        $this->iterator = -1;
        return $this;
    }

    /**
     * Haalt de namen op van de velden in dit formulier
     * @return array(fieldnames) array met de namen van de velden
     */
    public function getNames() {
        return $this->names;
    }

    public function __toString() {
        return "" . print_r($this->names);
    }

    /**
     * @see addField
     */
    public function insertField(ECP_FormObj $field){
        return $this->addField($field);
    }
    
    /**
     * Voeg een veld voor of na een ander veld in :)
     * @param ECP_FormObj $field Het in te voegen veld
     * @param ECP_FormObj|veldnaam $fieldone Het veld dat de positie aangeeft.
     * @param boolean $before Moet het ervoor of erachter? standaard erachter (=sneller)
     * @param boolean $returnbool Moet het object zelf of een true of false? standaard object zelf
     * @return boolean|\ECP_Form Het eigenobject voor chainability, of naar wens een true bij positieve insert of false..
     */
    public function insertInBetween(ECP_FormObj $field, $fieldone, $before = false,$returnbool = false){
        $returnfalse = $returnbool ? false : $this;
        $returntrue = $returnbool ? true : $this;
        if ($fieldone instanceof ECP_FormObj) {
            $indexone = array_search($fieldone->name, $this->names);
        } else {
            $indexone = array_search($fieldone, $this->names);
        }
        if (!$indexone || !$indextwo) {
            return $returnfalse;
        } else {
            $this->addField($field); //veld staat nu achteraan!
            //nu dat veld op de juiste plaats wisselen :)
            for($i=count($this->names); $i>1; --$i){
                if(!$before && $i-1 == $indexone[0]){
                    return $returntrue; //het in te voegen veld staat achter opgegeven veld :)
                    //nu nog een switch doen zou betekenen dat het ervoor staat :(
                }
                //i wordt eerst kleiner alvorens de lus te beginnen :)
                $this->switchFields($this->names[$i-1], $this->names[$i]);
                if($before && $i-1 == $indexone[0]){ //het in te voegen veld staat nu voor het opgegeven veld :)
                    return $returntrue;
                }
            }
        }
    }
    /**
     * Voeg een veldobject toe aan dit formulier
     * @param ECP_FormObj $field Het veldobject
     * @return \ECP_Form Het formulierobject voor chainability
     */
    public function addField(ECP_FormObj $field) {
        array_push($this->names, $field->name);
        $this->obj[$field->name] = $field;
        return $this;
    }

    /**
     * Verwijderd een veld. Je kan het object meegeven ofwel de naam van het veld.
     * Daarnaast kan je kiezen wat gereturned word. een true of false of gewoon $this
     * Standaard wordt $this meegegeven voor chainability.
     * 
     * @param ECP_FormObj|string $field Veldobject ofwel de naam
     * @param boolean $returnbool standaard false, return een bool of gewoon $this
     * @return boolean|\ECP_Form afhankelijk van de keuze bij $returnbool true of false of $this
     */
    public function removeField($field, $returnbool = false) {
        $returnval = $returnbool ? true : $this;
        if ($field instanceof ECP_FormObj) {
            if (array_key_exists($field, $this->obj[$field->name])) {
                unset($this->obj[$field->name]); //veld verwijderen
                $index = array_search($field->name, $this->names);
                unset($this->names[$index[0]]);
                //nu de iteratie controleren...
                if ($index[0] == $this->iterator) {
                    if ($this->iterate())
                        return $returnval;
                    else {
                        $this->iterate--;
                        return $returnval;
                    }
                }
            } elseif ($returnbool) {
                return false;
            } else {
                return $this;
            }
        } else {
            if (array_key_exists($field, $this->obj[$field])) {
                unset($this->obj[$field]); //veld verwijderen
                $index = array_search($field, $this->names);
                unset($this->names[$index[0]]);
                //nu de iteratie controleren...
                if ($index[0] == $this->iterator) {
                    if ($this->iterate())
                        return $returnval;
                    else {
                        $this->iterate--;
                        return $returnval;
                    }
                }
            } elseif ($returnbool) {
                return false;
            } else {
                return $this;
            }
        }
    }

    /**
     * Verwissel 2 velden in dit formulier zodat ze bij de view ook in die volgorde uitkomen :)
     * Je kan dit doen door de namen op te geven of objecten. Zolang je die 2 dingen niet door elkaar doet!
     * @param ECP_FormObj|veldnaam $fieldone Veldobject of de naam ervan
     * @param ECP_FormObj|veldnaam $fieldtwo Veldobject of de naam ervan waarmee wordt gewisseld (indien bij 1 een object, hier ook object!)
     * @param boolean $returnbool Dit is standaard false, in dat geval wordt steeds $this terug gegeven anders true bij geslaagde wissel :)
     * @return boolean|\ECP_Form Het object ofwel een true bij geslaagde wissel of false bij fout.
     */
    public function switchFields($fieldone, $fieldtwo, $returnbool = false) {
        $returnfalse = $returnbool ? false : $this;
        $returntrue = $returnbool ? true : $this;
        if ($fieldone instanceof ECP_FormObj && $fieldtwo instanceof ECP_FormObj) {
            $indexone = array_search($fieldone->name, $this->names);
            $indextwo = array_search($fieldtwo->name, $this->names);
        } else {
            $indexone = array_search($fieldone, $this->names);
            $indextwo = array_search($fieldtwo, $this->names);
        }
        if (!$indexone || !$indextwo) {
            return $returnfalse;
        } else {
            $name = $this->names[$indexone[0]];
            $this->names[$indexone[0]] = $this->names[$indextwo[0]];
            $this->names[$indextwo[0]] = $name;
            return $returntrue;
        }
    }

    public function validate() {
        $error = array();
        $fields = array();
        $count = 0;
        $length = count($this->obj);
        foreach ($this->obj as $fieldobj) {
            if (!$fieldobj->validate()) {
                array_push($error, $fieldobj->msg);
                array_push($fields, $fieldobj->name);
                $count++;
            }
        }
        if ($count == 0)
            return true;
        else
            return array($fields, $error, $count);
    }

    public function insertValue($name, $value) {
        if (in_array($name, $this->names)) {
            $this->obj[$name]->insert($value);
            return true;
        }
        else
            return false;
    }

    public function smartInsert($array) {
        if (!is_array($array))
            return false;
        foreach ($array as $field => $value) {
            if (array_key_exists($field, $this->obj)) {
                $this->obj[$field]->insert($value);
            }
        }
        return $this;
    }

    public function getHtml($class = "default", $placeholders = array(), $nobreak = false) {
        if (!empty($placeholders)) {
            foreach ($this->obj as $key => $value) {
                $this->obj[$key]->setPlaceholder($placeholders[$key]);
            }
        }
        $html = "<form name='{$this->name}'><div class='centered'>";

        foreach ($this->obj as $value) {
            $html .= $value->getHtml($this->name, $class);
        }
        $html.="</div></form>";
        if ($nobreak) {
            $html = str_replace("<br/>", "", $html);
        }
        return $html;
    }

    /**
     * Javascript.
     * Genereert een kant en klaar scriptje om 1 enkel formuliertje te valideren en ook meteen door te sturen bij succes.
     * Daarna kan je meegeven wat er moet gebeuren indien de server positief antwoord geeft.
     * 
     * @param routing $route De routing-url waarnaar het formulier moet, Verplicht!
     * @param array(ovr teksten) $txt Een array met daarin tekst die in de popup zichtbaar wordt (zoals titel enzo.. "title"=>"titel van de box", "action"=>"Bezig met verzenden..")
     * @param javascript $succes Javascriptcode die uitgevoerd wordt bij succes van de aflevering 
     * @param javascript $fail Javascriptcode die uitgevoerd wordt bij foutmelding van de server
     * @param javascript $extra Javascriptcode die je kan gebruiken om meerdere meldingen van de server te kunnen begrijpen (iets anders dan succes=='positive')
     * @return javascript (altijd, dus opletten wat je ingeeft! fouten kunnen javascript-errors veroorzaken)
     */
    public function getScript($route, $txt = array(), $succes = "", $fail = "", $extra = "") {
        if (empty($txt)) {
            $txt["title"] = "Formulier";
            $txt["action"] = "Bezig met verzenden...";
        }$script.="
            var {$this->name}completion = function(){
                EQ.OVR = new EQ.overflow({
                    title:'{$txt["title"]}'
                });
                EQ.OVR.content = '{$txt["action"]}<br/><img src=\'/listel_new/lib/images/flat-loader.gif\' />';
                EQ.OVR.refresh('c').open();
                var pname = '{$this->name}completion';";
        for ($i = 0; $i < count($this->names); $i++) {
            if ($this->names[$i] != "submit")
                $script .="var {$this->names[$i]} = document.{$this->name}.{$this->names[$i]}.value.toString();";
        }
        $script .="
                EQ.CPU.makeProcess({
                    name: pname,
                    process: function(resp){
                        var json = EQ.jsp(resp);
                        if(json.error){
                            $('#{$this->name}'+json.error).html(EQ.messages['form-wrong']).removeClass('succes').addClass('wrong');
                            EQ.OVR.close();
                        }else if(json.succes){
                            if(json.succes=='positive'){
                                EQ.OVR.content='{$txt["succes"]}';
                                EQ.OVR.refresh('c');
                                EQ.login(json);
                                {$succes}
                            }
                            {$extra}
                            else{
                                EQ.OVR.content='{$txt["fail"]}';
                                EQ.OVR.refresh('c');
                                {$fail}
                            }
                        }
                        //laad ook userobjecten in..
                        }
                });
                EQ.CPU.newRequest({
                    process: pname,
                    url:'{$route}',
                    parameters:'";
        for ($i = 0; $i < count($this->names); $i++) {
            if ($this->names[$i] != "submit")
                $script.="{$this->names[$i]}='+{$this->names[$i]}";
            if (($i + 2) < count($this->names)) //1 veld is altijd ongeldig, daarom i+2 !!
                $script.="+'&";
        }
        $script.="
                });
                EQ.CPU.startProcess(pname);
            }
            $('#{$this->name}-form').bind('click',function(){
                var b = true;
                ";
        foreach ($this->obj as $value) {
            $script.= "if(!EQ.formCheck(document.{$this->name}.{$value->name},{$value->script},'{$this->name}')) b = false;
                ";
        }
        $script.="
                if(b){
                    $('#{$this->name}submit').removeClass('wrong').addClass('succes').html(EQ.messages['form-complete']);
                    {$this->name}completion();
                }
            });
            
           " . self::$eqmessages . "
         ";
        return $script;
    }

    /**
     * Genereert javascript-code.
     * Maakt een "collector" aan van alle velden die er in alle formulieren staan.
     * De waarden van elk veld komt zo in een object te staan met de naam van het veld.
     * Je moet ofwel 1 enkel form doorgeven ofwel een array van forms.
     * @param array(ECP_Form) $forms Array met ECP_Form objecten, Verplicht!
     * @return javascript of niets indien foute opgave array
     */
    public static function generateCollector($forms = array()) {
        if (!is_array($forms) && $forms instanceof ECP_Form) {
            $formar = array($forms);
        } elseif (!is_array($forms)) {
            return "";
        } else {
            $formar = $forms;
        }
        $script = "valueCollector = function(){
                    return {
                    ";
        foreach ($formar as $form) {
            $names = $form->getNames();
            foreach ($names as $name) {
                switch ($form->$name->type) {

                    case "radio":
                        $script .= "$name : EQ.radioValue(document.{$form->name}.$name),
                                ";
                        break;
                    case "checkbox":
                        $script .= "$name : document.{$form->name}.$name.checked,
                                ";
                        break;
                    default: case "select": case "input":
                        $script .= "$name : document.{$form->name}.$name.value,
                                ";
                        break;
                }
            }
        }
        $script.=" collectorfinished:true
                };
            };
        ";
        return $script;
    }

    /**
     * Javascriptcode.
     * Genereer de standaard foutmeldingen die worden gebruikt met validatie (dit moet in combinatie met generateValidation)
     * Dit zijn de zogenaamde EQ.messages!
     * @see generateValidation
     * @return javascript
     */
    public static function generateValidationMessages() {
        return self::$eqmessages;
    }

    /**
     * Javascriptcode.
     * Genereert een validatiescript dat ook automatisch meldingen plaatst achter foutieve of juiste velden. (soft validatie)
     * Je moet formulieren doorgeven via een array gevolgd door een array met de namen van de te controleren velden.
     * Enkel de opgegeven velden worden gevalideerd. De rest niet!
     * Opgelet! Je moet de EQ.messages gegenereerd hebben. Dit kan je zelf ofwel via generateValidationMesssages().
     * Bijkomend! De functie neemt een object aan met waarden van velden, kan je zelf maken of via generateCollector.
     * 
     * @see generateValidationMessages
     * @see generateCollector
     * @param array(ECP_Form) $forms Formulieren die eigenaar zijn van de velden., Verplicht!
     * @param array(veldnamen) $fields Velden die in de formulieren staan en gevalideerd moeten worden., Verplicht!
     * @param type $functionname Optioneel, een andere naam van de validatiefunctie
     * @return javascript ofwel niets indien fout bij parameters 1 en 2
     */
    public static function generateValidation($forms = array(), $fields = array(), $functionname = "validate") {
        if (!is_array($forms) && $forms instanceof ECP_Form) {
            $formar = array($forms);
        } elseif (!is_array($forms)) {
            return "";
        } else {
            $formar = $forms;
        }

        if (!is_array($fields) && $fields != "") {
            $fieldar = array($fields);
        } elseif (!is_array($fields)) {
            return "";
        } else {
            $fieldar = $fields;
        }

        $script = " $functionname = function(values){
                        var b = true;
                ";
        foreach ($formar as $form) {
            foreach ($fieldar as $field) {
                if (!is_null($form->$field)) {
                    $f = $form->$field;
                    switch ($f->type) {
                        default:
                            $script .= "if(!EQ.formCheck('',$f->script,'{$form->name}',{value:values.{$f->name},name:'{$f->name}'})) b = false;
                            ";
                            break;
                        case "radio": case "select":
                            break;
                    }
                }
            }
        }
        $script.="
            };
        ";
        return $script;
    }

}

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

    public function insert($value) {
        $this->value = trim($value);
        return true;
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

    public function validate() {
        if ($this->value == '1' && $this->tobechecked)
            return true;
        else if ($this->value == '0' && !$this->tobechecked)
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

class ECP_FormObj_Password extends ECP_FormObj_Input {

    public function getHtml($formname, $class) {
        return "<input type='password' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_Email extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false) {
        parent::__CONSTRUCT($fieldname);
        $this->script = "'x','x','email'";
        $this->type = "email";
    }

    public function validate() {
        $email = filter_var($this->value, FILTER_SANITIZE_EMAIL);  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
            $this->msg = "match";
            return false;
        } 
        return true;
        
        /*
         * old method with reg exp
         */
        /*
        $reg = '/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/';
        if (!preg_match($reg, $this->value)) {
            $this->msg = "match";
            return false;
        }
        return true;
         */
         
    }

    public function getHtml($formname, $class) {
        return "<input type='email' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_Button extends ECP_FormObj {

    private $text;

    public function __CONSTRUCT($text) {
        $this->name = "submit";
        $this->text = $text;
        $this->script = "0,0,false";
        $this->type = "button";
    }

    public function validate() {
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='button' id='{$formname}-form' name='button' value='{$this->text}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
    }

}

class ECP_FormObj_NormalButton extends ECP_FormObj {

    private $text;

    public function __CONSTRUCT($name, $text) {
        $this->name = $name;
        $this->text = $text;
        $this->script = "0,0,false";
        $this->type = "button";
    }

    public function validate() {
        return true;
    }

    public function getHtml($formname, $class) {
        return "<input type='button' id='{$formname}-{$this->name}' name='button' value='{$this->text}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
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

class ECP_FormObj_Hulpverlener extends ECP_FormObj_Input {

    public function __CONSTRUCT($fieldname = false, $minlength = 0, $maxlength = 30) {
        parent::__CONSTRUCT($fieldname, $minlength, $maxlength);
        $this->type = "hulpverlener";
        $this->script="0,30,'hulpverlener'";
    }
    
    //komt naam = "voornaam achternaam"
    public function validate(){
        $naam = filter_var($this->value, FILTER_SANITIZE_STRING);
        $name = explode(" ", $naam);
        if(count($name)==2){
            //gewoon voor-en achternaam
            $voornaam = $name[0];
            $achternaam = $name[1];
        } else {
            //achternaam telt meerdere delen
            $voornaam = $name[0];
            for($i=1;$i<count($name);$i++){
                //moet misschien nog worden aangepast, spaties
                $achternaam .=$name[$i];
            }
        }
        self::$db = ECPFactory::getPDO("Hulpverleners","class");
        $hvl = new Hulpverleners();
        $hvl->setNaam($achternaam) ->setVoornaam($voornaam);
        $result = $hvl->findByExample($db, $hvl, true);
        if(empty($result)){
            $this->msg="match";
            return false;
        } return true;
    }
    
    public function getHtml($formname, $class) {
        return "<input type='text' name='{$this->name}' value='' placeholder='{$this->placeholder}'class='{$class}'/><span id='{$formname}{$this->name}'></span><br/>";
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
        return "<input type='text' maxlength='15' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br></br>";
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
        return "<input type='text' maxlength='4' name='{$this->name}' value='' placeholder='{$this->placeholder}' class='{$class}'/><span id='{$formname}{$this->name}'></span><br></br>";
    }

}
?>
