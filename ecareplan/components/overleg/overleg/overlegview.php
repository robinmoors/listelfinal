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
    
    
}