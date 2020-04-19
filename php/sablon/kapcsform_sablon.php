<?php

class CKapcsform_sablon extends CVaz_bovit_sablon
{
    
    function Foglal_panel1()
    {
        $Vissza["Mezok"][]=array("0"=>"email","1"=>"s","2"=>"*@@@E-mal cím§§§","3"=>"1");
        $Vissza["Mezok"][]=array("0"=>"vezeteknev","1"=>"s","2"=>"*@@@Vezetéknév§§§","3"=>"1");
        $Vissza["Mezok"][]=array("0"=>"keresztnev","1"=>"s","2"=>"*@@@Keresztnév§§§","3"=>"1");
        $Vissza["Mezok"][]=array("0"=>"orszag","1"=>"s","2"=>"@@@Ország§§§");
        $Vissza["Mezok"][]=array("0"=>"varos","1"=>"s","2"=>"@@@Város§§§");
        $Vissza["Mezok"][]=array("0"=>"iranyitoszam","1"=>"s","2"=>"@@@Irányítószám§§§");
        $Vissza["Mezok"][]=array("0"=>"utca","1"=>"s","2"=>"@@@Utca§§§");
        $Vissza["Mezok"][]=array("0"=>"telefon","1"=>"s","2"=>"*@@@Telefon§§§","3"=>"1");
        $Vissza["Mezok"][]=array("0"=>"fax","1"=>"s","2"=>"@@@Fax§§§");

        $Vissza["Html"]="<p>@@@Kedves Vendégünk!§§§<br>
        @@@Amennyiben foglalni szeretne, kérjük töltse ki az alábbi űrlapot!§§§ @@@Kollégáink 24 órán belül válaszolnak!§§§<br>
        @@@Ha kérdése van hívjon bennünket: +36/95/320-525, vagy írjon e-mailt az§§§ <a href='mailto:info@hotel-viktoria.hu'>info@hotel-viktoria.hu</a> @@@címre.§§§ @@@Fax: +36/95/320-525.§§§ </p>



                                <!-- Személyes adatok-->
                                <div class='half'>
                                    <fieldset>
                                        <legend>@@@Név§§§</legend>
                                        <label>
                                            <input type='text' name='email' id='email' placeholder='@@@E-mal cím§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='vezeteknev' id='vezeteknev' placeholder='@@@Vezetéknév§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='keresztnev' id='keresztnev' placeholder='@@@Keresztnév§§§'>
                                        </label>
                                    </fieldset>
                                </div>
                                <div class='half'>
                                    <fieldset>
                                        <legend>@@@Elérhetőség§§§</legend>

                                        <label>
                                            <input type='text' name='orszag' id='orszag' placeholder='@@@Ország§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='varos' id='varos' placeholder='@@@Város§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='iranyitoszam' id='iranyitoszam' placeholder='@@@Irányítószám§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='utca' id='utca' placeholder='@@@Utca§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='telefon' id='telefon' placeholder='@@@Telefon§§§'>
                                        </label>
                                        <label>
                                            <input type='text' name='fax' id='fax' placeholder='@@@Fax§§§'>
                                        </label>


                                    </fieldset>


                                    
                                    <a class='next_btn'>@@@Tovább§§§</a>

                                </div>";
        return $Vissza;                                
    }
    
