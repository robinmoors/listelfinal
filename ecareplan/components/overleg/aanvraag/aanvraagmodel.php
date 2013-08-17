<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_AanvraagModel extends ECP_Comp_Overleg_Model{

    protected $pid = 0;
    protected $aanvraag = 0;
    
     /**
     * Deze klasse bevat een aantal queries Aanvragen van overleggen.
     * (Vormt tussenlaag van de DatabaseObjecten en het overlegcomponent)
     * De constructor opent meteen een PDO object en een AanvraagOverleg object.
     * Resetten van het object kan via restartAanvraag();
     * @param integer $pid De ID van de patient!
     */

    public function __CONSTRUCT($pid) {
        $this->pid = $pid;
        $this->aanvraag = $this->startAanvraag();
    }
    
    /**
     * Haal de overleg aanvraag op via de id en geef het object of het resultaat
     * @param AanvraagID $id
     * @param boolean $obj Het Object ja of nee? (nee = resultaat in array)
     * @return null|\AanvraagOverleg steeds null wanneer leeg resultaat anders het object of het resultaatarray
     */
    public function getAanvraagById($id,$obj=false){
        $this->aanvraag -> setId($id);
        $results = $this->aanvraag->findByExample(self::$db, $this->aanvraag);
        if(empty($results)){
            return null;
        }else{
            if($obj) return $results[0];
            else return self::resultToArray($results, AanvraagOverleg::getFieldNames());
        }
    }
        
    /**
     * Get PDO object from factory and create Patient object, return the last one.
     * @return \Patient
     */
    private static function startAanvraag(){
        self::$db = ECPFactory::getPDO("aanvraag.overleg");
        return new AanvraagOverleg();
    }
    
    /**
     * Reset de huidige settings door een nieuw AanvraagOverleg object aan te maken voor dit model
     */
    public function restartAanvraag(){
        $this->aanvraag = new AanvraagOverleg();
    }
    
    /**
     * Zet de aanvraag om naar een overleg.. Het overleg zelf wordt hier niet gemaakt! wel zetten we alles in de aanvraag wat we over het overleg weten...
     * @param AanvraagOverleg $aanvraag
     * @param type $datum
     * @return boolean
     */
    public function setAanvraagToOverleg($datum){
        self::$db = ECPFactory::getPDO("overlegbasis");
        //alle soorten overleggen includen...
        ecpimport("database.overleggen.overleggen");
        //we beginnen alvast met de basisinformatie
        $basis = new OverlegGewoon();
        //daar kunnen we de patientcode overnemen vanuit de aanvraag en ook de datum invullen van het overleg
        //de status eveneens instellen
        $basis->setPatientCode($this->aanvraag->getPatientCode())->setDatum($datum);
        
        
        
        //de aanvraag mag geupdated worden met status naar overleg...
        //en we geven ook mee welk overleg hiermee gekoppeld is...
        $this->aanvraag->setStatus("overleg")->setRedenStatus("overleg gepland");
        try{
            $update = $this->aanvraag->updateToDatabase(self::$db);
            return true;
        }catch(Exception $e){
            ecpexit('{"succes":"negative","message":"Het overleg is gepland maar...<br/>
                Er liep iets grandioos fout bij het updaten van de aanvraag!<br/>
                De aanvraag zal bijgevolg niet gekoppeld zijn aan het nieuwe overleg.<br>Fout:'.htmlentities($e->getMessage()).'"}');
        }
    }
    
    /**
     * Maak een nieuwe aanvraag aan voor een patient
     * @param Patient $patient Het Patientobject (dus die moet je omhalen uit het patientenmodel!)
     * @param array $data Allerhande data die een aanvraag nodig heeft.. (normaal is dat via het formulier in orde na controle)
     * @return boolean is het gelukt of niet? Bij een exception gebeurt er wel meteen een print!! dan is de controle niet goed genoeg geweest..
     */
    public function setAanvraag(Patient $patient,$data){
        //rijksregister, patient_code, gemeente_id
        if($data['huidigok']==1){
            //huidige organisator blijft behouden, wat was deze?
            $data['organisator'] = $patient->getToegewezenGenre();
            //gemeente moet eigenlijk ocmw worden anders klopt de enum niet :s
            if($data['organisator']=="gemeente") $data['organisator'] = "ocmw";
            $data['organisatorid'] = $patient->getToegewezenId();
            //de organisatorid is ms onbekend.. dat is niet goed! dan maar 1 nemen
            if($data['organisatorid']=="" || $data['organisatorid']==0) $data['organisatorid'] = 1; //error of warning?
        }
        
        //rijksregister en gemeente_id gaan we niet gebruiken (zit bij patient, dus code is voldoende)
        //overleg_id is er pas indien er ook effectief een overleg komt
        $this->aanvraag->setPatientCode($patient->getCode())->setRijksregister($patient->getRijksregister())->setGemeenteId($patient->getGemId());
        //volgende waarden mogen niet leeg zijn, maar moeten tekst bevatten..
         self::slashset($data['organisator_reden']);
         self::slashset($data['organisator_reden_andere']);
        $this->aanvraag->setKeuzeOrganisator($data['organisator'])->setRedenOrganisator($data['organisator_reden']);
        $this->aanvraag->setAndereRedenOrganisator($data['organisator_reden_andere'])->setIdOrganisator($data['organisatorid']);
        //doelen mogen niet leeg zijn, dat zijn ze wel als ze niet aangevinkt waren :s
        //idem voor dringend || alle doelen behalve andere zijn integers.. dus preset.
        self::preset($data['informeren']); self::preset($data['beslissen']); 
        self::preset($data['organiseren']); self::preset($data['debriefen']);
        self::preset($data['overtuigen']); self::slashset($data['doel_andere']);
        $this->aanvraag->setDoelInformeren($data['informeren'])->setDoelBeslissen($data['beslissen'])->setDoelDebriefen($data['debriefen']);
        $this->aanvraag->setDoelOrganiseren($data['organiseren'])->setDoelOvertuigen($data['overtuigen'])->setDoelAndere($data['doel_andere']);
        self::preset($data['dringend']);
        $this->aanvraag->setDringend($data['dringend']);
        $this->aanvraag->setNaamAanvrager($data['naam'])->setDisciplineAanvrager($data['relatie'])->setOrganisatieAanvrager($data['organisatie']);
        $this->aanvraag->setInfoAanvrager($data['email']."|".$data['telefoon'])->setDringend($data['dringend'])->setStatus("aanvraag");
        $this->aanvraag->setTimestamp(time())->setRedenStatus("Nieuwe aanvraag"); 
        try{
            $insert = $this->aanvraag->insertIntoDatabase(self::$db);
        }catch(Exception $e){
            ecpexit('{"succes":"negative","message":"Er liep iets grandioos fout!<br/>'.htmlentities($e->getMessage()).'"}');
        }
        

        //ontvangst? Bron?
        
        return $insert;
    }
}

?>
