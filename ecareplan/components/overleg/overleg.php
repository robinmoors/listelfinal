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
class ECP_Comp_Overleg_Controller implements ECP_ComponentController {

    protected $action = null;
    protected $vars = null;
    protected $model = null;
    protected $view = null;
    protected $app = null;
    protected $user = null;

    public function __CONSTRUCT() {
        ecpimport("components.overleg.base.overlegobserver"); //observer interface
        ecpimport("components.overleg.base.overlegobservable"); //observable (subject) interface
        ecpimport("components.overleg.base.model"); //basis model
        ecpimport("components.overleg.base.view"); //basis view (observable)
        //onderstaand moet op termijn verdwijnen!
        ecpimport("components.overleg.overlegmodel"); //overleg model
        ecpimport("components.overleg.overlegview"); //std view
        //bovenstaand moet op termijn verdwijnen!
        $this->action = "std_command";
        $this->app = ECPFactory::getApp(); //haal de app op om template te gaan veranderen
        $this->user = $this->app->getUser(); //via de app de user ophalen zodat we zeker de huidige user hebben :)
        $this->model = new ECP_Comp_OverlegModel($this->user->getUserId());
        $this->view = new ECP_Comp_OverlegView($this->app);
    }

    public function command($command) {
        if ($command == "command" || !is_callable(array(&$this, $command)) || $command == "command") {
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
        //we tonen de overleg aanvragen zodat we die kunnen starten :)
        $aanvragen = $this->model->getOverlegAanvragen();
        $this->view->viewList($aanvragen);
    }

    /**
     * Bewerken van een aanvraag of overleg ~ TODO ONDERSCHEID OVERLEG ? AANVRAAG ? ~
     * @url http://listel.be/ecareplan/overleg/bewerk/
     * 
     */
    
    public function bewerk() {
        if (!is_null($this->vars[0])) {
            ecpimport("components.overleg.base.overlegform");
            $formmodel = new ECP_Comp_OverlegForm();
            if (!is_null($this->vars[1])) {
                //er is ook een overlegid opgegeven...
                $overleg = $this->model->getOverlegById($this->vars[1]);
                $this->view->editOverleg($overleg, $formmodel->getForm("edit"));
            } else {
                //we hebben de code nodig en niet de ID dus eerst omzetten!!
                $aanvraag = $this->model->getOverlegAanvraag($this->vars[0]);
                if (count($aanvraag) > 1) { //meer dan 1 overleg gevonden dus daar uit kiezen...
                    
                    $this->view->viewOverlegList($aanvraag);
                }
                else{
                    $formmodel->getForm("aanvraag");
                    $this->view->viewOverlegAanvraag($aanvraag);
                    //$this->view->editOverleg($aanvraag, $formmodel->getForm("edit")); //maar 1 overleg dus dat ook bewerken...
                }
            }
        }else {
            $this->std_command();
        }
    }
    
    /**
     * Nieuw overleg gaan beginnen = Aanvraag starten!
     * @url http://listel.be/ecareplan/overleg/nieuw/
     */
    public function nieuw() {
        ecpimport("components.overleg.base.overlegform");
        $formmodel = new ECP_Comp_OverlegForm();
        if($_SERVER['REQUEST_METHOD']!="POST"){
            if (!is_null($this->vars[0])) { //patientnummer opgeven
                $pat_id = $this->vars[0];
                //patient met overleggen ophalen
                ecpimport("components.overleg.patient.model"); // het patientenmodel ophalen...
                $patmod = new ECP_Comp_Overleg_PatientModel($this->user->getUserId());
                $patient = $this->model->getOverlegByPatientId($pat_id);
                if ($patient == null) {
                    //patient had geen overleggen... Dan maar alleen patient opgeven
                    $patient = $patmod->getPatientById($pat_id);
                }
                //de toegewezen OC ophalen en bij data patient steken...
                $patient['toegewezen'] = $patmod->getPatientToewijzing($pat_id);
                //regionaal dienstencentra ophalen (RDC)
                $formmodel->updateRDCList($this->model->getRDC());
                //zorgaanbieders ophalen (ZA)
                $formmodel->updateZAList($this->model->getZA());
                //zorgaanbieders profiel PSY ophalen
                $formmodel->updatePSYList($this->model->getPSY());
                $this->view->newOverleg($patient, $pat_id, $formmodel->getForm("new"));
            } else {
                $patienten = $this->model->getAllPatients();
                $formmodel->updatePatientList($patienten);
                $this->view->selectPatient($patienten, $formmodel->getForm("select"));
            }
        }else{
            //we zouden nu data hebben mee gekregen :)
            if(array_key_exists("values", $_POST) && array_key_exists("patid", $_POST)){
                $session = ECPFactory::getSession();
                if($session->isActive()){
                    //hier normaal controle op loginpin
                    $patient = $this->model->getOverlegByPatientId($_POST['patid']);
                    if ($patient == null) {
                        //patient had geen overleggen... Dan maar alleen patient opgeven
                        ecpimport("components.overleg.patient.model"); // het patientenmodel ophalen...
                        $patmod = new ECP_Comp_Overleg_PatientModel($this->user->getUserId());
                        $patient = $patmod->getPatientById($_POST['patid']);
                    }
                    if($patient == null) {
                        ecpexit('{"succes":"negative","message":"Oei het loopt even mis!<br/>De server kon de patient niet vinden..."}');
                    }
                    //regionaal dienstencentra ophalen (RDC)
                    $formmodel->updateRDCList($this->model->getRDC());
                    //zorgaanbieders ophalen (ZA)
                    $formmodel->updateZAList($this->model->getZA());
                    //zorgaanbieders profiel PSY ophalen
                    $formmodel->updatePSYList($this->model->getPSY());
                    //data insteken
                    $values = json_decode($_POST['values'],true); //json string naar assoc array parsen..
                    $report = $formmodel->validateValuesNewOverleg($values);
                    if($report===0){
                        ecpexit('{"succes":"negative","message":"Oei het loopt even mis!<br/>De server kon niets opmaken uit de gestuurde waarden..."}');
                    }
                    //en dan nu valideren :)
                        //print_r($report);
                    $error = $formmodel->validateNewOverleg($report);
                        //print_r($error);
                    ecpimport("components.overleg.aanvraag.model");
                    $aanmod = new ECP_Comp_Overleg_AanvraagModel($_POST['patid']);
                    if($aanmod->setAanvraag($patient, $values)){
                        ecpexit('{"succes":"positive","message":"Het overleg werd aangevraagd!<br/>Het systeem keert terug naar de overleglijst..."}');
                    }else{
                        ecpexit('{"succes":"negative","message":"Oei het loopt even mis!<br/>Onze database kon de aanvraag niet verwerken.<br/>Probeer opnieuw of neem contact op met de beheerder."}');
                    }
                }else{
                    ecpexit('{"succes":"negative","reason":"signout","message":"U bent niet aangemeld!<br/>De server kon bijgevolg je aanvraag niet uitvoeren.."}');
                }
            }else {
                echo '{"succes":"negative","message":"Oei het loopt even mis!<br/>De server onbegreep niet wat je wilde doorsturen.."}';
                ecpexit();
            }
        }
    }
    
    /**
     * Het overleg plannen = aanvraag omzetten naar een overleg (proberen toch..)
     * Dit is een POST-only commando!
     */
    public function aanvraagplannen(){
        //we hebben de code nodig en niet de ID dus eerst omzetten!!
        if(array_key_exists("datum", $_POST)){
            $session = ECPFactory::getSession();
                if($session->isActive()){
                    //sessie is ok
                    ecpimport("components.overleg.base.overlegform");
                    $formmodel = new ECP_Comp_OverlegForm();
                    ecpimport("components.overleg.aanvraag.model");
                    $aanmod = new ECP_Comp_Overleg_AanvraagModel(0);
                    $aanvraag = $aanmod->getAanvraagById($this->vars[0],true);
                    //we hebben het formulier en de gegevens vd aanvraag...
                    $formmodel->getForm("aanvraag");
                    $error[] = ECPFactory::getForm("aanvraagoverleg")->smartInsert($_POST)->validate();
                    if($error[0]){
                        //de waarde = 1 dus validatie was goed :)
                        //Nu kijken of er al overleggen bestaan (voor gegevens) en of er een overleg ook op die datum staat (wat niet mag)
                        $datum = ECPFactory::getForm("aanvraagoverleg")->datum->value;
                        if($this->model->getOverlegByDatum($datum,true)){
                            //we hadden bij de test het object gevraagd = sneller resultaat omdat er geen omzetting gebeurd (terzijde)
                            
                            //er is een overleg gevonden op de datum! dat mag niet..
                            ecpexit('{"succes":"negative","message":"Er bestaat al een overleg op deze datum<br/>Elke patient kan maar 1 overleg per dag hebben!"');
                        }else{
                            //geen overleg op die datum, we gaan verder...
                            //zijn er oudere overleggen? zoja, neem er 1tje..
                            $oude = $this->model->getOuderOverleg($datum,true);
                            $extra = null;
                            if($oude){
                                //waardes kijken of die bestaan en dan instellen
                                $extra = array();
                                $extra['ContactHvl'] = $extra->getContactHvl() ? $extra->getContactHvl() : false;
                                $extra['ContactMz'] = $extra->getContactHvl() ? $extra->getContactHvl() : false;
                               // $extra['psy_algemeen'] = $extra->getPsyAlgemeen() ? $extra->getPsyAlgemeen() : false;
                               // $extra['psy_doelstellingen'] = $extra->getPsyDoelstellingen() ? $extra->getPsyDoelstellingen : false;
                               // dient voor psy!
                            }
                            //TPRechten
                            
                            
                            //toegewezen genre en id bepalen
                            
                            //problematiek instellen
                            
                            //overleg aanmaken
                            
                            //patient updaten
                            
                            //huidige betrokkenen ophalen en tp plan aanmaken
                            
                        }
                        //dan gaan we de aanvraag omzetten naar een overleg!! 
                        $aanmod->setAanvraagToOverleg($aanvraag, $datum);
                        ecpexit('{"succes":"positive","message":"De opgegeven waarde was fout!"}');
                    }else{
                        //validatie niet ok..
                        ecpexit('{"succes":"negative","message":"De opgegeven waarde was fout!","error":"datum"}');
                    }
                }else{
                    //sessie niet ok..
                    ecpexit('{"succes":"negative","reason":"signout","message":"U bent niet aangemeld!<br/>De server kon bijgevolg je aanvraag niet uitvoeren.."}');
                }
        }else{
            //post waardes niet ok.. als je braafjes alles doet kan dat normaal ook niet..
            ecpexit('{"succes":"negative","message":"Oei het loopt even mis!<br/>De server begreep niet wat je wilde doorsturen.."}');
        }
    }

}

?>
