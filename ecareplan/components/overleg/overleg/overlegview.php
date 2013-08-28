<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_OverlegView extends ECP_Comp_Overleg_View{
    public function __CONSTRUCT($app) {
        parent::__CONSTRUCT($app);
        $this->setState("overleg.constructed");
    }
    
    public function __destruct() {
        $this->setState("overleg.destructed");
    }
    
     public function viewList($aanvragen,$overleggen) {
        $this->setState("viewlist.start"); //naam.actie!
        
        $keys = array('id', 'patient_code', 'rijksregister', 'naam_aanvrager', 'timestamp');
        $keysnamed = array('#', 'Patient Code', 'Rijksregister', 'Aanvrager', 'Datum aanvraag');
        $this->setState("content.start");
        
        $this->setState("aanvraag.start");
        $content = "<h4>Aanvragen</h4><br/><a class='RoundedButton2 login' href='' onclick='EQ.reRoute(\"overlegnieuw\",true);'>Nieuwe aanvraag indienen</a><br/><table id='ShowTable' class='wider'><tr id='TableHead'>";
        for ($i = 0; $i < count($keys); $i++) {
            $content.="<td>{$keysnamed[$i]}</td>";
        }
        $content.="</tr>";
        if($aanvragen!=null){
        foreach ($aanvragen as $patient) {
            $content.="<tr id='TableRow' onclick='EQ.reRoute(\"aanvraagplan\",true,{$patient['id']});'>";
            for ($i = 0; $i < count($keys); $i++) {
                $content.="<td>{$patient[$keys[$i]]}</td>";
            }
        }
        }else{
            $content.="<tr id='TableRow'><td colspan='".count($keys)."'><em>Geen aanvragen beschikbaar om te plannen. Dien een nieuwe aanvraag in om een overleg te plannen.</em></td></tr>";
        }
        $content.="</table>";
        $this->stack=$content;
        $this->setState("aanvraag.end");
        $this->moveToContent();
        
        $this->setState("overleg.start");
        $content = "<h4>Overleggen</h4><br/><table id='ShowTable' class='wider'><tr id='TableHead'>";
        for ($i = 0; $i < count($keys); $i++) {
            $content.="<td>{$keysnamed[$i]}</td>";
        }
        $content.="</tr>";
        if($overleggen!=null){
        foreach ($overleggen as $patient) {
            $content.="<tr id='TableRow' onclick='EQ.reRoute(\"overlegbewerk\",true,{$patient['id']});'>";
            for ($i = 0; $i < count($keys); $i++) {
                $content.="<td>{$patient[$keys[$i]]}</td>";
            }
        }
        }else{
            $content.="<tr id='TableRow'><td colspan='".count($keys)."'><em>Geen aanvragen beschikbaar om te plannen. Dien een nieuwe aanvraag in om een overleg te plannen.</em></td></tr>";
        }
        $content.="</table>";
        $this->stack=$content;
        $this->setState("overleg.end");
        $this->moveToContent();
        
        $this->setState("content.end");
        
        $this->title = "Patientenlijst";
        $this->moveToContent();
        
        $this->setState("viewlist.end");
        $this->export();
    }
    
    public function editOverleg($overleg, $patient, $aanvraag, $form) {
        if ($overleg === null || $overleg === false) {
            $this->content = "Deze pati&euml;nt blijkt geen overleggen te hebben! <a onclick='EQ.reRoute(\"overleg\",true)'>Keer terug naar patientenlijst. (Ook om er een aan te maken)</a>";
            $this->export();
        } else {
            $content = "<div class='box'>
                            <h5>Pati&euml;ntinfo</h5>
                            <p>
                            Rijksregisternummer: {$patient->getRijksregister()} <br/>
                            Volgnummer: SO98 - {$patient->getCode()} <br/>
                            Pati&euml;ntnaam: {$patient->getNaam()} <br/>
                            </p><h5>Gegevens overleg</h5>
                            <p>
                            Start van het overleg: {$overleg->getDatum()}<br/>
                            Organisatie aanvrager: {$aanvraag->getOrganisatieAanvrager()}<br/>
                            Doel: het doel
                    </div><div class='box selfclear'>
                            <ul class='tabinterface'>
                            <li class='selected' id='basis-but'>Basisgegevens</li>
                            <li id='team-but'>Teamoverleg</li>
                            <li id='attest-but'>Attesten en bijlagen</li>
                            <li id='taak-but'>Taakfiches</li>
                            <li id='afdruk-but'>Afdrukpagina</li>
                            </ul>
                          </div><div class='box wrapped'>
                                <div id='basis' class='tappage tapdefault'>
                                <h5>Basisgegevens:</h5><br/>";
            $content.=$form[0]->getHtml("normal", array("locatie" => "Plaats van het overleg:<br/>", "aanwezig" => "Wie is er aanwezig op het overleg?<br/>", "instemming" => "Instemming met de deelnemers van het overleg. De pati&euml;nt of vertegenwoordiger?<br/>"))
                    ."</div><div id='team' class='tappage'>
                                <h5>Teamoverleg:</h5><br/>
                                Hier komt een tabel met teamleden - hun rechten - teamleider.
                      </div><div id='attest' class='tappage'>
                                <h5>Attesten en bijlagen</h5>
                      </div><div id='taak' class='tappage'>
                                <h5>Taakfiches</h5>
                      </div><div id='afdruk' class='tappage'>
                                <h5>Afdrukpagina</h5>
                      </div></div>";
        }


            $this->title = "Overleg bewerken";
            $this->content = $content;
            $script = self::createTabScript(array("basis","team","attest","taak","afdruk")).
                    $form[0]->getScript("/listelfinal/ecareplan/overleg/bewerkopslaan/{$overleg->getId()}",
                            array("title"=>"Basisgegevens opslaan","action"=>"Bezig met opslaan..."),
                            "EQ.OVR.close();",
                            "",
                            "else if(json.message){
                                EQ.OVR.content=json.message; EQ.OVR.refresh('c');
                            }");
            $this->script = $script;
            $this->export();
        }
        
        
        private static function createTabScript($pages=array()){
        $h="";
        for($i=0; $i<count($pages); $i++){
            $h.="$('#{$pages[$i]}-but').bind('click',function(){EQ.change('{$pages[$i]}')});";
        }
        $h.="EQ.pages=[";
        for($i=0; $i<count($pages);$i++){
            $h.="'{$pages[$i]}'";
            if(count($pages)>($i+1)) $h.=",";
        }
        $h.="]";
        return $h; 
    }
    
    
}