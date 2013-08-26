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

class OverlegMenos extends Overlegbasis{
    use OverlegOmbTrait;
    
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

class OverlegTp extends Overlegbasis{
    use OverlegTpTrait;
}
?>
