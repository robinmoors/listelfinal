<?php
/**
 * Description of OverlegGDT
 *
 * @author Robin Moors, Joris Jacobs
 */
ecpimport("database.overleggen.OverlegExtended", "trait");
ecpimport("database.overleggen.OverlegOrg", "trait");
ecpimport("database.overleggen.OverlegLok", "trait");
ecpimport("database.overleggen.OverlegOmb", "trait");
ecpimport("database.overleggen.OverlegOrg", "trait");
ecpimport("database.overleggen.OverlegPsy", "trait");
ecpimport("database.overleggen.OverlegTp", "trait");
ecpimport("database.Overlegbasis", "class");

class OverlegGewoon extends Overlegbasis{
    
}

/**
 * * Uitgeschreven en operationeel
 */
class OverlegMenos extends Overlegbasis{
    use OverlegOmbTrait;
    public static $db;
    
    public function startOmb(){
        self::$db = ECPFactory::getPDO("Overleggen");
        return new OverlegMenos();
    }
    
    /**
     * Zoekt een overleg en maakt er een nieuw overlegMenos van
     * @param type $overlegId
     * @return type OverlegMenos
     */
    private static function findByIdAndConvert($overlegId){
        self::$db = ECPFactory::getPDO("Overleggen");
        $result = Overlegbasis::findById(self::$db, $overlegId); 
        $array = $result->toHash();

        $omb = new OverlegMenos();
        $omb->assignByHashOmb($array);
        return $omb;
        //return $result[0];
    }
    
    /**
     * Zoekt een overleg en maakt een nieuw overlegMenos met de bijgevoegde parameters
     * @param type $overlegId
     * @param type $ombFactuur
     * @param type $ombActief
     * @param type $ombRangorde
     * @return type OverlegMenos
     */
    public static function createOmbFromOverleg($overlegId,$ombFactuur=NULL,$ombActief=NULL,$ombRangorde=NULL){
        $omb = self::findByIdAndConvert($overlegId);
        if($omb){
            $omb->setOverlegId($omb->getId())
                ->setOmbFactuur($ombFactuur)
                ->setOmbActief($ombActief)
                ->setOmbRangorde($ombRangorde);
        return $omb;
        }
    }
    
    /**
     * Geeft een heel object OverlegMenos terug (bundelt 2 tabellen)
     * @param type $overlegId
     * @return type OverlegMenos $omb
     */
    public static function getOverlegMenos($overlegId){
        $omb = self::findByIdAndConvert($overlegId);
        $omb2 = OverlegMenos::findByOverleg(self::$db, $overlegId);
        $omb->setUid($omb2->getUid())
        ->setOverlegId($omb2->getOverlegId())
        ->setOmbFactuur($omb2->getOmbFactuur())
        ->setOmbActief($omb2->getOmbActief())
        ->setOmbRangorde($omb2->getOmbRangorde());
        return $omb;
    }
    
    /**
     * Delete de ombregistratie en/of overleg
     * @param type $overlegId
     * @param type $parent ook het overleg delete?
     */
    public static function deleteOverlegMenos($overlegId,$parent){
        $omb = OverlegMenos::getOverlegMenos($overlegId);
        if($omb){
            $omb->deleteFromDatabaseOmb(self::$db,$parent);
        }
    }
}

class OverlegLok extends Overlegbasis{
    use OverlegLokTrait, OverlegOrgTrait{
        OverlegLokTrait::setUid insteadof OverlegOrgTrait;
        OverlegLokTrait::getUid insteadof OverlegOrgTrait;
        OverlegLokTrait::getOverlegId insteadof OverlegOrgTrait;
        OverlegLokTrait::setOverlegId insteadof OverlegOrgTrait;
        OverlegOrgTrait::setUid as setIdOrg;
        OverlegOrgTrait::getUid as getIdOrg;
        OverlegOrgTrait::getOverlegId as setOverlegIdOrg;
        OverlegOrgTrait::setOverlegId as getOverlegIdOrg;
    }
}


