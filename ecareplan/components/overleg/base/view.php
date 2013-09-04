<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_View implements ECP_OverlegObservable{

    protected static $app;
    protected $content;
    protected $title;
    protected $script;
    protected $observer = array();
    protected $state = "unset";
    
    protected $stack='';

    public function __CONSTRUCT($app) {
        self::$app = $app;
        self::$app->setTemplate("listel");
        $this->state = "view.constructed";
    }
    
    public function __destruct() {
        $this->setState("deconstruted");
    }
    //Begin Observer pattern (Subject)
    public function attach(ECP_OverlegObserver $obs){
        $i = array_search($obs, $this->observer);
        if($i===false){
            $this->observers[]=$obs;
        }
        return $this;
    }
    public function detach(ECP_OverlegObserver $obs){
        if(!empty($this->observers)){
            $i = array_search($obs, $this->observers);
            if($i !== false){
                delete($this->observers[$i]);
            }
        }
        return $this;
    }
    public function notify(){
        if(empty($this->observers)) return $this; //indien er niemand meekijkt snel wegwezen!
        foreach($this->observers as $obs){
            $obs->update($this);
        }
        return $this;
    }
    public function getObservers() {
        return $this->observers;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $old = $this->state;
        $this->state = "view.".$state;
        if($old !== $this->state) $this->notify(); //autonotify on statechange...
        return $this;
    }
    //End Observer pattern (Subject
    //Start special observer methods
    public function getStack(){
        return $this->stack;
    }
    public function setStack($stack){
        $this->stack = $stack;
        return $this;
    }
    //End special observer methods
    
    //stack methods
        protected function export() {
        self::$app->setTemplateData(array("content" => $this->content, "content-title" => "Overleg", "content-sub-title" => $this->title, "title" => "Ecareplan ~ " . $this->title, "headscript" => $this->script));
    }
    
    protected function moveToContent(){
        $this->content.=$this->stack; $this->stack = '';
        return $this;
    }
    
    protected function moveToScript(){
        $this->script.=$this->stack; $this->stack ='';
        return $this;
    }
    
    public function __toString() {
        return $this->state;
    }
}
?>
