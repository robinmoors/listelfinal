<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_OverlegForm implements ECP_OverlegObservable{

    
     //observable
    private $observers = array();
    private $state = "unset";
    
    //forms
    private $base = null;

    /**
     * Deze klasse bevat een aantal queries Aanvragen van overleggen.
     * (Vormt tussenlaag van de DatabaseObjecten en het overlegcomponent)
     * De constructor opent meteen een PDO object en een AanvraagOverleg object.
     * Resetten van het object kan via restartAanvraag();
     * @param integer $pid De ID van de patient!
     */
    public function __CONSTRUCT() {
        $this->state = "constructed";
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
        $this->state = "form.".$state;
        if($old !== $this->state) $this->notify(); //autonotify on statechange...
        return $this;
    }
    //End Observer pattern
    
    public function getBasis(){
        if(!$this->base){
            $this->setState("overlegform.basisgegevens.start");
            $this->base = ECPFactory::getForm("basisgegevens");
            $this->base->addField(new ECP_FormObj_Radio("locatie",array("0"=>"Bij de pati&euml;nt huis","1"=>"Elders")))
                       ->addField(new ECP_FormObj_Radio("aanwezig",array("0"=>"De pati&euml;nt","1"=>"Vertegenwoordigster (kies onderaan)","2"=>"Niemand")))
                       ->addField(new ECP_FormObj_Radio("instemming", array("0"=>"Stemt in.","1"=>"Stemmen niet in.")))
                       ->addField(new ECP_FormObj_Button("Opslaan"));
            $this->setState("overlegform.basisgegevens.stop");
        }
        return $this->base;
    }

}

?>
