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
        //$this->test1();
        //$this->test2();
        //$this->test3();
        //$this->test4();
        //$this->test5();
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
    
     /**
     * een gewoon overleg ophalen met overlegId en een omb erbij creëren
     */
    private function test1(){
        self::$db = ECPFactory::getPDO("Overleggen");
        $overlegId = "433";
        //$omb = $this->model->createOmbFromOverleg($overlegId);
        //$omb->insertIntoDatabaseOmb(self::$db);
            
        $ombFactuur = "9/9/9999";
        $ombActief = "1";
        $ombRangorde = "1";
        $omb2 = $this->model->createOmbFromOverleg($overlegId, $ombFactuur, $ombActief, $ombRangorde);
        $omb2->insertIntoDatabaseOmb(self::$db);
            
    }
    
    /*
     * een nieuw overleg aanmaken
     */
    private function test2(){
        self::$db = ECPFactory::getPDO("Overleggen");
        $overleg = new OverlegGewoon();            
        $overleg->setPatientCode("11/AL-09-510406-A")
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
    }
    
    private function test3(){
        $overlegId = "99";
        $omb = $this->model->getOverlegMenos($overlegId);
    }
    
    /*
     * een overleg verwijderen (zowel omb als overlegGewoon)
     */
    private function test4(){
        $overlegId = "432";
        $this->model->deleteOverlegMenos($overlegId, TRUE);
    }
    
    /*
     * verander waarden van overleg (zowel omb als overleggewoon)
     */
    private function test5(){
        self::$db = ECPFactory::getPDO("Overleggen");
        $omb = $this->model->getOverlegMenos("433");
        $omb->setOmbActief("1");
        $omb->setOmbFactuur("11/11/1111");
        $omb->setDatum("22221010");
        $omb->updateToDatabaseOmb(self::$db,TRUE);
    }
}

?>
