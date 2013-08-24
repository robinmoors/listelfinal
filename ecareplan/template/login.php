<?php
/*
 * EcarePlan - Made by Robin Moors, Joris Jacobs
 * @version 1.0
 * Template for Listel vzw
 */

defined("ECP_AC") or die("Stop! Wat we onder de motorkap hebben zitten houden we liever verborgen.");

class ECP_Template_Login extends ECP_Template implements ECP_TemplateInterface {
    
    public function __CONSTRUCT() {
        parent::$styles = array("listel");
        parent::$scripts = array("ecareplan");
        parent::$rawbody = "
             <div id='overflowbox' style='display:none;'>
    <div id='overflow'>&nbsp;</div>
    <div id='overflowcontent' style='display:none;' class='clearfix'><header id='vrflheader'></header><section id='vrflcontent'></section><footer></footer></div>
</div> 

 <header>
    <div class='wrapper nopadding'>
        <div class='top selfclear'>
            <div class='logo'>
                <h1>LISTEL vzw</h1>
            </div>
        </div>
        <nav class='selfclear'>
            <ul>
                <li>
                    <img src='/listel_new/lib/images/home.png' alt='H' title='Homepagina'/> <span class='content'>Home</span>
                </li>
                <li>
                    <img src='/listel_new/lib/images/balloons.png' alt='O' title='Overleg...'/> <span class='content'>Overleg</span>
                </li>
                <li>
                    <img src='/listel_new/lib/images/marker.png' alt='E' title='Evaluaties...'/> <span class='content'>Evaluaties</span>
                </li>
                <li>
                    <img src='/listel_new/lib/images/drawer.png' alt='Z' title='Zorgplannen...'/> <span class='content'>Zorgplannen</span>
                </li>
                <li>
                    <img src='/listel_new/lib/images/chair.png' alt='OZ' title='Ouderenzorg...'/> <span class='content'>Ouderenbehandeling</span>
                </li>
                <li>
                    <img src='/listel_new/lib/images/briefcase.png' alt='F' title='Formulieren...'/> <span class='fastcontent'>Formulieren</span>
                </li>
            </ul>
        </nav>
    </div>
</header>
<div class='wrapper pagina'>
        <div class=\"header\">
            <hgroup>
                <h1>{[content-title]}</h1>
                <h3>{[content-sub-title]}</h3>
            </hgroup>
        </div>
        <div class='contents selfclear'>
            <div class='error' style='display:none;'>
                {[errors]}
            </div>
            <div class=\"main\">
                {[content]}
            </div>
        </div>
</div>
<footer>
    <div class='wrapper nopadding'>
        {[footer]} - Ecareplan v.{[version-name]} - LISTEL vzw A. Rodenbachstraat 29 bus 1, 3500 Hasselt
    </div>
</footer>           
 ";
    }

}
?>