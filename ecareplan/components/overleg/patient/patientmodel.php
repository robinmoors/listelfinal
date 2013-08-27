<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_Overleg_PatientModel extends ECP_Comp_Overleg_Model {

    protected $uid = 0;
    protected $patient = 0;

    /**
     * Deze klasse bevat een aantal queries voor Patienten.
     * (Vormt tussenlaag van de DatabaseObjecten en het overlegcomponent)
     * De constructor opent meteen een PDO object en een Patient object.
     * Resetten van het object kan via restartPatient();
     * @param integer $uid De ID van de gebruiker
     */
    public function __CONSTRUCT($uid) {
        $this->uid = $uid;
        $this->patient = $this->startPatient();
    }

    /**
     * Haal alle patienten op waarvoor de user gemachtigd is
     * @param int $limit Maximum aantal patienten
     * @param int $from Vanaf welk resultaat te beginnen?
     * @param int $to Tot welk resultaat te zoeken?
     * @return Array queryresult
     */
    public function getAllPatients($limit = 30, $from = 0, $to = 30) {
        $user = ECPFactory::getUser($this->uid);
        $this->patient->setGemId($user->gem_id);
        $result = Patient::findByExample(self::$db, $this->patient);
        return self::resultToArray($result, Patient::getFieldNames());
    }

    /**
     * Haal de patient up via zijn Id
     * @param integer $id de pat_id
     * @param boolean $getobject true als je het object wilt, leeg(false) voor een array van het resultaat
     * @return \Patient|Array|null afhankelijk van de gegeven boolean en null bij geen resultaat!
     */
    public function getPatientById($id, $getobject = false) {
        $this->patient->setId($id);
        $result = Patient::findByExample(self::$db, $this->patient);
        if ($getobject) {
            if (count($result) < 1)
                return null;
            else
                return $result[0];
        }
        return self::resultToArray($result, Patient::getFieldNames());
    }
    
    /**
     * Haal de patient up via zijn Code
     * @param integer $code de pat_code
     * @param boolean $getobject true als je het object wilt, leeg(false) voor een array van het resultaat
     * @return \Patient|Array|null afhankelijk van de gegeven boolean en null bij geen resultaat!
     */
    public function getPatientByCode($code, $getobject = false) {
        $this->patient->setCode($code);
        $result = Patient::findByExample(self::$db, $this->patient);
        if ($getobject) {
            if (count($result) < 1)
                return null;
            else
                return $result[0];
        }
        return self::resultToArray($result, Patient::getFieldNames());
    }

    /**
     * Haal de patientToewijzing op (id nodig) en return wat die toewijzing is (of het hele object/naam van het resultaat)
     * @param PatientId $id
     * @param boolean $getobject true als je het object wilt, false bij een resultaat in arrayvorm
     * @return \Organisatie|\Hulpverlener|int|null|string Het object of null bij leeg resultaat voor de patient, 0 bij leeg resultaat voor de organisatie of 2 bij leeg resultaat voor hulpverleners en 1 bij een gemeentetoewijzing, geen object en een nummer is een naam!
     */
    public function getPatientToewijzing($id, $getobject = false) {
        $pat = $this->getPatientById($id, true);
        if (is_null($pat))
            return null;
        switch ($pat->getToegewezenGenre()) {
            case "gemeente": return 1;
                break;
            case "rdc": case "psy":
                ecpimport("database.organisatie", "class");
                $org = new Organisatie();
                $org->setId($pat->getToegewezenId());
                $coresult = Organisatie::findByExample(self::$db, $org);
                if (count($coresult) < 1)
                    return 0;
                elseif ($getobject)
                    return $coresult[0];
                else
                    return $coresult[0]->getNaam();
                break;
            default:
                ecpimport("database.hulpverleners", "class");
                $hulp = new Hulpverleners();
                $hulp->setId($pat->getToegewezenId());
                $huresult = Hulpverleners::findByExample(self::$db, $hulp);
                if (count($huresult) < 1)
                    return 2;
                elseif ($getobject)
                    return $huresult[0];
                else
                    return $huresult[0]->getNaam();
                break;
        }
    }

    /**
     * Get PDO object from factory and create Patient object, return the last one.
     * @return \Patient
     */
    private static function startPatient() {
        self::$db = ECPFactory::getPDO("patient");
        return new Patient();
    }

    /**
     * Vernieuw het DatabaseObject "Patient" voor dit model (alle settings worden verwijderd)
     */
    public function restartPatient() {
        $this->patient = new Patient();
    }

}

?>
