<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Comp_OmbModel {

    protected $uid = 0;
    protected static $db; //db is een static object (zie factory)

    public function __CONSTRUCT($uid) {
        $this->uid = $uid;
    }
 
    private static function resultToArray($result,$names){
        if(!is_array($names) || $result==null) return null;
        foreach($result as $resource){//array van objecten dus een object nemen..
            $res=$resource->toArray();//dat object omzetten naar array
            foreach($res as $key => $value){
                $ar[$names[$key]] = $value; //hier gebeurd de key-wissel..
            }
            $data[] = $ar; //alles netjes terug in een array zetten :)
        }
        return $data;
    }
    
    public static function getContactwijze(){
        self::$db = ECPFactory::getPDO("OmbContactwijze");
        $contact = new OmbContactwijze();
        $sql = "SELECT * FROM `omb_contactwijze`";
        $result = $contact->findBySql(self::$db, $sql);
        return self::resultToArray($result, OmbContactwijze::getFieldNames());
    }
    
    public static function getProbleemfactor(){
        self::$db= ECPFactory::getPDO("OmbProbleemfactor");
        $probleem= new OmbProbleemfactor();
        $sql = "SELECT * FROM `omb_probleemfactor`";
        $result = $probleem->findBySql(self::$db, $sql);
        return self::resultToArray($result, OmbProbleemfactor::getFieldNames());
    }
    
    public static function getHoofdrelatie(){
        self::$db= ECPFactory::getPDO("OmbHoofdrelatie");
        $relatie = new OmbHoofdrelatie();
        $sql = "SELECT * FROM `omb_hoofdrelatie";
        $result = $relatie->findBySql(self::$db, $sql);
        return self::resultToArray($result, OmbHoofdrelatie::getFieldNames());
    }
    
    public static function getOmbs(){
        self::$db = ECPFactory::getPDO("OmbRegistratie","class");
        $omb = new OmbRegistratie();
        $sql = "SELECT * FROM `omb_registratie";
        $result = $omb->findBySql(self::$db, $sql);
        return self::resultToArray($result, OmbRegistratie::getFieldNames());
    }
    
    public function startOmb(){
        self::$db = ECPFactory::getPDO("Overleggen");
        return new OverlegMenos();
    }
    
    /**
     * Zoekt een overleg en maakt er een nieuw overlegMenos van
     * @param type $overlegId
     * @return type OverlegMenos
     */
    private function findByIdAndConvert($overlegId){
        self::$db = ECPFactory::getPDO("Overleggen");
        $result = Overlegbasis::findById(self::$db, $overlegId); 
        $array = $result->toHash();

        $omb = new OverlegMenos();
        $omb->assignByHashOmb($array);
        return $omb;
    }
    
    /**
     * Zoekt een overleg en maakt een nieuw overlegMenos met de bijgevoegde parameters
     * @param type $overlegId
     * @param type $ombFactuur
     * @param type $ombActief
     * @param type $ombRangorde
     * @return type OverlegMenos
     */
    public function createOmbFromOverleg($overlegId,$ombFactuur=NULL,$ombActief=NULL,$ombRangorde=NULL){
        $omb = $this->findByIdAndConvert($overlegId);
        if($omb){
            $omb->setOverlegId($omb->getId())
                ->setOmbFactuur($ombFactuur)
                ->setOmbActief($ombActief)
                ->setOmbRangorde($ombRangorde);
        return $omb;
        }
    }
    
    public function getOverlegMenos($overlegId){
        $omb = $this->findByIdAndConvert($overlegId);
        $omb2 = OverlegMenos::findByOverleg(self::$db, $overlegId);
        $omb->setUid($omb2->getUid())
        ->setOverlegId($omb2->getOverlegId())
        ->setOmbFactuur($omb2->getOmbFactuur())
        ->setOmbActief($omb2->getOmbActief())
        ->setOmbRangorde($omb2->getOmbRangorde());
        return $omb;
    }
    
    public function deleteOverlegMenos($overlegId,$parent){
        $omb = $this->getOverlegMenos($overlegId);
        if($omb){
            $omb->deleteFromDatabaseOmb(self::$db,$parent);
        }
    }
    
}
?>