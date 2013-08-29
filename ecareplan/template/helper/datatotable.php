<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * TemplateHelper class of Equinsi
 * @version 1.0
 * @package template
 * @subpackage helper
 * @author Robin Moors
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_TemplateHelper_DataToTable extends ECP_Object {

    private $resultlist;
    private $empty;
    private $msg;

    /**
     * Maak een tabel aan vanuit een queryresultaat
     * @param QueryResult $resultlist
     */
    public function __construct($resultlist = array()) {
        $this->resultlist = $resultlist;
    }

    /**
     * Zet het bericht dat te zien is indien resultaat leeg is..
     * @param String $msg Het bericht...
     * @return \ECP_TemplateHelperQueryToTable Chainability
     */
    public function setEmpty($msg) { 
        if (count($this->resultlist) === 0) {
            $this->empty = true;
            $this->msg = $msg;
        }
        return $this;
    }

    /**
     * Haal de tabel op -- dataafhankelijke inhoud vervangen door {[keynaam]}!!
     * @param array $toread de velden die gelezen moeten worden in het resultaat
     * @param array $titles de titels van de velden (bovenaan tabel)
     * @param string $class eventuele css klassenaam(en)
     * @param script
     * @return html de tabel
     */
    public function get($toread = array(), $titles = array(), $class = false, $script = false) {
        $css = $class ? " class='$class'" : "";
        $script = $script ? " onclick='$script'" : "";
        $html = "<table id='ShowTable'$css>
                    <thead><tr id='TableHead'>
                        ";
        foreach ($titles as $title) {
            $html.="<th>$title</th>";
        }
        $html.="
           </tr></thead><tbody>
            ";
        if (!$this->empty) {
            for ($i = 0; $i < count($this->resultlist); $i++) {
                $html.="<tr id='TableRow'$script>
                ";
                foreach ($toread as $read) {
                    $html.="<td>{$this->resultlist[$i][$read]}</td>";
                }
                //variabele tekst uit bvb script vervangen
                foreach($toread as $read){
                    $replace = "{[$read]}";
                    $html = str_replace($replace,$this->resultlist[$i][$read],$html);
                }
                $html.="
                </tr>";
            }
        } else {
            $html.="<tr id='TableRow'><td colspan='".count($titles)."'>$this->msg</td></tr>";
        }
        return $html . "</tbody></table>";
    }

}

?>