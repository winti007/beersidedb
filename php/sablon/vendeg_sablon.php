<?php



class CVendegKonyvCsoport_sablon extends CCsoport_sablon  
{


    function Lista($Data)
    {
        $Tartalom="";    
        $Vannyil=false;
        
        $DB=0;
        $Maxtddb=0;


        $VList=$Data["Lista"]["Eredm"];
        
        $Tartalom.=$this->Vendeg_adminsor($VList);
        
        if (isset($Data["Lista"]["Pager"]))
        {
            $Tartalom.=$Data["Lista"]["Pager"];
        }
        


        if (isset($Data["Menuutan"]))$Tartalom.=$Data["Menuutan"];
        if (isset($Data["Vissza"]))
        {
            if ($Data["Vissza"]!="")$Tartalom.="<br>".$this->Gombcsinal("Vissza","location.href='".$Data["Vissza"]."';");
        }
        
        
        return $Tartalom;
        
    }



   function Uzenbeker($Data)
    {
      /*  $NEV=$this->Postgetv("NEV");
        if (!(isset($NEV)))$NEV="";

        $EMAIL=$this->Postgetv("EMAIL");
        if (!(isset($EMAIL)))$EMAIL="";

        $UZENET=$this->Postgetv("UZENET");
        if (!(isset($UZENET)))$UZENET="";
*/
$NEV="";
$EMAIL="";
$UZENET="";

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
    if ($('#g-recaptcha-response').val()=='')
    {
        alert('@@@Bizonyítsd be hogy nem vagy robot!§§§');
    }else return true;


    return false;
    
}

         
</script>
";

        return " $Java  
             <div class='add_to_guestbook'>
                    <form class='form' action='?' method='post' id='Kapcsform2' name='Kapcsform2' >
                     <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value=\"".$Data["Link"]."\" />
                        <div class='half'>
                            <label><input type='text' name='NEV' id='NEV' value='$NEV' placeholder='@@@Név§§§'></label>
                            <label><input type='text' name='EMAIL' id='EMAIL'  value='' placeholder='@@@E-mail cím§§§'></label>
                            <p>@@@Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.§§§</p>
                            <div class='qaptcha'></div>
                        </div>
                        <div class='half'>
                            <label class='textarea'>
                                <textarea id='UZENET' name='UZENET' placeholder='@@@Üzenet§§§'>$UZENET</textarea>
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
                            
                </div>
                
                

        
                   ";    
    }
    
    function Vendeg_adminsor($Data)
    {
        $Vissza="";
        
        $Db=count($Data);
        
        
        if ($Db>0)
        {
            $Vissza.="
     <div class='guestbook'>
        
        
              
            ";
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Vendegmutatsor_admin($Data[$c]);
            }
            $Vissza.="
            </div>";
        }

        

        return $Vissza;        
    }

    function Vendegmutat($Data)
    {
        $Vissza="";
        
        $Db=count($Data);
        
        
        if ($Db>0)
        {
            $Vissza.="
     <div class='guestbook'>
        
        
              
            ";
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Vendegmutatsor($Data[$c]);
            }
            $Vissza.="
            </div>";
        }

        

        return $Vissza;
    } 

    function Vendegmutatsor_admin($Item)
    {

        return "                  <div class='item'>
                        <div class='gb_title'>
                            <h3><a href='mailto:".$Item["EMAIL_S"]."'>".$Item["NEV_S"]."</a></h3>
                            <span class='date'>".mb_substr($Item["Keszult"],0,10)."</span>
                        </div>
                        <div class='gb_content'>
                            <div class='html_edited'>
                                <p>".$Item["SZOVEG_S"]."</p>
                                <p><a href='".$Item["Torollink"]."' onclick=\"return confirm('Biztos hogy törölni akarja?');\" class=adminmenu><img src='/templ_admin/ikonok/smbdel.gif' border='0' alt='Hozzászólás törlése'>Törlés</a></p>
                            </div>
                        </div>
                    </div>
                    
                             
       ";        
        
    }

    function Vendegmutatsor($Item)
    {


        return "                  <div class='item'>
                        <div class='gb_title'>
                            <h3><a href='javascript:void(0);'>".$Item["NEV_S"]."</a></h3>
                            <span class='date'>".mb_substr($Item["Keszult"],0,10)."</span>
                        </div>
                        <div class='gb_content'>
                            <div class='html_edited'>
                                <p>".$Item["SZOVEG_S"]."</p>
                            </div>
                        </div>
                    </div>
                    
                             
       ";        
    }                    
    


}



?>