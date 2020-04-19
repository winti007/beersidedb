<?php


class CFelhasznalo_sablon extends CKapcsform_sablon  
{
        
}

class CSimaFelhasznalo_sablon extends CFelhasznalo_sablon  
{
    
    function Adatmutat2($Data)
    {
       

        
        $Jobb=" <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                <td height='24' class='bg_kosar_top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6'></td>
                                    <td height='24'><span class='kosar_head_2'>Szállítási adatok</span></td>
                                    <td width='28' align='center'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              <tr>
                                <td height='14'></td>
                                </tr>
                              <tr>
                                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6' ></td>
                                    <td width='120' height='20' ><span class='kosar_text'>Név: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZALL_NEV_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20'><span class='kosar_text'>Irányítószám: </span></td>
                                    <td><span class='kosar_text'><strong>".$Data["SZALL_IRSZAM_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>Város: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZALL_VAROS_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>Utca, házszám: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZALL_CIM_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              <tr>
                                <td height='12'></td>
                                </tr>
                              </table>";
                                      
        $Bal=" <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                <td height='24' class='bg_kosar_top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6'></td>
                                    <td height='24'><span class='kosar_head_2'>Számlázási adatok</span></td>
                                    <td width='28' align='center'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              <tr>
                                <td height='14'></td>
                                </tr>
                              <tr>
                                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6' ></td>
                                    <td width='120' height='20' ><span class='kosar_text'>Név: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZAML_NEV_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20'><span class='kosar_text'>Irányítószám: </span></td>
                                    <td><span class='kosar_text'><strong>".$Data["SZAML_IRSZAM_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>Város: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZAML_VAROS_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>Utca, házszám: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["SZAML_CIM_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>E-mail: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["EMAIL_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='20' ><span class='kosar_text'>@@@Phone number§§§: </span></td>
                                    <td ><span class='kosar_text'><strong>".$Data["TELSZAM_S"]."</strong></span></td>
                                    </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              <tr>
                                <td height='12'></td>
                                </tr>
                              </table>";

        $Vissza["Bal"]=$Bal;
        $Vissza["Jobb"]=$Jobb;
        return $Vissza;
        
    }                
    
    function Adatmutat(&$Form,$Data)
    {
        $Form->Szabad2(" ","Számlázási adatok ");
        
        $Form->Szabad2("*Név:",$Data["SZAML_NEV_S"]);
        $Form->Szabad2("*Irányítószám:",$Data["SZAML_IRSZAM_S"]);
        $Form->Szabad2("*Város:",$Data["SZAML_VAROS_S"]);
        $Form->Szabad2("*Utca, házszám:",$Data["SZAML_CIM_S"]);

        $Form->Szabad2(" ","Szállítási adatok");

                
       
        $Form->Szabad2("Név:",$Data["SZALL_NEV_S"]);
        $Form->Szabad2("Irányítószám:",$Data["SZALL_IRSZAM_S"]);
        $Form->Szabad2("Város:",$Data["SZALL_VAROS_S"]);
        $Form->Szabad2("Utca, házszám:",$Data["SZALL_CIM_S"]);
        
        $Form->Szabad2("@@@Telefonszám§§§:",$Data["TELSZAM_S"]);
        $Form->Szabad2("*@@@E-mail§§§:",$Data["EMAIL_S"]);        
        
    }                
    
    function Regisztral($Data)
    {
        $this->ScriptTarol("
function Ellenor()
{
    if (Adatellenor())
    {
        if (document.Adatform.JELSZO_S.value=='')
        {
            alert('@@@A jelszó nem lehet üres!§§§');
            document.Adatform.JELSZO_S.focus();
        }else 
        if (document.Adatform.JELSZO_S.value!=document.Adatform.JELSZO_S2.value)
        {
            alert('@@@A jelszavak nem egyeznek!§§§');
            document.Adatform.JELSZO_S.focus();
        }else return true;
        
        return false;
                 
    }else return false;
}
");
        $Form=new CForm_rendel("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $this->Adatbekero($Form,$Data);
        
        $Form->Password("*@@@Jelszó§§§:","JELSZO_S","","");        
        $Form->Password("*@@@Jelszó mégegyszer§§§:","JELSZO_S2","","");
        
        $Form->Gomb("@@@Regisztráció§§§","return Ellenor()","submit");
        
        return $Form->OsszeRak();
        
    }
    
    function Adatbekero(&$Form,$Data)
    {
        $Formnev=$Form->Formnevad();


        
        $Form->Fejlec("@@@Számlázási adatok§§§ ");
        
        $Form->TextBox("*@@@Vezetéknév§§§:","SZAML_NEV_S",$Data["SZAML_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZAML_KERNEV_S",$Data["SZAML_KERNEV_S"],"");

        $Form->TextBox("@@@Cégnév§§§:","SZAML_CEGNEV_S",$Data["SZAML_CEGNEV_S"],"");
        
        $Form->TextBox("*@@@Irányítószám§§§:","SZAML_IRSZAM_S",$Data["SZAML_IRSZAM_S"],"");
        $Form->TextBox("*@@@Város§§§:","SZAML_VAROS_S",$Data["SZAML_VAROS_S"],"");
        $Form->TextBox("*@@@Utca, házszám§§§:","SZAML_CIM_S",$Data["SZAML_CIM_S"],"");

        $Form->TextBox("@@@Adószám§§§:","ADOSZAM_S",$Data["ADOSZAM_S"],"");

$Form->Oszlopvalt();
        $Form->Fejlec("@@@Szállítási adatok§§§ <input type=button value='@@@Számlázási cím másolása§§§' class='btn btn-default' onclick=\"Cimmasol();\">");
        

$this->ScriptTarol("
function Cimmasol()
{
        document.getElementById('SZALL_NEV_S').value=document.getElementById('SZAML_NEV_S').value;
        document.getElementById('SZALL_KERNEV_S').value=document.getElementById('SZAML_KERNEV_S').value;
        document.getElementById('SZALL_IRSZAM_S').value=document.getElementById('SZAML_IRSZAM_S').value;
        document.getElementById('SZALL_VAROS_S').value=document.getElementById('SZAML_VAROS_S').value;
        document.getElementById('SZALL_CIM_S').value=document.getElementById('SZAML_CIM_S').value;
        document.getElementById('SZALL_CEGNEV_S').value=document.getElementById('SZAML_CEGNEV_S').value;
}
                ");
                
       
        $Form->TextBox("@@@Vezetéknév§§§:","SZALL_NEV_S",$Data["SZALL_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZALL_KERNEV_S",$Data["SZALL_KERNEV_S"],"");
        $Form->TextBox("@@@Cégnév§§§:","SZALL_CEGNEV_S",$Data["SZALL_CEGNEV_S"],"");
        
        $Form->TextBox("@@@Irányítószám§§§:","SZALL_IRSZAM_S",$Data["SZALL_IRSZAM_S"],"");
        $Form->TextBox("@@@Város§§§:","SZALL_VAROS_S",$Data["SZALL_VAROS_S"],"");
        $Form->TextBox("@@@Utca, házszám§§§:","SZALL_CIM_S",$Data["SZALL_CIM_S"],"");
        
        $Form->TextBox("@@@Telefonszám§§§:","TELSZAM_S",$Data["TELSZAM_S"],"");
        if (isset($Data["Mailnem"]))
        {
            $Emailj="";
            $Form->Szabad2("*@@@E-mail§§§:",$Data["EMAIL_S"]);
        }else
        {
            $Form->TextBox("*@@@E-mail§§§:","EMAIL_S",$Data["EMAIL_S"],"");
            $Emailj="  if (document.$Formnev.EMAIL_S.value=='')
        {
            alert('@@@Az email nem lehet üres!§§§');
            document.$Formnev.EMAIL_S.focus();
        }else  ";
        }        
        if ((isset($Data["Adminmod"]))&&($Data["Adminmod"]=="1"))
        {
            $Form->TextBox("Felhasználónév:","LOGIN_S",$Data["LOGIN_S"],"");
            
            $this->ScriptTarol("
function Adatellenor()
{

     if (document.$Formnev.SZAML_KERNEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_KERNEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_NEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_NEV_S.focus();
        }else 
          $Emailj        
        return true;
        
        return false;         
}
");            
        }else
        {
            $this->ScriptTarol("
function Adatellenor()
{
    
     if (document.$Formnev.SZAML_KERNEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_KERNEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_NEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_NEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_IRSZAM_S.value=='')
        {
            alert('@@@A számlázási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZAML_IRSZAM_S.focus();
        }else 
        if (document.$Formnev.SZAML_VAROS_S.value=='')
        {
            alert('@@@A számlázási város nem lehet üres!§§§');
            document.$Formnev.SZAML_VAROS_S.focus();
        }else 
        if (document.$Formnev.SZAML_CIM_S.value=='')
        {
            alert('@@@A számlázási utca, házszám nem lehet üres!§§§');
            document.$Formnev.SZAML_CIM_S.focus();
        }else
        if (document.$Formnev.SZALL_NEV_S.value=='')
        {
            alert('@@@A szállítási név nem lehet üres!§§§');
            document.$Formnev.SZALL_NEV_S.focus();
        }else
        if (document.$Formnev.SZALL_IRSZAM_S.value=='')
        {
            alert('@@@A szállítási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZALL_IRSZAM_S.focus();
        }else
        if (document.$Formnev.SZALL_VAROS_S.value=='')
        {
            alert('@@@A szállítási város nem lehet üres!§§§');
            document.$Formnev.SZALL_VAROS_S.focus();
        }else
        if (document.$Formnev.SZALL_CIM_S.value=='')
        {
            alert('@@@A szállítási utca,házszám  nem lehet üres!§§§');
            document.$Formnev.SZALL_CIM_S.focus();
        }else
      $Emailj        
        return true;
        
        return false;         
}
");
    }
    
    }    


    function Urlapki($Data)
    {
        $this->ScriptTarol("
function Ellenor()
{
    if (Adatellenor())
    {
        if (document.Adatform.JELSZO_S.value!=document.Adatform.JELSZO_S2.value)
        {
            alert('@@@A jelszavak nem egyeznek!§§§');
            document.Adatform.JELSZO_S.focus();
        }else return true;
        
        return false;
                 
    }else return false;
}
");
        $Form=new CForm_rendel("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $Data["Adminmod"]=1;
        
        $this->Adatbekero($Form,$Data);
        
        $Form->Szabad2("@@@Felhasználónév§§§:",$Data["LOGIN_S"]);
        
        

        
        $Form->CheckBox("Hírlevelet kér:","HIRLEVEL_I",$Data["HIRLEVEL_I"],"");
        $Form->CheckBox("Aktív:","AKTIV_I",$Data["AKTIV_I"],"");
        $Form->Password("Új jelszó:","JELSZO_S","","");        
        $Form->Password("Új jelszó mégegyegyszer:","JELSZO_S2","","");
        
        $Form->Szabad2(" ",$Form->Gomb("Tárol","return Ellenor()","submit")." ".$Form->Gomb("Mégsem","location.href='".$Data["Visszalink"]."'","button"));
        
        
        return $Form->OsszeRak();
        
    }

    function Profil($Data)
    {
        $this->ScriptTarol("
function Ellenor()
{
    if (Adatellenor())
    {
        if (document.Adatform.JELSZO_S.value!=document.Adatform.JELSZO_S2.value)
        {
            alert('@@@A jelszavak nem egyeznek!§§§');
            document.Adatform.JELSZO_S.focus();
        }else return true;
        
        return false;
                 
    }else return false;
}
");
        $Form=new CForm_rendel("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $Data["Mailnem"]=1;
        $this->Adatbekero($Form,$Data);
        
        
        
        $Form->Szabad2("@@@Felhasználónév§§§:",$Data["LOGIN_S"]);
        $Form->CheckBox("Hírlevelet kér:","HIRLEVEL_I",$Data["HIRLEVEL_I"],"");
        
        $Form->Password("Új jelszó:","JELSZO_S","","");        
        $Form->Password("Új jelszó mégegyegyszer:","JELSZO_S2","","");
        
        $Form->Szabad2(" ",$Form->Gomb("Tárol","return Ellenor()","submit")." ".$Form->Gomb("Mégsem","location.href='".$Data["Visszalink"]."'","button"));
        
        
        return $Form->OsszeRak();
        
    }

} 

 
?>