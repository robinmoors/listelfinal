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
    private $subject;
    public function update($subject) {
        $this->messageReader($subject->getState());
        $this->subject = $subject;
        //echo "notified::".$subject->getState()."<br/>";
    }    
    
    private function messageReader($message){
        $msg = explode('.',$message);
        if($this->currentmethod===null && $msg[2]==="start"){
            $this->currentmethod = $msg[1];
            $this->takeMethod();
        }else if($this->currentaction===null && $msg[2]==="start"){
            $this->takeAction();
            $this->currentaction = $msg[1];
        }else if($msg[2]==="stop" && $this->currentaction !== null){
            //$this->takeAction();
            $this->currentaction = null;
        }else if($msg[2]==="stop" && $this->currentaction === null){
            $this->takeMethod();
            $this->currentmethod = null;
        }
    }
    
    private function takeAction(){
        //echo $this->currentaction." ".$this->currentmethod;
        if($this->currentaction==="script" && $this->currentmethod==="editoverleg"){
            $this->subject->setStack("var aanwezig='0'; var instemming='0';");
        }
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
