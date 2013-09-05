<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ECP_Comp_Overleg_Listel_Afronden{
    public function getMessage($overleg, $patient, $aanvraag, $betrokkenen){
        $c = count($betrokkenen);
        if($c<3){
            return "<span class='red'>LISTEL: Er moeten minstens 3 teamleden zijn alvorens dit overleg kan vergoed worden.</span>";
        }else{
            return "<span class='green'>LISTEL: Dit overleg is vergoedbaar!</span>";
        }
    }
}
?>
