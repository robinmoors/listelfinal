<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_AanvraagView extends ECP_Comp_Overleg_View{
    public function __CONSTRUCT($app) {
        parent::__CONSTRUCT($app);
        $this->setState("aanvraag.constructed");
    }
    
    public function __destruct() {
        $this->setState("aanvraag.destructed");
    }
    
    
}