    function Foglal_panel2()
    {
        $Vissza["Mezok"][]=array("0"=>"felnott","1"=>"s","2"=>"@@@Felnőtt§§§");
        $Vissza["Mezok"][]=array("0"=>"gyerek","1"=>"s","2"=>"@@@Gyermek§§§");
        $Vissza["Mezok"][]=array("0"=>"gyerekeletkor","1"=>"s","2"=>"@@@Gyermek(ek) életkora§§§");
        $Vissza["Mezok"][]=array("0"=>"ketagyas","1"=>"s","2"=>"@@@2 ágyas szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"egyagyas","1"=>"s","2"=>"@@@1 ágyas szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"ketagyasapartman","1"=>"s","2"=>"@@@2 ágyas apartman§§§");
        $Vissza["Mezok"][]=array("0"=>"negyagyasapartman","1"=>"s","2"=>"@@@4 ágyas apartman§§§");
        $Vissza["Mezok"][]=array("0"=>"mozgasserult","1"=>"s","2"=>"@@@Mozgássérült szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"egyagyasprenium","1"=>"s","2"=>"@@@1 ágyas Prémium szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"ketagyasprenium","1"=>"s","2"=>"@@@2 ágyas Prémium szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"negyagyasprenium","1"=>"s","2"=>"@@@4 ágyas családi szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"superiorapartman","1"=>"s","2"=>"@@@Superior apartman (2-4fő)§§§");
        $Vissza["Mezok"][]=array("0"=>"deluxapartman","1"=>"s","2"=>"@@@Delux apartman (4-6fő)§§§");

        
        $felnott=$this->Postgetv("felnott");
        if (!(isset($felnott)))$felnott="";

        $gyerekek=$this->Postgetv("gyerekek");
        if (!(isset($gyerekek)))$gyerekek="";
        
        
                
        
        
        
        
        
        $Vissza["Html"]="<div class='half'>
                                    <fieldset>
                                        <legend>@@@Vendégek száma§§§</legend>

                                        <label>
                                            <input type='text' name='felnott' id='felnott' placeholder='@@@Felnőtt§§§' value='$felnott'>
                                        </label>

                                        <label>
                                            <input type='text' name='gyerek' id='gyerek' placeholder='@@@Gyermek§§§' value='$gyerekek'>
                                        </label>

                                        <label>
                                            <input type='text' name='gyerekeletkor' id='gyerekeletkor' placeholder='@@@Gyermek(ek) életkora§§§'>
                                        </label>
                                    </fieldset>
                                </div>
                                <div class='half'>
                                    <fieldset>
                                        <legend>@@@Szobák száma§§§</legend>

                                            <fieldset>
                                                <legend>@@@Normál§§§</legend>
                                                <label>
                                                    <input type='text' name='ketagyas' id='ketagyas' placeholder='@@@2 ágyas szoba§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='egyagyas' id='egyagyas' placeholder='@@@1 ágyas szoba§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='ketagyasapartman' id='ketagyasapartman' placeholder='@@@2 ágyas apartman§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='negyagyasapartman' id='negyagyasapartman' placeholder='@@@4 ágyas apartman§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='mozgasserult' id='mozgasserult' placeholder='@@@Mozgássérült szoba§§§'>
                                                </label>

                                                
                                            </fieldset>

                                            <fieldset>
                                                <legend>@@@Emelt színvonalú§§§ </legend>
                                                <label>
                                                    <input type='text' name='egyagyasprenium' id='egyagyasprenium' placeholder='@@@1 ágyas Prémium szoba§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='ketagyasprenium' id='ketagyasprenium' placeholder='@@@2 ágyas Prémium szoba§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='negyagyasprenium' id='negyagyasprenium' placeholder='@@@4 ágyas családi szoba§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='superiorapartman' id='superiorapartman' placeholder='@@@Superior apartman (2-4fő)§§§'>
                                                </label>
                                                <label>
                                                    <input type='text' name='deluxapartman' id='deluxapartman' placeholder='@@@Delux apartman (4-6fő)§§§'>
                                                </label>
                                            </fieldset>
                                    </fieldset>
                                    <a class='next_btn'>@@@Tovább§§§</a>
                                </div>";
        return $Vissza;
    }
    
    function Foglal_panel3()
    {
        $Vissza["Mezok"][]=array("0"=>"erkezes","1"=>"s","2"=>"*@@@Érkezés dátuma§§§","3"=>"1");
        $Vissza["Mezok"][]=array("0"=>"tavozas","1"=>"s","2"=>"*@@@Távozás időpontja§§§","3"=>"1");

        $erkezes=$this->Postgetv("erkezes_f");
        if (!(isset($erkezes)))$erkezes="";
        
        $tavozas=$this->Postgetv("tavozas_f");
        if (!(isset($tavozas)))$tavozas="";
        
        $Vissza["Html"]="<fieldset>
                                        <legend>@@@Időpontok§§§</legend>
                                        <div class='half'>
                                            <label>
                                                <input class='datepicker' type='text' name='erkezes' id='erkezes' value='$erkezes' placeholder='@@@Érkezés dátuma§§§'>
                                            </label>
                                        </div>
                                        <div class='half'>
                                            <label>
                                                <input class='datepicker' type='text' name='tavozas' id='tavozas' value='$tavozas' placeholder='@@@Távozás időpontja§§§'>
                                            </label>
                                        </div>
                                    </fieldset>
                                    <a class='next_btn'>@@@Tovább§§§</a>";
        return $Vissza;                                    
    }
    
