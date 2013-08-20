<?php
/*
 * EcarePlan - Made by Robin Moors, Joris Jacobs
 * @version 1.0
 * Template for Listel vzw
 */

defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

interface ECP_TemplateInterface {
    
    /**
     * Set de css bestanden en de javascript bestanden, beiden moeten in array geplaatst worden!
     * @param array $styles Array van css bestanden zonder .css extentie!
     * @param array $scripts Array van javascriptbestanden zonder .js extentie!
     */
    public function setHeader($styles=array(),$scripts=array());
    
    /**
     * Haal de bestanden op
     * @return Array met op 0 de css bestanden (array) en 1 de javascript bestanden (array)
     */
    public function getHeader();
    
    /**
     * Zet de html van de body. Opgelet! De template wordt hiermee gewist!
     * @param html $html
     */
    public function setBody($html);
    
    /**
     * Haal de html op van de body-tag
     * @return html de html van de body
     */
    public function getBody();
}