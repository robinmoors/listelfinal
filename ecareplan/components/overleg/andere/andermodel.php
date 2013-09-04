<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_AnderModel extends ECP_Comp_Overleg_Model {

    /**
     * Deze klasse bevat een aantal queries Aanvragen van overleggen.
     * (Vormt tussenlaag van de DatabaseObjecten en het overlegcomponent)
     * De constructor opent meteen een PDO object en een AanvraagOverleg object.
     * Resetten van het object kan via restartAanvraag();
     * @param integer $pid De ID van de patient!
     */
    
    private static $functies=null;
    private static $verwantschappen=null;
    
    public function __CONSTRUCT() {
           parent::__construct();
    }

    /**
     * Haal de overleg betrokkenen op via de patientcode
     * @param Patientcode $pat_code
     * @param integer $obj Het Object ja of nee? (nee = resultaat in array), 2 = array van objecten!!
     * @return null|\AanvraagOverleg|\array steeds null wanneer leeg resultaat anders het object of het resultaatarray
     */
    public function getBetrokkenenByPatientCode($pat_code, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        ecpimport("database.HuidigeBetrokkenen","class");
        $betrokken = new HuidigeBetrokkenen();
        $betrokken->setPatientCode($pat_code);
        $results = $betrokken->findByExample(self::$db, $betrokken);
        if (empty($results)) {
            return null;
        } else {
            if ($obj) {
                if($obj==2) return $results;
                return $results[0];
            }
            else
                return self::resultToArray($results, HuidigeBetrokkenen::getFieldNames());
        }
    }
    
    /**
     * Haal de overleg betrokkenen op via de id
     * @param ID $id
     * @param integer $obj Het Object ja of nee? (nee = resultaat in array
     * @return null|\AanvraagOverleg|\array steeds null wanneer leeg resultaat anders het object of het resultaatarray
     */
    public function getBetrokkenenById($id, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        ecpimport("database.HuidigeBetrokkenen","class");
        $betrokken = new HuidigeBetrokkenen();
        $betrokken->setId($id);
        $results = $betrokken->findByExample(self::$db, $betrokken);
        if (empty($results)) {
            return null;
        } else {
            if ($obj) {
                return $results[0];
            }
            else
                return self::resultToArray($results, HuidigeBetrokkenen::getFieldNames());
        }
    }
    
    private static function fetchFunctie($functieid){
        if(self::$functies===null){
            ecpimport("database.Functies","class");
            $functie = new Functies();
            self::$functies = Functies::findByExample(self::$db, $functie);
        }
        foreach(self::$functies as $functie){
            if($functie->getId()==$functieid) return $functie->getNaam ();
        }
        return null;
    }
    
    private static function fetchVerwantschap($verwschid){
        if(self::$verwantschappen===null){
            ecpimport("database.Verwantschap","class");
            $functie = new Verwantschap();
            self::$verwantschappen = Verwantschap::findByExample(self::$db, $functie);
        }
        foreach(self::$verwantschappen as $functie){
            if($functie->getId()==$verwschid) return $functie->getNaam ();
        }
        return null;
    }
    
    public function fetchInfoBetrokkenen($betrokkenen = array()){
        if(!count($betrokkenen)) return null;
        else{
            ecpimport("database.Persoon","class");
            $persoon = new Persoon();
            ecpimport("database.Hulpverleners","class");
            $verlener = new Hulpverleners();
            ecpimport("database.Mantelzorgers","class");
            $mantel = new Mantelzorgers();
            $info = array();
            foreach($betrokkenen as $betrokken){
                $persoon->setId($betrokken->getPersoonId());
                $pers = $persoon->findByExample(self::$db, $persoon);
                switch($pers[0]->getPersoonType()){
                    case "HVL":
                        $verlener->setId($persoon->getId());
                        $hulpverlener = $verlener->findByExample(self::$db, $verlener);
                        $info[] = array("naam"=>$hulpverlener[0]->getNaam().",".$hulpverlener[0]->getVoornaam(),"wat"=>self::fetchFunctie($hulpverlener[0]->getFnctId()),
                            "aanwezig"=>$betrokken->getAanwezig(),"referentie"=>$betrokken->getNamens(),
                            "rechten"=>$betrokken->getRechten(),"genre"=>$betrokken->getGenre(),
                            "id"=>$betrokken->getId());
                        break;
                    default: case "ORG":
                        //organisatie(persoon)
                        break;
                    case "MVZ":
                        $mantel->setId($persoon->getId());
                        $mantelzorger = $mantel->findByExample(self::$db, $mantel);
                        $info[] = array("naam"=>$mantelzorger[0]->getNaam().",".$mantelzorger[0]->getVoornaam(),"wat"=>self::fetchVerwantschap($mantelzorger[0]->getVerwschId()),
                            "aanwezig"=>$betrokken->getAanwezig(),"referentie"=>$betrokken->getNamens(),
                            "rechten"=>$betrokken->getRechten(),"genre"=>$betrokken->getGenre(),
                            "id"=>$betrokken->getId());
                        break;
                }
                
            }
            return $info;
        }
    }
    
    public function getDokterById($id,$obj = false){
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        ecpimport("database.Hulpverleners","class");
        $dokter = new Hulpverleners();
        $dokter->setId($id);
        $results = $dokter->findByExample(self::$db, $dokter);
        if (empty($results)) {
            return null;
        } else {
            if ($obj) {
                return $results[0];
            }
            else
                return self::resultToArray($results, Hulpverleners::getFieldNames());
        }
    }
    
    public function toggleRechtenBetrokkenen(\HuidigeBetrokkenen $betrokkenen,$value){
        if($betrokkenen == null) return null;
        $betrokkenen->setRechten($value);
       try {
            $update[] = $betrokkenen->updateToDatabase(self::$db);
            return true;
        } catch (Exception $e) {
            ecpexit('{"succes":"negative","error":"Helaas ... Fout:' . htmlentities($e->getMessage()) . '"}');
            return null;
        }
        return true;
    }
    
    public function toggleAanwezigBetrokkenen(\HuidigeBetrokkenen $betrokkenen,$value){
        if($betrokkenen == null) return null;
        $betrokkenen->setAanwezig($value);
       try {
            $update[] = $betrokkenen->updateToDatabase(self::$db);
            return true;
        } catch (Exception $e) {
            ecpexit('{"succes":"negative","error":"Helaas ... Fout:' . htmlentities($e->getMessage()) . '"}');
            return null;
        }
        return true;
    }
    
    public function verwijderBetrokkenen(\HuidigeBetrokkenen $betrokkenen){
        if($betrokkenen == null) return null;
       $delete = HuidigeBetrokkenen::deleteByFilter(self::$db, new DFC(HuidigeBetrokkenen::FIELD_ID,$betrokkenen->getId(),DFC::EXACT));
       return $delete;
    }
    
    public function maakHulpverlenerBetrokken(\OverlegGewoon $overleg,  \Hulpverleners $hulpverlener){
        ecpimport("database.HuidigeBetrokkenen","class");
        $betrokken = new HuidigeBetrokkenen();
        $betrokken->setAanwezig(0)->setBereikbaarheid("")->setGenre("hulp")->setNamens(0)
                ->setOverleggenre("gewoon")->setPatientCode($overleg->getPatientCode())
                ->setPersoonId($hulpverlener->getId())->setRechten(0);
        try {
            $update[] = $betrokken->insertIntoDatabase(self::$db);
            return true;
        } catch (Exception $e) {
            ecpexit('{"succes":"negative","error":"Helaas ... Fout:' . htmlentities($e->getMessage()) . '"}');
        }
        return true;
    }
    
    public function getNietBetrokkenDokters($pat_code, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        ecpimport("database.HuidigeBetrokkenen","class");
        $betrokken = new HuidigeBetrokkenen();
        $betrokken->setPatientCode($pat_code);
        $results = $betrokken->findByExample(self::$db, $betrokken);
        
        ecpimport("database.Hulpverleners","class");
        $hulp = new Hulpverleners();
        ecpimport("database.Functies","class");
        $functies =  new Functies();
        ecpimport("database.Functiegroepen","class");
        $groep = new Functiegroepen();
        ecpimport("database.Organisatie","class");
        $organisatie = new Organisatie();
        ecpimport("database.Patient","class");
        $patient = new Patient();
        ecpimport("database.Gemeente","class");
        $gemeente = new Gemeente();
        //de zorgverlener moet van de gemeente vd patient zijn...
        $patient->setCode($pat_code);
        $pat = $patient->findByExample(self::$db, $patient);
        $gemeente_id = $pat[0]->getGemId();
        //dus alle id's van de gemeente in kwestie ophalen
        $gemeente->setId($gemeente_id);
        $gemeentes = $gemeente->findByExample(self::$db, $gemeente);
        $gemeente_zip = $gemeentes[0]->getZip();
        
        $gemeente = new Gemeente();
        $gemeente->setZip($gemeente_zip);
        $gemeentes = $gemeente->findByExample(self::$db, $gemeente);
        
        $filtergemeente = array();
        foreach($gemeentes as $gemeente){
            $filtergemeente[] = new DFC(Hulpverleners::FIELD_GEM_ID,$gemeente->getId(),DFC::EXACT);
        }
        $hulpverleners = $hulp->findByFilter(self::$db, $filtergemeente,false);
        
        
        
        //functiegroep = ZVL, dan functieid's ophalen die onder ZVL vallen.
        $groep->setNaam("ZVL");
        $groepr = $groep->findByExample(self::$db, $groep);
        
        $functies->setGroepId($groepr[0]->getId());
        $funcar = $functies->findByExample(self::$db, $functies);
        
        $filterfunctie = array();
        foreach($funcar as $functie){
            $filterfunctie[] = $functie->getId();
        }
        
        //organisatiegenre moet ook ZVL zijn, dus even die organisaties selecteren
        
        $organisatie->setGenre("ZVL");
        $orgs = $organisatie->findByExample(self::$db, $organisatie);
        
        //filteren
        $ohulpverleners = array();
        foreach($orgs as $org){
            foreach($hulpverleners as $hulpverlener){
                if($hulpverlener->getOrganisatie()==$org->getId())
                    $ohulpverleners[] = $hulpverlener;
            }
        }
        //filteren op functieid
        $verlener = array();
        foreach($ohulpverleners as $hulpverlener){
            if(array_search($hulpverlener->getFnctId(),$filterfunctie)){
                $verlener[]=$hulpverlener;
            }
        }
        //filteren op actief of niet
        $actieve = array();
        foreach($verlener as $hulpverlener){
            if($hulpverlener->getActief()==1)
                $actieve[] = $hulpverlener;
        }
        //filteren of hij al betrokken is of niet
        $nieuw  = array();
        foreach($actieve as $hulpverlener){
            $betrok = false;
            foreach($results as $betrokken){
                if($betrokken->getPersoonId() == $hulpverlener->getId())
                    $betrok = true;
            }
            if(!$betrok) $nieuw[] = $hulpverlener;
        }
        if (empty($nieuw)) {
            return null;
        } else {
            if ($obj) {
                if($obj==2) return $nieuw;
                return $results[0];
            }
            else
                return self::resultToArray($nieuw, Hulpverleners::getFieldNames());
        }
    }
    
     public function getNietBetrokkenMantelzorgers($pat_code, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        ecpimport("database.HuidigeBetrokkenen","class");
        $betrokken = new HuidigeBetrokkenen();
        $betrokken->setPatientCode($pat_code);
        $results = $betrokken->findByExample(self::$db, $betrokken);
        
        ecpimport("database.Mantelzorgers","class");
        $mantelzorgers = new Mantelzorgers();
        ecpimport("database.Verwantschap","class");
        $verwantschap = new Verwantschap();
        
        

        //filteren of hij al betrokken is of niet
        $nieuw  = array();
        foreach($actieve as $hulpverlener){
            $betrok = false;
            foreach($results as $betrokken){
                if($betrokken->getPersoonId() == $hulpverlener->getId())
                    $betrok = true;
            }
            if(!$betrok) $nieuw[] = $hulpverlener;
        }
        if (empty($nieuw)) {
            return null;
        } else {
            if ($obj) {
                if($obj==2) return $nieuw;
                return $results[0];
            }
            else
                return self::resultToArray($nieuw, Hulpverleners::getFieldNames());
        }
    }
}

?>