    function Foglal_panel4($Data)
    {
        $Vissza["Mezok"][]=array("0"=>"uzenet","1"=>"s","2"=>"@@@Egyéb§§§");
        $Vissza["Mezok"][]=array("0"=>"Csomagval","1"=>"k","2"=>"@@@Csomagok§§§");

        $Vissza["Mezok"][]=array("0"=>"felpanzio","1"=>"r","2"=>"@@@Félpanzió§§§");
        

        $Vissza["Mezok"][]=array("0"=>"potagy","1"=>"r","2"=>"@@@Pótágy§§§");
        $Vissza["Mezok"][]=array("0"=>"klimatizatszoba","1"=>"r","2"=>"@@@Klimatizált szoba§§§");
        $Vissza["Mezok"][]=array("0"=>"babaagy","1"=>"r","2"=>"@@@Babaágy§§§");

        $CSOM_IR="";
        

        $CSOM=$this->Postgetv("CSOM");
        if (!(isset($CSOM)))$CSOM="";

        
        foreach ($Data["Csomagok"] as $item)
        {
            $Jel="";
            if ($CSOM==$item["Azon"])$Jel="selected";
            
            $CSOM_IR.="<option value='".$item["Azon"]."' $Jel>".$item["Nev"]."</option>
";
        }

/*
        if ($CSOM!="")
        {
            $CSOM_IR.="
            $('#Csomagval').val('$CSOM').trigger('change');
            
            $('#Csomagval').trigger('chosen:updated');
            ";
        }*/
        $Vissza["Html"]= "<div class='half'>
                                    <fieldset>
                                        <legend>@@@Csomagok§§§</legend>

                                        <label>

                                            <select class='chosen_select' name='Csomagval[]' id='Csomagval' multiple data-placeholder='Csomagok választása'>
                                               $CSOM_IR
                                            </select>
                                        </label>
                                    </fieldset>
                                </div>
                                <div class='half'>
                                    <fieldset>
                                        <legend>@@@Extrák§§§</legend>

                                        <p>@@@Félpanzió§§§ </p>
                                        <div class='radio radio-inline'>
                                            <input id='inlineRadio1' value='@@@kérek§§§' name='felpanzio' type='radio'>
                                            <label for='inlineRadio1'>@@@kérek§§§</label>
                                        </div>
                                        <div class='radio radio-inline'>
                                            <input id='inlineRadio2' value='@@@nem kérek§§§' name='felpanzio' type='radio'>
                                            <label for='inlineRadio2'>@@@nem kérek§§§</label>
                                        </div>


                                        <p>@@@Pótágy§§§  </p>
                                        <div class='radio radio-inline'>
                                            <input id='potagy' value='@@@kérek§§§' name='potagy' type='radio'>
                                            <label for='potagy'>@@@kérek§§§</label>
                                        </div>
                                        <div class='radio radio-inline'>
                                            <input id='potagy2' value='@@@nem kérek§§§' name='potagy' type='radio'>
                                            <label for='potagy2'>@@@nem kérek§§§</label>
                                        </div>


                                        <p>@@@Klimatizált szoba§§§  </p>
                                        <div class='radio radio-inline'>
                                            <input id='klimatizatszoba' value='@@@kérek§§§' name='klimatizatszoba' type='radio'>
                                            <label for='klimatizatszoba'>@@@kérek§§§</label>
                                        </div>
                                        <div class='radio radio-inline'>
                                            <input id='klimatizatszoba2' value='@@@nem kérek§§§' name='klimatizatszoba' type='radio'>
                                            <label for='klimatizatszoba2'>@@@nem kérek§§§</label>
                                        </div>


                                        <p>@@@Babaágy§§§</p>
                                        <div class='radio radio-inline'>
                                            <input id='babaagy' value='@@@kérek§§§' name='babaagy' type='radio'>
                                            <label for='babaagy'>@@@kérek§§§</label>
                                        </div>
                                        <div class='radio radio-inline'>
                                            <input id='babaagy2' value='@@@nem kérek§§§' name='babaagy' type='radio'>
                                            <label for='babaagy2'>@@@nem kérek§§§</label>
                                        </div>

                        <label class='qaptcha_label'>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</label>
                        <div class='qaptcha'></div>
                        <script type='text/javascript'>
                        $(document).ready(function(){

                            // More complex call
                            $('.qaptcha').QapTcha({
                                txtLock: '@@@Zárva : Az űrlap nem küldhető§§§',    
                                txtUnlock: '@@@Nyitva! Az űrlap elküldhető§§§',    
                                autoSubmit : false,
                                autoRevert : true,
                                PHPfile : '/php/qaptcha.jquery.php'
                            });
                        });
                        </script>

                                        

                                    </fieldset>
                                    <fieldset>
                                        <legend>@@@Egyéb§§§</legend>
                                        <textarea name='uzenet' value='uzenet'></textarea>
                                    </fieldset>
                                    <a class='next_btn'>@@@Tovább§§§</a>
                                </div>";
            return $Vissza;
    }
    
