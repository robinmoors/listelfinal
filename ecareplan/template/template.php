<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Template class of EcarePlan System
 * @version 1.0
 * @package core
 * @subpackage template
 * @author Robin Moors, Joris Jacobs
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Template extends ECP_Object implements ECP_FactoryInterface, ECP_TemplateInterface{

    private static $head = '';
    private static $body = '';
    
    protected static $styles = array();
    protected static $scripts = array();
    protected static $rawbody = "";
    protected static $data = array();
    
    private static $state = "uninitialized";

    public function __construct() {
        self::$state = "constructed";
    }

    /**
     * Create a custom html head-section 
     * @param array $styles (stylefiles without .css), leave blank if no styles are needed
     * @param array $scripts (javascripts withoud .js), leave blank if no scripts are needed
     */
    private function createHead($styles = null, $scripts = null) {
        $scripts = is_null($scripts) ? array("none") : (is_array($scripts) ? $scripts : array($scripts));
        $styles = is_null($styles) ? array("none") : (is_array($styles) ? $styles : array($styles));
        self::$head = "
<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\" />
        <link rel=\"shortcut icon\" href=\"/listelfinal/favicon.ico.png.ico\" />";
        if ($styles[0] != "none") {
            foreach ($styles as $style) {
                self::$head.="<link rel=\"stylesheet\" href=\"/listelfinal/lib/css/{$style}.css\" media=\"screen\" />";
            }
        }
        self::$head.=" 
        <script src=\"/listelfinal/lib/js/jquery.js\" type=\"text/javascript\"></script>
        ";
        if ($scripts[0] != "none") {
            foreach ($scripts as $script) {
                self::$head.="<script src=\"/listelfinal/lib/js/{$script}.js\"></script>";
            }
        }

        self::$head.="
        <script type=\"text/javascript\">
            $(document).ready(function(){
                EQ.CPU = EQ.ajaxProcessor;
                {[headscript]}
            });
        </script>
        <title>{[title]}</title>
        <!--[if lt IE 9]>
            <script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script>
        <![endif]-->
    </head>
                    ";
        $this->state = "header-ready";
    }

    /**
     * Creates the body-block
     * @param type $html 
     */
    private function createBody($html) {
        self::$body = "
    <body>
        $html
    </body>
</html>";
        self::$state = "body-ready";
    }
    
    /**
     * Geef een bericht door
     * @param html $msg 
     */
    public function input($msg){
        $this->replace("message",$msg);
    }
    
    /**
     * Vervang een textgebied in de template met de opgegeven tekst
     * @param templateTextArea $needle
     * @param html $txt 
     */
    private function replace($needle,$txt){
        if(self::$state!=="body-ready"){
            if(self::$state!=="header-ready"){
                $header = $this->getHeader();
                $this->createHead($header[0], $header[1]);
            }
            $this->createBody($this->getBody());
        }
        self::$head = str_replace("{[{$needle}]}",$txt,self::$head);
        self::$body = str_replace("{[{$needle}]}",$txt,self::$body);
    }
    
    /**
     * Maak het template klaar en geef de inhoud terug
     * @return html 
     */
    public function get(){
        $this->finish();  //deleting templateTextArea's wich are not filled in
        return self::$head.self::$body;
    }
    /**
     * Pass the templatedata to this class.
     * @param array $data templatedata 
     * @return boolean true=data passed, false=no data passed
     */
    public function give($data){
        if(!is_array($data)){ 
            $this->addError("Fatal Error: No templatedata given in give!");
            return false;
        }else{
            self::$data = $data;
            return true;
        }
    }
    /**
     *  Verwijder alle ongebruikte templateTextArea's om een mooi template af te kunnen leveren
     */
    private function finish(){
        if(self::$state!=="finished" && self::$state!=="body-ready"){
            if(self::$state!=="header-ready"){
                $header = $this->getHeader();
                $this->createHead($header[0], $header[1]);
            }
            $this->createBody($this->getBody());
        }
        //replace all templateTextArea's with ""
        $tags = array("baseurl","headscript","title","bodyscript","content","content-title","content-sub-title","footer","version-name","errors","user-name","login-button");
        foreach($tags as $tag){
            if(array_key_exists($tag, self::$data)) self::replace($tag,self::$data[$tag]);
            else $this->replace($tag,"");
        }
        $this->state = "finished";
    }
    /**
     * Toon errors in template
     */
    public function viewErrors(){
        foreach($this->errors as $error){
            $errors.=$error."<br/>";
        }
        self::replace("errors",$errors);
    }
    /**
     * Returns an object of EQApp from the right $id
     * @param type $id Clientname
     */
    public static function getInstance($templatefile) {
        static $instances;
        if (!isset($instances)) {
            $instances = array();
        }
        if (empty($instances[$id])) {
            $prefix = "ECP_Template_";
            $classname = $templatefile;
            if (ecpimport("template." . strtolower($classname))) {
                $classname = $prefix . $templatefile;
                $instance = new $classname($templatefile);
            } else {
                $this->addError("<hr/>error@ECP_Template::getInstance");
                $instance = "";
            }
            $instances[$templatefile] = &$instance;
        }
        return $instances[$templatefile];
    }

     public function getBody() {
        return self::$rawbody;
    }

    public function getHeader() {
        return array(self::$styles,self::$scripts);
    }

    public function setBody($html) {
        self::$rawbody = $html;
    }

    public function setHeader($styles = array(), $scripts = array()) {
        self::$styles = $styles; self::$scripts = $scripts;
    }
}

?>
