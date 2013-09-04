<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ECP_Comp_Overleg_Listel_Observer implements ECP_OverlegObserver{
    private $currentmethod = null;
    private $currentaction = null;
    private $methodtaken = false;
    private $actiontaken = false;
    public function update($subject) {
        $this->messageReader($subject->getState());
        //echo "notified::".$subject->getState()."<br/>";
    }    
    
    private function messageReader($message){
        $msg = explode('.',$message);
        if($this->currentmethod===null && $msg[2]==="start"){
            $this->currentmethod = $msg[1];
            $this->takeMethod();
        }else if($this->currentaction===null && $msg[2]==="start"){
            
            $this->currentaction = $msg[1];
        }else if($msg[2]==="stop" && $this->currentaction !== null){
            $this->takeAction();
            $this->currentaction = null;
        }else if($msg[2]==="stop" && $this->currentaction === null){
            $this->takeMethod();
            $this->currentmethod = null;
        }
    }
    
    private function takeAction(){
        
    }
    
    private function takeMethod(){
        //echo $this->currentmethod;
        if($this->currentmethod==="editoverleg" && !$this->methodtaken){
            $form = ECPFactory::getForm("basisgegevens");
            $form->removeField($form->aanwezig);
            $form->removeField($form->instemming);
            $this->methodtaken = true;
        }
    }
}
?>