    function Foglal_osszesito($Mezok)
    {
        $Td_ir="";
        $Masol_java="";
        $Defjava="";
        foreach($Mezok as $item)
        {
            $Td_ir.="                                    <tr>
                                        <th style='width: 25%;'>".$item[2]."</th>
                                        <td id='td".$item[0]."'></td>
                                    </tr>";
            $Masol_java.="Egymasol('".$item[0]."','".$item[1]."');
";
            if ((isset($item[3]))&&($item[3]=="1"))
            {
               // if ($Defjava!="")$Defjava.="else ";
                $Defjava.="if ( $('#".$item[0]."','#Reszlfoglalform').val()=='')
                {
                    alert('@@@Nem lehet üres:§§§ ".$item[2]."');
                }else ";
            }
        }
                
        return " <h3>@@@Összegzés§§§</h3>
                                <table class='table'>
                                  $Td_ir
                                </table>

<script>

function Foglellenor()
{
    $Defjava
    return true;
    return false;
}

function Osszesit_masol()
{ 
    
    
    
    
    $Masol_java
    
}

function Egymasol(nev,tipus)
{
    if (tipus=='r')
    {
        ert=$('input[name='+nev+']:checked', '#Reszlfoglalform').val();
        //alert(ert);
        
        $('#td'+nev).html(ert);
    }
    if (tipus=='s')
    {
        ert=$('#'+nev,'#Reszlfoglalform').val();
        $('#td'+nev).html(ert);
    }
    
    if (tipus=='k')
    {
        vane=$('#'+nev+' :selected','#Reszlfoglalform');

        ert='';
        if (vane.length>0)
        {

            ossz=$('#'+nev,'#Reszlfoglalform').val();        
        
            ig=ossz.length;
    
            for (c=0;c<ig;c++)
            {
                if (ert!='')ert=ert+', ';
                ert=ert+$('#'+nev+' option[value='+ossz[c]+']').text();
            }
        }
        $('#td'+nev).html(ert);
    }

}
</script>
                                <input type='submit' value='@@@foglalás§§§' onclick=\"return Foglellenor();\" />
";
    }
    
