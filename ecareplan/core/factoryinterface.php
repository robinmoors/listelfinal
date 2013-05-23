<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author robindell
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");
interface ECP_FactoryInterface {
    //put your code here
    /**
     * Met deze methode wordt een object van de "fabriceerbare" klasse aangemaakt of juist opgehaald.
     * In deze methode wordt ook het aantal aan te maken objecten min of meer beheerd.
     * De enige parameter die nodig is het label dat het object meekrijgt om later te hergebruiken.
     * @param mixed $instancename Eventuele naam of id van het object.
     */
    public static function getInstance($instancename);
}

?>