class OverlegGDT extends Overlegbasis{
    use OverlegExtendedTrait, OverlegOrgTrait{
        OverlegExtendedTrait::setUid insteadof OverlegOrgTrait;
        OverlegExtendedTrait::getUid insteadof OverlegOrgTrait;
        OverlegExtendedTrait::getOverlegId insteadof OverlegOrgTrait;
        OverlegExtendedTrait::setOverlegId insteadof OverlegOrgTrait;
        
        OverlegOrgTrait::setUid as setIdOrg;
        OverlegOrgTrait::getUid as getIdOrg;
        OverlegOrgTrait::getOverlegId as setOverlegIdOrg;
        OverlegOrgTrait::setOverlegId as getOverlegIdOrg;
    }
}


class OverlegPsy extends Overlegbasis {
    use OverlegOrgTrait, OverlegPsyTrait{
        OverlegOrgTrait::setUid insteadof OverlegPsyTrait;
        OverlegOrgTrait::getUid insteadof OverlegPsyTrait;
        OverlegOrgTrait::getOverlegId insteadof OverlegPsyTrait;
        OverlegOrgTrait::setOverlegId insteadof OverlegPsyTrait;
        
        OverlegPsyTrait::setUid as setIdPsy;
        OverlegPsyTrait::getUid as getIdPsy;
        OverlegPsyTrait::getOverlegId as setOverlegIdPsy;
        OverlegPsyTrait::setOverlegId as getOverlegIdPsy;
    }
}

/*
Class OverlegPsy2013 extends Overlegbasis{
    use OverlegExtendedTrait, OverlegOrgTrait, OverlegPsyTrait;
}
 * */

/**
 * Uitgeschreven en operationeel
 */
class OverlegTp extends Overlegbasis{
    use OverlegTpTrait;
    
    public static $db;
    
    public function startTp(){
        self::$db = ECPFactory::getPDO("Overleggen");
        return new OverlegTp();
    }
    
    /**
     * Zoekt een overleg en maakt er een nieuw overlegTp van
     * @param type $overlegId
     * @return type OverlegTp
     */
    private static function findByIdAndConvert($overlegId){
        self::$db = ECPFactory::getPDO("Overleggen");
        $result = Overlegbasis::findById(self::$db, $overlegId); 
        $array = $result->toHash();

        $tp = new OverlegTp();
        $tp->assignByHashTp($array);
        return $tp;
    }
    
    /**
     * 
     * @param type $overlegId
     * @param type $tpVerslag
     * @param type $tpAuteur
     * @param type $tpNieuwePartners
     * @param type $tpRechtenoc
     * @return type
     */
    public static function createTpFromOverleg($overlegId,$tpVerslag=NULL,$tpAuteur=NULL,$tpNieuwePartners=NULL,$tpRechtenoc=NULL){
        $tp = self::findByIdAndConvert($overlegId);
        if($tp){
            $tp->setOverlegId($tp->getId())
                ->setTpVerslag($tpVerslag)
                ->setTpAuteur($tpAuteur)
                ->setTpNieuwepartners($tpNieuwePartners)
                ->setTpRechtenoc($tpRechtenoc);
            return $tp;
        }
       
    }
    
    /**
     * Geeft een heel object OverlegTp terug (bundelt 2 tabellen)
     * @param type $overlegId
     * @return type OverlegTp $tp
     */
    public static function getOverlegTp($overlegId){
        $tp = self::findByIdAndConvert($overlegId);
        $tp2 = OverlegTp::findByOverlegTp(self::$db, $overlegId);
        $tp->setUid($tp2->getUid())
                ->setOverlegId($tp->getId())
                ->setTpVerslag($tp2->getTpVerslag())
                ->setTpAuteur($tp2->getTpAuteur())
                ->setTpNieuwepartners($tp2->getTpNieuwePartners())
                ->setTpRechtenoc($tp2->getTpRechtenoc());
        return $tp;
    }
    
    /**
     * Delete de Tp en/of overlegbasis
     * @param type $overlegId
     * @param type $parent ook het overleg delete?
     */
    public static function deleteOverlegTp($overlegId,$parent){
        $tp = OverlegTp::getOverlegTp($overlegId);
        if($tp){
            $tp->deleteFromDatabaseTp(self::$db,$parent);
        }
    }
}
?>