    function Foglalurlap($Data)
    {
        $Mezok=array();
        $Tomb1=$this->Foglal_panel1();
        $Mezok=array_merge($Mezok,$Tomb1["Mezok"]);
        $Tomb2=$this->Foglal_panel2();
        $Mezok=array_merge($Mezok,$Tomb2["Mezok"]);
        
        $Tomb3=$this->Foglal_panel3();
        $Mezok=array_merge($Mezok,$Tomb3["Mezok"]);
        $Tomb4=$this->Foglal_panel4($Data);
        $Mezok=array_merge($Mezok,$Tomb4["Mezok"]);
        

        $FOGLAL_TIPUS=$this->Postgetv("FOGLAL_TIPUS");
        if (!(isset($FOGLAL_TIPUS)))$FOGLAL_TIPUS=1;


        return "<form method='post' action='?' id='Reszlfoglalform' name='Reszlfoglalform'>
        <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='".$Data["Link"]."'> 
        <input type='hidden' name='FOGLAL_TIPUS' id='FOGLAL_TIPUS' value='".$FOGLAL_TIPUS."'> 
                <div id='foglalas-urlap'>
                    <ul class='tabs'>
                        <li>@@@Személyes adatok§§§</li>
                        <li>@@@Vendégek száma§§§</li>
                        <li>@@@Érkezés§§§</li>
                        <li>@@@Egyéb§§§</li>
                        <li onclick=\"Osszesit_masol();\">@@@Összegzés és mentés§§§</li>
                    </ul>
                    <div class='tabs_items'>
                        
                        <div class='item'>
                            <div class='html_edited'>

                                ".$Tomb1["Html"]."
                            </div>
                        </div>
                        <div class='item'>
                            <!-- Vendégek száma -->
                            <div class='html_edited'>
                               ".$Tomb2["Html"]."
                            </div>

                        </div>
                        <div class='item'>
                            <!-- Érkezés -->
                            <div class='html_edited'>
                                  ".$Tomb3["Html"]."
                            </div>

                        </div>
                        <div class='item'>
                            <!-- Egyéb -->
                            <div class='html_edited'>
                                ".$Tomb4["Html"]."
                            </div>
                        </div>
                        <div class='item'>
                            <!-- Összegzés és mentés -->
                            <div class='html_edited'>
                               ".$this->Foglal_osszesito($Mezok)."
                            </div>
                        </div>

                    </div>
                </div>
                </form>
 <script type='text/javascript'>

 
              $(function() {

                  // get container for the wizard and initialize its exposing
                var wizard = $('#foglalas-urlap');
              
                    // enable tabs that are contained within the wizard
                  $('ul.tabs', wizard).tabs('div.tabs_items > div', function(event, index) {

                  /* now we are inside the onBeforeClick event */

                  // ensure that the 'terms' checkbox is checked.
                  
                  //var terms = $('#terms');
                  //if (index > 0 && !terms.get(0).checked)  {
                  //terms.parent().addClass('error');

                  // when false is returned, the user cannot advance to the next tab
                  //return false;
                  //}

                  // everything is ok. remove possible red highlight from the terms
                  //terms.parent().removeClass('error');
                  });
              
                    // get handle to the tabs API
                  var api = $('ul.tabs', wizard).data('tabs');

                  // 'next tab' button
                  $('a.next_btn', wizard).click(function() {
                    Osszesit_masol();
                  api.next();
                  });

                  // 'previous tab' button
                  $('a.back_btn', wizard).click(function() {
                  api.prev();
                  });
                });
            </script>
                            ";
    }
    
    function Kapcsoldalt($Data)
    {
        $Java="<script>
function Kapcsellenor22()
{
    if (document.Kapcsform2.NEV.value=='')
    {
        alert('@@@A név nem lehet üres!§§§');
        document.Kapcsform2.NEV.focus();
    }else
    if (document.Kapcsform2.EMAIL.value=='')
    {
        alert('@@@Az email nem lehet üres!§§§');
        document.Kapcsform2.EMAIL.focus();
    }else
    if (document.Kapcsform2.UZENET.value=='')
    {
        alert('@@@Az üzenet nem lehet üres!§§§');
        document.Kapcsform2.UZENET.focus();
    }else return true;
    return false;
    
}
</script>
";        
        return "$Java <form action='".$Data["Link"]."' method='post' name='Kapcsform2' id='Kapcsform2'>                        
                        <input type='text' placeholder='@@@Név§§§' name='NEV' id='NEV'>
                        <input type='email' placeholder='@@@Email§§§' name='EMAIL' id='EMAIL'>
                        <textarea placeholder='@@@Üzenet§§§' name='UZENET' id='UZENET'></textarea>       
                        <label class='qaptcha_label'>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</label>
                        <div class='qaptcha'></div>
                        <script type='text/javascript'>
                        $(document).ready(function(){

                            // More complex call
                            $('.qaptcha').QapTcha({
                                txtLock: '@@@Zárva : Az űrlap nem küldhető§§§',    
                                txtUnlock: '@@@Nyitva! Az űrlap elküldhető§§§',    
                                autoSubmit : false,
                                autoRevert : true,
                                PHPfile : '/php/qaptcha.jquery.php'
                            });
                        });
                        </script>
                        <input type='hidden' name='submit' value='submit'>

                        <input type='submit' value='@@@elküld§§§' onclick=\"return Kapcsellenor22();\" />


                    </form>";
    }
    
