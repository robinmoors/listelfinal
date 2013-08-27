<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_AanvraagView extends ECP_Comp_Overleg_View{
    public function __CONSTRUCT($app) {
        parent::__CONSTRUCT($app);
        $this->setState("aanvraag.constructed");
    }
    
    public function __destruct() {
        $this->setState("aanvraag.destructed");
    }
    
    public function viewPlanVanOverleg(AanvraagOverleg $aanvraag){
        if ($aanvraag === null || $aanvraag === false) {
            $this->content = "Deze pati&euml;nt blijkt geen aanvraag te hebben! <a onclick='EQ.reRoute(\"overleg\",true)'>Keer terug naar patientenlijst. (Ook om er een aan te maken)</a>";
            $this->export();
        } else {
            //print_r($aanvraag);
            $content = "<div class='box'>
                            <h5>Pati&euml;ntinfo</h5>
                            <p>
                            Rijksregisternummer: aanvraag[0]['rijksregister'] <br/>
                            Volgnummer: SO98 - aanvraag[0]['code'] <br/>
                            Pati&euml;ntnaam: aanvraag[0]['naam'] <br/>
                            </p><h5>Gegevens Aanvrager</h5>
                            <p>
                            Aanvrager: mr x<br/>
                            Doel: het doel
                            </p>
                    </div><div class='box'>
                                <h5>Overleg Plannen:</h5><br/>";
            $content.="Datum (dd/mm/jjjj): ".ECPFactory::getForm("aanvraagplannen")->getHtml("normal", array("datum"=>"01/01/2013","submit"=>"Plannen"))
                    ."</div>";

            $script = ECPFactory::getForm("aanvraagplannen")->getScript("/listelfinal/ecareplan/overleg/aanvraagplannen/{$aanvraag->getId()}", array("title" => "Plannen...",
            "action" => "Bezig met plannen...",
            "succes" => "Het overleg is nu geplanned! Je kan het nu bewerken. <br/><img src=\'/listelfinal/lib/images/flat-loader.gif\' />",
            "fail" => "Er is iets misgegaan. Probeer opnieuw!"), "EQ.reRoute('overleg');");
            $this->title = "Overleg plannen";
            $this->content = $content;
            $this->script = $script;
            $this->export();
        }
    }
    
    
}