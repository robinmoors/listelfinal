<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ECP_Comp_Overleg_Goal_Afronden{
    public function getMessage($overleg, $patient, $aanvraag, $betrokkenen){
        $c = count($betrokkenen);
        if($c<3){
            return "<span class='red'>GOAL: Er moeten minstens 3 teamleden zijn alvorens dit overleg kan vergoed worden.</span>";
        }else if($c>=3 && !$overleg->getLocatie()){
            return "<span class='green'>GOAL: Dit overleg is vergoedbaar!</span>";
        }else{
            return "<span class='red'>GOAL: Het overleg moet plaatsvinden bij de patient!</span>";
        }
    }
}

?>
