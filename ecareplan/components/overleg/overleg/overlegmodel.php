<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_OverlegModel extends ECP_Comp_Overleg_Model {

    protected $pid = 0;
    protected $overleg = 0;

    /**
     * Deze klasse bevat een aantal queries Aanvragen van overleggen.
     * (Vormt tussenlaag van de DatabaseObjecten en het overlegcomponent)
     * De constructor opent meteen een PDO object en een AanvraagOverleg object.
     * Resetten van het object kan via restartAanvraag();
     * @param integer $pid De ID van de patient!
     */
    public function __CONSTRUCT($pid) {
        $this->pid = $pid;
        $this->overleg = $this->startOverleg();
    }

    /**
     * Haal de overleg op via de gebruiker en geef het object of het resultaat
     * @param UserId $uid
     * @param boolean $obj Het Object ja of nee? (nee = resultaat in array)
     * @return null|\Overlegbasis steeds null wanneer leeg resultaat anders het object of het resultaatarray
     */
    public function getOverlegByUser($uid, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        $this->overleg->setAfgerond(0);
        $results = $this->overleg->findByExample(self::$db, $this->overleg);
        if (empty($results)) {
            return null;
        } else {
            if ($obj)
                return $results[0];
            else
                return self::resultToArray($results, Overlegbasis::getFieldNames());
        }
    }
    
    /**
     * Haal de overleg op via de id en geef het object of het resultaat
     * @param ID $id
     * @param boolean $obj Het Object ja of nee? (nee = resultaat in array)
     * @return null|\Overlegbasis steeds null wanneer leeg resultaat anders het object of het resultaatarray
     */
    public function getOverlegById($id, $obj = false) {
        //Hier regeltjes over welke aanvragen die user nu eigenlijk mag zien...
        $this->overleg->setId($id);
        $results = $this->overleg->findByExample(self::$db, $this->overleg);
        if (empty($results)) {
            return null;
        } else {
            if ($obj)
                return $results[0];
            else
                return self::resultToArray($results, Overlegbasis::getFieldNames());
        }
    }

    /**
     * Get PDO object from factory and create Patient object, return the last one.
     * @return \Patient
     */
    private static function startOverleg() {
        self::$db = ECPFactory::getPDO("Overlegbasis");
        ecpimport("database.overleggen.overleggen");
        return new OverlegGewoon();
    }

    /**
     * Reset de huidige settings door een nieuw AanvraagOverleg object aan te maken voor dit model
     */
    public function restartOverleg() {
        $this->overleg = new OverlegGewoon();
    }

    public function setAanvraagToOverleg(Patient $patient, $datum) {
        //daar kunnen we de patientcode overnemen vanuit de aanvraag en ook de datum invullen van het overleg
        //de status eveneens instellen
        //locatie
        $locatie = "0";

        //Problematiek
        $problematiek = "fysisch";
        if ($patient->getType() == "7" || $patient->getType() == "16" || $patient->getType() == "18") {
            $problematiek = "psychisch";
        }

        //keuzeVergoeding
        $keuzeVergoeding = "1";
        //geldVoorHvl
        $geld = "0";
        //controle
        $controle = "0";
        //loginCode
        ecpimport("helpers.cryptology");
        $login = ECP_Cryptology::generateString(15);
        //ToegewezenGenre
        $genre = ECPFactory::getApp()->getUser()->getType();

        $this->overleg->setPatientCode($patient->getCode())
                      ->setDatum($datum)
                        ->setLocatie($locatie)
                    ->setSoortProblematiek($problematiek)
                    ->setKeuzeVergoeding($keuzeVergoeding)
                    ->setGeldVoorHvl($geld)
                    ->setAfgerond("0")
                    ->setControle($controle)
                    ->setLogincode($login)
                    ->setToegewezenGenre($genre);

        //de aanvraag mag geupdated worden met status naar overleg...
        //en we geven ook mee welk overleg hiermee gekoppeld is...
        try {
            $update[] = $this->overleg->insertIntoDatabase(self::$db);
            //$menos test
            // $menos = OverlegMenos::createOmbFromOverleg(self::$db->lastInsertId());
            // $update[] = $menos->insertIntoDatabaseOmb(self::$db);
            //
            //$tp test
            $tp = OverlegTp::createTpFromOverleg(self::$db->lastInsertId());
            $update[] = $tp->insertIntoDatabaseTp(self::$db);
            return true;
        } catch (Exception $e) {
            ecpexit('{"succes":"negative","message":"Helaas ... Fout:' . htmlentities($e->getMessage()) . '"}');
        }
    }
    
    public function updateOverleg($overleg, $data){
        $this->overleg = $overleg;
        $overlegvalues = $this->overleg->toHash();
        $newoverleg = array_merge($overlegvalues, $data);
        $this->overleg->assignByHash($newoverleg);
        try {
            $update[] = $this->overleg->updateToDatabase(self::$db);
        } catch (Exception $e) {
            ecpexit('{"succes":"negative","message":"Helaas ... Fout:' . htmlentities($e->getMessage()) . '"}');
        }
    }

}

?>
