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
