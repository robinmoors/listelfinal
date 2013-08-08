<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of overleg
 *
 * @author robindell
 */
class ECP_Comp_Omb_Controller implements ECP_ComponentController {

    protected $action = null;
    protected $vars = null;
    protected $model = null;
    protected $view = null;
    protected $app = null;
    protected $user = null;
    
    //TEMP om overleggen te testen
    protected static $db; //db is een static object (zie factory)

    public function __CONSTRUCT() {
        ecpimport("components.overleg.overlegobserver"); //observer interface
        ecpimport("components.overleg.overlegobservable"); //observable (subject) interface
        ecpimport("components.omb.ombmodel"); //std model
        ecpimport("components.omb.ombview"); //std view
        $this->action = "std_command";
        $this->app = ECPFactory::getApp(); //haal de app op om template te gaan veranderen
        $this->user = $this->app->getUser(); //via de app de user ophalen zodat we zeker de huidige user hebben :)
        $this->model = new ECP_Comp_OmbModel($this->user->getUserId());
        $this->view = new ECP_Comp_OmbView($this->app);
    }

    public function command($command) {
        if ($command == "command" || !is_callable(array(&$this, $command))) {
            $command = "command_error";
        }
        $this->action = $command;
    }

    public function command_error() {
        $this->std_command();
    }

    public function execute() {
        call_user_func(array(&$this, $this->action));
    }

    public function params($vars) {
        $this->vars = $vars;
    }

    public function lijst() {
        $this->std_command();
    }

    public function std_command() {
        $ombs = $this->model->getOmbs();
        $this->view->viewListOmb($ombs);
    }

    public function bewerk(){
        /*
             * BEGIN TEST voor de overleggen aan te maken
             */
            
             // 1.  overlegGewoon
        /*
            self::$db = ECPFactory::getPDO("Overleggen");
            $overleg = new OverlegGewoon();            
            $overleg->setId("10")
                    ->setPatientCode("11/AL-09-510406-A")
                    ->setDatum("20091010")
                    ->setLocatie("Hasselt")
                    ->setSoortProblematiek("fysisch")
                    ->setKeuzeVergoeding("-88")
                    ->setGeldVoorHvl("0")
                    ->setAfgerond("0")
                    ->setControle("1")
                    ->setLogincode("OHKHHPLOMWBWFIRA")
                    ->setToegewezenGenre("gewoon");
            $overleg->insertIntoDatabase(self::$db);
        */
        
        //  2.  overlegOmb
        // een gewoon overleg ophalen en een omb erbij creëren
            self::$db = ECPFactory::getPDO("Overleggen");
            $ex = new OverlegGewoon();
            $ex->setId("6");
            $result = Overlegbasis::findByExample(self::$db, $ex);
            
            
            $data = $this->tempResultToArray($result, OverlegGewoon::getFieldNames());
            $this->tempCreateOmb($data);
            
        // bestaand update
            $this->tempUpdateOmb($data);
            
        //bestaand delete
            $this->tempDeleteOmb();
            
            
            /*  vb nieuw menos
            $omb->setOverlegId("3")
                ->setOmbFactuur("11/11/2009")
                ->setOmbActief("1")
                ->setOmbRangorde("1")
                ->setId("8")
                ->setPatientCode("11/AL-09-510406-A")
                ->setDatum("20091010")
                ->setLocatie("Sint-Truiden")
                ->setSoortProblematiek("fysisch")
                ->setKeuzeVergoeding("-88")
                ->setGeldVoorHvl("0")
                ->setAfgerond("0")
                ->setControle("1")
                ->setLogincode("OHKHHPLOMWBWFIRA")
                ->setToegewezenGenre("gewoon");
            */
            
            /*
             * END TEST
             */
    }
    
    public function nieuw(){
        ecpimport("components.omb.listel.ombform");
        $formmodel = new ECP_Comp_OmbForm();
        if ($_SERVER['REQUEST_METHOD'] != "POST") {
            /*
             * Model
             */
            $contactwijze = $this->model->getContactwijze();
            $relatie = $this->model->getHoofdrelatie();
            //$probleemfactor = $this->model->getProbleemfactor();
            
            //$test = $this->model->testFunction();
            /*
             * Form
             */
            $formmodel->updateContactwijzeList($contactwijze);
            $formmodel->updateHoofdrelatieList($relatie);
            /*
             * View
             */
            $this->view->viewMeldingForm($formmodel->getForm("melding"));
        } else {
            echo '{"succes":"negative","message":"Oei het loopt even mis!<br/>De server ontving geen waarden van het formulier..."}';
            ecpexit();
        }
    }
    
    public function checkOmb(){
        $form = ECPFactory::getForm("base");
        $form->smartInsert($_POST);
        $rapport = $form->validate();
        if(is_array($rapport)){
          echo '{"error":"'.$rapport[0][0].'"}'; //eerste index:: 1. velden met fouten, 2. fout per veld, 3. aantal fouten totaal.
          exit(); //geen view doen :)
        } else{
            $omb = $this->model->newOmb();
        }
    }
    
    public function tempResultToArray($result, $names){
        echo '----------- to array -----------';
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
    
    public function tempCreateOmb($data){
        $omb = new OverlegMenos();
        foreach ($data as $value) {
            //gewoon overleg kopiëren naar menos
            $omb->assignByHashOmb($value);
            //extra waarden toevoegen specifiek voor menos
            $omb->setOverlegId($omb->getId())
                    ->setOmbFactuur("11/11/2009")
                    ->setOmbActief("1")
                    ->setOmbRangorde("1");
                    //->setUid("3"); 
            //insert in db, zonder ook het originele nog eens te inserte
            $omb->insertIntoDatabaseOmb(self::$db,false);
        }
    }
    
    public function tempUpdateOmb($data){
        $omb = new OverlegMenos();
        foreach ($data as $value) {
            $omb->assignByHashOmb($value);
            $omb->setOverlegId($omb->getId());
            $omb->setLocatie("Tongeren");   //Verandering in Parent
            $omb->setUid("6");              //verandert rij met uid = 6
            $omb->setOmbFactuur("22/12/2000");  //verandert de datum van de factuur
            $omb->updateToDatabaseOmb(self::$db, TRUE);
        }
    }
    
    public function tempDeleteOmb(){
        //zoeken o.b.v. uid
        $uid = "1";
        $data = OverlegMenos::findById(self::$db,$uid);
        $data->deleteFromDatabaseOmb(self::$db,FALSE);
        
        //Zoeken o.b.v overlegId (TO DO)
        
         
    }
}

?>