   function Kapcsurlap($Data)
    {
        $NEV=$this->Postgetv("NEV");
        if (!(isset($NEV)))$NEV="";

        $EMAIL=$this->Postgetv("EMAIL");
        if (!(isset($EMAIL)))$EMAIL="";

        $UZENET=$this->Postgetv("UZENET");
        if (!(isset($UZENET)))$UZENET="";

        $TELEFON=$this->Postgetv("TELEFON");
        if (!(isset($TELEFON)))$TELEFON="";

        $Java="<script>
function Kapcsellenor2()
{
    if (document.Kapcsform2.NEV.value=='')
    {
        alert('@@@A név nem lehet üres!§§§');
        document.Kapcsform2.NEV.focus();
    }else
    if (document.Kapcsform2.EMAIL.value=='')
    {
        alert('@@@Az email nem lehet üres!§§§');
        document.Kapcsform2.EMAIL.focus();
    }else
    if (document.Kapcsform2.UZENET.value=='')
    {
        alert('@@@Az üzenet nem lehet üres!§§§');
        document.Kapcsform2.UZENET.focus();
    }else 
    if (document.Kapcsform2.KOD_FORM.value=='')
    {
        alert('@@@Az ellenőrző kód nem lehet üres!§§§');
        document.Kapcsform2.KOD_FORM.focus();
    }else return true;
    return false;
    
}

         
</script>
";

        return " $Java  
                <form class='form' id='contactform' name='Kapcsform2'  action='?' method='post'>
                <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value=\"".$Data["Link"]."\" />
            <div class='half'>
                <label><input type='text' name='NEV' id='NEV' value='$NEV' placeholder='Név:'></label>
                <label><input type='text' name='EMAIL' id='EMAIL' value='$EMAIL' placeholder='E-mail cím:'></label>
                <label><input type='text' name='TELEFON' id='TELEFON' value='$TELEFON' placeholder='Telefonszám: '></label>
                 <p>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</p>
                <div class='qaptcha'></div>
                
                
                
            </div>
            <div class='half'>
                <label class='textarea'>                    
                     <textarea id='UZENET' name='UZENET' placeholder='* @@@Megjegyzés§§§'>$UZENET</textarea>
                </label>
                <input type='hidden' name='submit' value='submit'>
                <input type='submit' value='@@@elküldés§§§' onclick=\"return Kapcsellenor2();\">
            </div>

        </form> 
               <script type='text/javascript'>
        $(document).ready(function(){

            // More complex call
            $('.qaptcha').QapTcha({
                txtLock: '@@@Zárva : Az űrlap nem küldhető§§§',    
                txtUnlock: '@@@Nyitva! Az űrlap elküldhető§§§',   
                autoSubmit : false,
                autoRevert : true,
                PHPfile : '/php/qaptcha.jquery.php'
            });
        });
        </script>  
        
                   ";    
    }
      
      
   function Ajanlatker($Data)
    {
    /*    $NEV=$this->Postgetv("NEV");
        if (!(isset($NEV)))$NEV="";

        $EMAIL=$this->Postgetv("EMAIL");
        if (!(isset($EMAIL)))$EMAIL="";

        $UZENET=$this->Postgetv("UZENET");
        if (!(isset($UZENET)))$UZENET="";

        $TELEFON=$this->Postgetv("TELEFON");
        if (!(isset($TELEFON)))$TELEFON="";
        */

        $Java="<script>
function Kapcsellenor2()
{
    if (document.Kapcsform2.NEV.value=='')
    {
        alert('@@@A név nem lehet üres!§§§');
        document.Kapcsform2.NEV.focus();
    }else
    if (document.Kapcsform2.EMAIL.value=='')
    {
        alert('@@@Az email nem lehet üres!§§§');
        document.Kapcsform2.EMAIL.focus();
    }else
    if (document.Kapcsform2.UZENET.value=='')
    {
        alert('@@@Az üzenet nem lehet üres!§§§');
        document.Kapcsform2.UZENET.focus();
    }else return true;
    return false;
    
}

         
</script>
";

        return " $Java  
                <form class='form' id='Kapcsform2' name='Kapcsform2'  action='?' method='post'>
                <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value=\"".$Data["Link"]."\" />
            <div >
                <label>* Kapcsolattartó neve: <input type='text' name='NEV' id='NEV' value=''></label>
                <label>* Kapcsolattartó email címe: <input type='text' name='EMAIL' id='EMAIL' value=''></label>
                <label>* Kapcsolattartó telefonszáma: <input type='text' name='TELEFON' id='TELEFON' value=''></label>

                <label>A társasház címe: <input type='text' name='CIM' id='CIM' value=''></label>
                <label>Lakások száma: <input type='text' name='LAKAS_SZAM' id='LAKAS_SZAM' value=''></label>
                <label>Garázsok száma: <input type='text' name='GARAZS_SZAM' id='GARAZS_SZAM' value=''></label>
                <label>Üzletek, irodák, egyéb helyiségek száma: <input type='text' name='EGYEB_SZAM' id='EGYEB_SZAM' value=''></label>

                
                
                

                <label>Összes alapterület: <input type='text' name='ALAPT' id='ALAPT' value=''></label>
                <label>Fűtési mód az épületben: <input type='text' name='FUTES' id='FUTES' value=''></label>
                <label>Építés éve: <input type='text' name='EPITES' id='EPITES' value=''></label>

                <p>Rendelkezik a társasház Szervezeti Működési Szabályzattal?  </p>
                 <div class='radio_buttons'>
                   <input type='radio' name='RENDELK' id='RENDELK' value='igen'> igen
                   <input type='radio' name='RENDELK' id='RENDELK' value='nem' checked> nem
                 </div>  
               <br><br>


                <label class='textarea'>                    
                     <textarea id='UZENET' name='UZENET' placeholder='Egyéb megjegyzés'>$UZENET</textarea>
                </label>
                 <p>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</p>
                <div class='qaptcha'></div>
                
                <input type='hidden' name='submit' value='submit'>
                <input type='submit' value='@@@elküldés§§§' onclick=\"return Kapcsellenor2();\">
            </div>

        </form> 
               <script type='text/javascript'>
        $(document).ready(function(){

            // More complex call
            $('.qaptcha').QapTcha({
                txtLock: '@@@Zárva : Az űrlap nem küldhető§§§',    
                txtUnlock: '@@@Nyitva! Az űrlap elküldhető§§§',   
                autoSubmit : false,
                autoRevert : true,
                PHPfile : '/php/qaptcha.jquery.php'
            });
        });
        </script>  
        
                   ";    
    }      


