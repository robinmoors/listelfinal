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
    /*
    OverlegOmbTrait::assignByHash as assignByHashOmb;
    OverlegOmbTrait::findById as findByIdOmb;
    OverlegOmbTrait::getFieldNames as getFieldNamesOmb;
    OverlegOmbTrait::toArray as toArrayOmb;
    */  
}

class OverlegLok extends Overlegbasis{
    use OverlegLokTrait, OverlegOrgTrait{
        OverlegLokTrait::setId insteadof OverlegOrgTrait;
        OverlegLokTrait::getId insteadof OverlegOrgTrait;
        OverlegLokTrait::getOverlegId insteadof OverlegOrgTrait;
        OverlegLokTrait::setOverlegId insteadof OverlegOrgTrait;
        OverlegOrgTrait::setId as setIdOrg;
        OverlegOrgTrait::getId as getIdOrg;
        OverlegOrgTrait::getOverlegId as setOverlegIdOrg;
        OverlegOrgTrait::setOverlegId as getOverlegIdOrg;
    }
}

/*
class OverlegGDT extends Overlegbasis{
    use OverlegExtendedTrait, OverlegOrgTrait;
}
*/

class OverlegPsy extends Overlegbasis {
    use OverlegOrg, OverlegPsy{
        OverlegOrgTrait::setId insteadof OverlegPsyTrait;
        OverlegOrgTrait::getId insteadof OverlegPsyTrait;
        OverlegOrgTrait::getOverlegId insteadof OverlegPsyTrait;
        OverlegOrgTrait::setOverlegId insteadof OverlegPsyTrait;
        
        OverlegPsyTrait::setId as setIdPsy;
        OverlegPsyTrait::getId as getIdPsy;
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
