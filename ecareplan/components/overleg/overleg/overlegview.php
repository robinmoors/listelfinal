<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_OverlegView extends ECP_Comp_Overleg_View {

    public function __CONSTRUCT($app) {
        parent::__CONSTRUCT($app);
        $this->setState("overleg.constructed");
    }

    public function __destruct() {
        $this->setState("overleg.destructed");
    }

    public function viewList($aanvragen, $overleggen) {
        $this->setState("viewlist.start"); //naam.actie!

        $keys = array('id', 'patient_code', 'rijksregister', 'naam_aanvrager', 'timestamp');
        $keysnamed = array('#', 'Patient Code', 'Rijksregister', 'Aanvrager', 'Datum aanvraag');
        ecpimport("template.helper.datatotable");
        $aanvraagtable = new ECP_TemplateHelper_DataToTable($aanvragen);
        $aanvraagtable->setEmpty("<em>Geen aanvragen beschikbaar om te plannen. Dien een nieuwe aanvraag in om een overleg te plannen.</em>");
        $overlegtable = new ECP_TemplateHelper_DataToTable($overleggen);
        $overlegtable->setEmpty("<em>Geen overleggen beschikbaar om te bewerken of af te ronden. Dien een nieuwe aanvraag in of plan een overleg via een bestaande aanvraag.</em>");
        $this->setState("content.start");

        $this->setState("aanvraag.start");
        $content = "<h4>Aanvragen</h4><br/><a class='RoundedButton2 login' href='' onclick='EQ.reRoute(\"overlegnieuw\",true);'>Nieuwe aanvraag indienen</a><br/>".
        $aanvraagtable->get($keys,$keysnamed,"wider","EQ.reRoute(\"aanvraagplan\",true,{[id]});");
        $this->stack = $content;
        $this->setState("aanvraag.end");
        $this->moveToContent();

        $this->setState("overleg.start");
        $content = $overlegtable->get($keys,$keysnamed,"wider","EQ.reRoute(\"overlegbewerk\",true,{[id]});");
        $this->stack = $content;
        $this->setState("overleg.end");
        $this->moveToContent();

        $this->setState("content.end");

        $this->title = "Patientenlijst";
        $this->moveToContent();

        $this->setState("viewlist.end");
        $this->export();
    }
    
    private function tabbladTeam($betrokkenen,$id){
        $url = "/listelfinal/lib/images";
        if($betrokkenen === null){
            $html = "<h4>Zorgverleners <img src='$url/Doctor.png' onclick='EQ.getPopup(\"doktors\",$id);'/></h4>
                     <h4>Mantelzorgers <img src='$url/Doctor-Nurse.png' onclick='EQ.getPopup(\"mantelzorgers\",$id);'/><h4>";
            return $html;
        }else{
            $dokters = array();
            $mantels = array();
            foreach($betrokkenen as $betrokken){
                $betrokken["verwijder"] = "<img src='$url/cross.png' onclick='EQ.setPopup(\"verwijder\",$id,{$betrokken['id']});'/>";
                $iconaction = "EQ.iconAction(\"#betrokkenrechten{$betrokken['id']}\",
                    {
                    on:\"$url/eye.png\",
                    off:\"$url/eye-close.png\",
                    error:\"$url/eye--exclamation.png\",
                    bussy:\"$url/eye-half.png\"
                    },
                    {
                    url:\"overleg/bewerkinvoegenrechten/{$betrokken['id']}\",
                    pname:\"rechtentoggle\",
                    onvalue:1,
                    offvalue:0
                    });
                    ";
                $betrokken["rechten"] = $betrokken["rechten"]==0 ? "<span id='betrokkenrechten{$betrokken['id']}' onclick='$iconaction'><img src='$url/eye-close.png'></span>" : "<span id='betrokkenrechten{$betrokken['id']}' onclick='$iconaction'><img src='$url/eye.png'></span>";
                if($betrokken['genre']=="hulp") $dokters[] = $betrokken;
                if($betrokken['genre']=="mantel") $mantels[] = $betrokken;
            }
            ecpimport("template.helper.datatotable");
            $doktert = new ECP_TemplateHelper_DataToTable($dokters);
            $doktert->setEmpty("<em>klik op het icoontje naast zorgveleners om er eentje toe te voegen...</em>");
            $html= "<h4>Zorgverleners <img src='$url/Doctor.png' onclick='EQ.getPopup(\"doktors\",$id);'/></h4>"
                    .$doktert->get(array("aanwezig","naam","wat","referentie","verwijder","rechten"));
            $mantelt = new ECP_TemplateHelper_DataToTable($mantels);
            $mantelt->setEmpty("<em>Klik op het icoontje naast mantelverzorgers om er eentje toe te voegen...</em>");
            $html .= "<h4>Mantelzorgers <img src='$url/Doctor-Nurse.png' onclick='EQ.getPopup(\"mantelzorgers\",$id);'/></h4>"
                    .$mantelt->get(array("aanwezig","naam","wat","referentie","verwijder","rechten"));
            return $html;
        }
    }

    public function editOverleg($overleg, $patient, $aanvraag, $form, $betrokkenen) {
        $teamcode = $this->tabbladTeam($betrokkenen,$overleg->getId());
        $this->title = "Overleg bewerken";
        if ($overleg === null || $overleg === false) {
            $this->content = "Het overleg werd niet gevonden! <a onclick='EQ.reRoute(\"overleg\",true)'>Keer terug naar aanvragen en overleggen.</a>";
            $this->export();
        } elseif ($aanvraag === null) {
            $this->content = "Dit overleg werd gepland zonder bijhorende aanvraag!<br/>Het is noodzakelijk voor een overleg om vanuit een aanvraag te vertrekken. <a onclick='EQ.reRoute(\"overleg\",true)'>Keer terug naar aanvragen en overleggen.</a>";
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
                            <li id='basis-but'>Basisgegevens</li>
                            <li class='selected' id='team-but'>Teamoverleg</li>
                            <li id='attest-but'>Attesten en bijlagen</li>
                            <li id='taak-but'>Taakfiches</li>
                            <li id='afdruk-but'>Afdrukpagina</li>
                            </ul>
                          </div><div class='box wrapped'>
                                <div id='basis' class='tappage'>
                                <h5>Basisgegevens:</h5><br/>";
            $content.=$form[0]->getHtml("normal", array("locatie" => "Plaats van het overleg:<br/>", "aanwezig" => "Wie is er aanwezig op het overleg?<br/>", "instemming" => "Instemming met de deelnemers van het overleg. De pati&euml;nt of vertegenwoordiger?<br/>"))
                    . "</div><div id='team' class='tappage tapdefault'>
                                <h5>Teamoverleg:</h5><br/>
                                $teamcode
                      </div><div id='attest' class='tappage'>
                                <h5>Attesten en bijlagen</h5>
                      </div><div id='taak' class='tappage'>
                                <h5>Taakfiches</h5>
                      </div><div id='afdruk' class='tappage'>
                                <h5>Afdrukpagina</h5>
                      </div></div>";
            $this->content = $content;
            $script = self::createTabScript(array("basis", "team", "attest", "taak", "afdruk")) .
                    $form[0]->getScript("/listelfinal/ecareplan/overleg/bewerkopslaan/{$overleg->getId()}", array("title" => "Basisgegevens opslaan", "action" => "Bezig met opslaan..."), "EQ.OVR.close();", "", "else if(json.message){
                                EQ.OVR.content=json.message; EQ.OVR.refresh('c');
                            }");
            $this->script = $script;
            $this->export();
        }
    }

    private static function createTabScript($pages = array()) {
        $h = "";
        for ($i = 0; $i < count($pages); $i++) {
            $h.="$('#{$pages[$i]}-but').bind('click',function(){EQ.change('{$pages[$i]}')});";
        }
        $h.="EQ.pages=[";
        for ($i = 0; $i < count($pages); $i++) {
            $h.="'{$pages[$i]}'";
            if (count($pages) > ($i + 1))
                $h.=",";
        }
        $h.="]";
        return $h;
    }

}