    function Urlapba_dat($MEZO)
    {
        if ("$MEZO"=="erkezes")
        {
            $NEV=$this->Postgetv($MEZO);
            if (!(isset($NEV)))
            {
                $MEZO="erkezes_f";
                $NEV=$this->Postgetv($MEZO);
            }

            if (!(isset($NEV)))
            {
                $MEZO="erkezes_f1";
                $NEV=$this->Postgetv($MEZO);
            }

            if (!(isset($NEV)))
            {
                $MEZO="erkezes_f2";
                $NEV=$this->Postgetv($MEZO);
            }
            if (!(isset($NEV)))$NEV="";


        }else
        if ("$MEZO"=="tavozas")
        {
            $NEV=$this->Postgetv($MEZO);
            if (!(isset($NEV)))
            {
                $MEZO="tavozas_f";
                $NEV=$this->Postgetv($MEZO);
            }

            if (!(isset($NEV)))
            {
                $MEZO="tavozas_f1";
                $NEV=$this->Postgetv($MEZO);
            }

            if (!(isset($NEV)))
            {
                $MEZO="tavozas_f2";
                $NEV=$this->Postgetv($MEZO);
            }
            if (!(isset($NEV)))$NEV="";

        }else
        {
            $NEV=$this->Postgetv($MEZO);
            if (!(isset($NEV)))$NEV="";
        }
        
        
        return $NEV;
    }
    
    function Foglalurlap_regi($DATA)
    {
        
        $Gyerek=$this->Urlapba_dat("gyerekek");
        $felnott=$this->Urlapba_dat("felnott");
        
        $Gy_tag="<option value=''>- -</option>";
        for ($c=1;$c<7;$c++)
        {
            $Jel="";
            if ($Gyerek=="$c")$Jel="selected";
            $Gy_tag.="<option value='$c' $Jel>$c</option>
";
        }

        $Fel_tag="<option value=''>- -</option>";
        for ($c=1;$c<7;$c++)
        {
            $Jel="";
            if ($felnott=="$c")$Jel="selected";
            $Fel_tag.="<option value='$c' $Jel>$c</option>
";
        }


        return "    <script>
function Foglellenor()
{
    if (document.contactform.name.value=='')
    {
        alert('@@@A név nem lehet üres!§§§');
        document.contactform.name.focus();
    }else
    if (document.contactform.email.value=='')
    {
        alert('@@@Az email nem lehet üres!§§§');
        document.contactform.email.focus();
    }else
    if (document.contactform.erkezes.value=='')
    {
        alert('@@@Az érkezés nem lehet üres!§§§');
        document.contactform.erkezes.focus();
    }else 
    if (document.contactform.tavozas.value=='')
    {
        alert('@@@A távozás nem lehet üres!§§§');
        document.contactform.tavozas.focus();
    }else return true;
    return false;
    
}

         
</script>

    <form class='form' id='contactform' name='contactform' action='?' method='post'>
        <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='".$DATA["Link"]."' >
                        <div class='half'>


                            <label>@@@Név:§§§ <input type='text' name='name' id='name' value='".$this->Urlapba_dat("name")."'></label>
                            <label>@@@E-mail cím:§§§ <input type='text' name='email' id='email' value='".$this->Urlapba_dat("email")."'></label>
                            <label>@@@Telefonszám:§§§ <input type='text' name='tel' id='tel' value='".$this->Urlapba_dat("tel")."'></label>
                            <label class='textarea'>
                                <textarea id='message' placeholder='@@@Megjegyzés§§§' name='message'>".$this->Urlapba_dat("message")."</textarea>
                            </label>
                        </div>
                        <div class='half'>
                            <label>@@@Érkezés napja:§§§ <input type='text' class='datepicker'  name='erkezes' id='erkezes' value='".$this->Urlapba_dat("erkezes")."'></label>
                            <label>@@@Távozás napja:§§§ <input type='text' class='datepicker'  name='tavozas' id='tavozas' value='".$this->Urlapba_dat("tavozas")."'></label>

                            <div class='half' style='padding-left: 0;'>
                                <label>@@@Felnőtt:§§§ <select name='felnott' id='felnott'>$Fel_tag</select></label>
                            </div>
                            <div class='half'>
                                <label>@@@Gyermek:§§§ <select name='gyerekek' id='gyerekek'>$Gy_tag</select></label>
                            </div>
                   
                            <p>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</p>
                            <div class='qaptcha'></div>
                            <input type='hidden' name='submit' value='submit'>
                            <input type='submit' value='@@@elküldés§§§' onclick=\"return Foglellenor();\">
                        </div>

                    </form> 

                    <script type='text/javascript'>
                        $(document).ready(function(){

                            // More complex call
                            $('.qaptcha').QapTcha({
                                txtLock: '@@@Zárva : Az űrlap nem küldhető§§§',    
                                txtUnlock: '@@@Nyitva! Az űrlap elküldhető§§§',    
                                autoSubmit : false,
                                autoRevert : true,
                                PHPfile : '/php/qaptcha.jquery.php'
                            });
                        });
                        </script>
                        
";
    }
    
}
?>