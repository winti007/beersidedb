<?php


class CGaleriacsoport extends CCsoport
{


        function Ujgalkep_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CGalkep","GALKEP");
                return $this->Futtat($Obj)->Tarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),$this->Postgetv("NEV_S"),$this->Postgetv("YOUTUBE_S"));

        }
    
    
        function Tobbnyelvu()
        {
            return true;
        }
            
        function Listazhat()
        {
            return true;
        }

        function Listakepmeret()
        {
              
            return "p440-220";
        }
        function Kelllistakep()
        {
            return true;
        }
        
                  
        function Mutat_pb_fut()
        {
                $NEV=$this->NevAd();
                $SZOVEG=$this->SzovegCserel();
                
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                $AZON=$this->AzonAd();
                $Vlink=$this->Mutatvisszalink();
                if ($Vlink!="")$Vissza["Vissza"]=$Vlink;
                
                $Parat["Menuk"]=$Ered["Eredm"];
                $Vissza["Almenuk"]=$this->Sablonbe("Menumunkateruletre",$Parat);



                $Vissza["Tartalom"]=$SZOVEG;
                if ($this->Tobbnyelvu())
                {
                    $BEVEZETO=$this->TablaAdatKi("BEVEZETO_".$this->Nyelvadpub()."_S");
                }
                else $BEVEZETO=$this->TablaAdatKi("BEVEZETO_S");
                
                $Vissza["Bevezeto"]=$BEVEZETO;
                $Vissza["Cim"]=$NEV;
                $Vissza["Nemkellhtml"]=1;
                
                
                //$Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldal",$Vissza);

        }
                
        function Nyitolapon()
        {
            $Csoport=array();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and NYITON_I='1' and AKTIV_I='1' order by AZON_I asc");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Csoport[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(false);
                }
            }

            $Kep=array();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join MULTIMEDIA on VAZ.VZ_TABLA_S='MULTIMEDIA' and VZ_TABLA_AZON_I=MULTIMEDIA.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_OBJEKTUM_S='CGalkep' order by AZON_I desc limit 0,8 ");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Kep[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlist_nyito();
                }
            }
            $Vissza["Csoport"]=$Csoport;
            $Vissza["Kep"]=$Kep;
            return $Vissza;
            
            
        }
/*
        function Urlapplusz(&$Form)
        {
            $Form->Checkbox("Nyitólapon megjelenik:","NYITON_I",$this->TablaAdatKi("NYITON_I"),"");
            $Form->Area("Nyitólapi szöveg magyar:","NYITOSZOVEG_HU_S",$this->TablaAdatKi("NYITOSZOVEG_HU_S"),"");
            $Form->Area("Nyitólapi szöveg német:","NYITOSZOVEG_DE_S",$this->TablaAdatKi("NYITOSZOVEG_DE_S"),"");
                 
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("NYITON_I",$this->Postgetv("NYITON_I",1));            
            $this->TablaAdatBe("NYITOSZOVEG_HU_S",$this->Postgetv("NYITOSZOVEG_HU_S"));            
            $this->TablaAdatBe("NYITOSZOVEG_DE_S",$this->Postgetv("NYITOSZOVEG_DE_S"));            
        }

*/
        function Keprejttol_pb_fut()
        {
                $FAJL=$_FILES['uploadfile']['tmp_name'];
                $FAJLNEV=$_FILES['uploadfile']['name'];

                $Obj=$this->UjObjektumLetrehoz("CGalkep","GALKEP");
                

                $this->Futtat($Obj)->Tarol($FAJL,$FAJLNEV,"");

                $FoObj=$this->ObjektumLetrehoz(Focsop_azon,0);
                $FoObj->Futas_vege(1);
                echo OLDALCIM.$Obj->Listakep();
                exit;
            
        }
                
      
        
       function Adatlistkozep_publ($Almenukell=false)
        {
            $Vissza=parent::Adatlistkozep_publ($Almenukell);
            $Tomb=$this->Futtatgy("GALKEP","VZ_SORREND_I asc",null,"1")->AzonAd();
            $Vankep=false;
            if ($Tomb["Ossz"]>0)$Vankep=true;
            
            $Vissza["Vankep"]=$Vankep;

            return $Vissza;
        }
        
      
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlGaleriacsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
        }       
         
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]="Új csoport";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        

                
        function Lista_fut()
        {
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('A kép nem lehet üres!');
    }else return true;
    return false;
}");
            $Data["Lista"]=$this->Futtatgy("CSOPORT","VZ_SORREND_I",null,null,null,1)->Adatlist_adm();
            $Data=array_merge($Data,$this->Adat_adm());
            
            $Form=new CForm("Ujallomany","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Ujgalkep"));
            $Form->Textbox("Kép neve:","NEV_S","","");
//            $Form->Textbox("Videó esetén youtube azonosító:","YOUTUBE_S","","");
            $Form->Allomanybe("Kép forrása:","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Vissza["Tartalom"]=$Form->OsszeRak();
            
            $Rejttolt=$this->EsemenyHozzad("Keprejttol_pb_fut");
            $Tomegt="<script type='text/javascript' src='/templ/js/swfupload/swfupload.js'></script>
<script type='text/javascript' src='/templ/js/jquery.swfupload.js'></script>        
                  <script type=\"text/javascript\">
jQuery(function(){
        jQuery('#swfupload-control').swfupload({
                upload_url: \"".$Rejttolt."\",
                file_post_name: 'uploadfile',
                file_size_limit : \"10024\",
                file_types : \"*.jpg;*.jpeg;\",
                file_types_description : \"Image files\",
                file_upload_limit : 100,
                flash_url : \"/templ/js/swfupload/swfupload.swf\",
                button_image_url : '/templ/js/swfupload/wdp_buttons_upload_114x29.png',
                button_width : 114,
                button_height : 29,
                button_placeholder : jQuery('#button')[0],
                debug: false
        })
                .bind('fileQueued', function(event, file){
                        var listitem='<li id=\"'+file.id+'\" >'+
                                'File: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class=\"progressvalue\" ></span>'+
                                '<div class=\"progressbar\" ><div class=\"progress\" ></div></div>'+
                                '<p class=\"status\" >Feltöltve</p>'+
                                '<span class=\"cancel\" >&nbsp;</span>'+
                                '</li>';
                        jQuery('#log').append(listitem);
                        jQuery('li#'+file.id+' .cancel').bind('click', function(){
                                var swfu = jQuery.swfupload.getInstance('#swfupload-control');
                                swfu.cancelUpload(file.id);
                                jQuery('li#'+file.id).slideUp('fast');
                        });
                        // start the upload since it's queued
                        jQuery(this).swfupload('startUpload');
                })
                .bind('fileQueueError', function(event, file, errorCode, message){
                     if (file==null)
                     {
                     alert('Hiba !');
                     }else
                        alert('Túl nagy fájl: '+file.name+' ');
                })
                .bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
                        jQuery('#queuestatus').text('Kijelölt fájlok: '+numFilesSelected+' / Feltöltött fájlok: '+numFilesQueued);
                })
                .bind('uploadStart', function(event, file){
                        jQuery('#log li#'+file.id).find('p.status').text('Feltöltés...');
                        jQuery('#log li#'+file.id).find('span.progressvalue').text('0%');
                        jQuery('#log li#'+file.id).find('span.cancel').hide();
                })
                .bind('uploadProgress', function(event, file, bytesLoaded){
                    
                        //Show Progress
                        var percentage=Math.round((bytesLoaded/file.size)*100);
                        jQuery('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
                        jQuery('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
                })
                .bind('uploadSuccess', function(event, file, serverData){
                        var item=jQuery('#log li#'+file.id);
                        item.find('div.progress').css('width', '100%');
                        item.find('span.progressvalue').text('100%');
                        var pathtofile='<a href=\"'+serverData+'\" target=\"_blank\" class=\"publikuslink\">megtekint &raquo;</a>';
                        item.addClass('success').find('p.status').html('Kész!!! | '+pathtofile);
                })
                .bind('uploadComplete', function(event, file){
                        // upload has completed, try the next one in the queue
                        jQuery(this).swfupload('startUpload');
                })

});
</script><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td>Tömeges feltöltés: <span id=\"swfupload-control\">
        <input type=\"button\" id=\"button\" />
</span>
</td>
                  </tr>
                  <tr>
                    <td colspan='3' align='center'><p id=\"queuestatus\" ></p>
        <ol id=\"log\"></ol></td>
                  </tr>
                </table>";
                
            $Vissza["Tartalom"].=$Tomegt;
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);
            


            $Keplist["Lista"]=$this->Futtatgy("GALKEP","VZ_SORREND_I asc",null,null,null,1)->Adatlist_adm();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Keplist);
            

                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }          
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        }
        
        function Szovegcserel()
        {
            $Data=$this->Futtatgy("GALKEP","VZ_SORREND_I asc")->Adatlistkozep_publ();
            $Param["Kepek"]=$Data;
            $Param["Adat"]=$this->Adatlist0();
            
            $Vissza=$this->Sablonbe("Kepekmutat",$Param);

            return $Vissza;
        }         
        
}



class CAlGaleriacsoport extends CGaleriacsoport
{


        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TEKINT"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }
}



class CMultimedia extends CVaz_bovit
{
        var $Tabla_nev="MULTIMEDIA";

        public function Tablasql()
        {
            $SQL="

CREATE TABLE IF NOT EXISTS `MULTIMEDIA` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `NEV_S` varchar(155) DEFAULT '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE." 

";
            return $SQL;    
        } 
        
        function Fulleleres()
        {
            $Vissza["Eleres"]=$this->Eleres();
            $Vissza["Eredeti"]=$this->EredetiEleres();
            return $Vissza;
        }
        
        function Eleres()
        {
            $Vissza="";
            $Eredm=$this->Futtat($this->Gyerekparam("LISTA"))->Eleres();
            
            if ($Eredm["Ossz"]>0)$Vissza=$Eredm["Eredm"][0];
            return $Vissza; 
        }

        function EredetiEleres()
        {
            $Vissza="";
            $Eredm=$this->Futtat($this->Gyerekparam("EREDETIKEP"))->Eleres();
            
            if ($Eredm["Ossz"]>0)$Vissza=$Eredm["Eredm"][0];
            return $Vissza; 
        }

        function NevAd()
        {
                return $this->TablaAdatKi("NEV_S");
        }

        function Fajltarol($Allomany,$Allomany_nev,$Limit,$Meretez=null)
        {
            $Jo=true;
            $Eredm=$this->Futtat($this->Gyerekparam("LISTA"))->Fajltarol($Allomany,$Allomany_nev,$Limit,$Meretez);
            if ($Eredm["Ossz"]==0)
            {
                $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","LISTA");
                $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,$Limit,$Meretez)));
            }

            $Eredm=$this->Futtat($this->Gyerekparam("EREDETIKEP"))->Fajltarol($Allomany,$Allomany_nev,$Limit);
            if ($Eredm["Ossz"]==0)
            {
                $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","EREDETIKEP");
                $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,$Limit)));
            }
            
            if ($Jo)     $this->Szinkronizal();
            
        }
 

}



      
class CGalkep extends CMultimedia
{
    
        
        function Tarol($Allomany,$Allomany_nev,$NEV)
        {
            $Jo=true;
            
            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","LISTA");
            $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET,"p440-220")));
//            $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET,"p414-311")));

//            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","NYITOKEP");
  //          $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET,"k531-362")));
            
           
            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","EREDETIKEP");
            $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET)));
            

            $this->TablaAdatBe("NEV_S",$NEV);
//            $this->TablaAdatBe("YOUTUBE_S",$YOUTUBE_S);

            if ($Jo)$this->Szinkronizal();
                else $this->TablaTarol();
            return $this->VisszaLep();
        }
        
        function Adatlist_nyito()
        {
            $Vissza["Lista"]=$this->Eleres_allom("LISTA");
            $Vissza["Eredeti"]=$this->Eleres_allom("EREDETIKEP");
            $Vissza["Nev"]=$this->NevAd();
//            $Vissza["LINK_S"]=$this->TablaAdatKi("LINK_S");
            
            $Link=$this->Futtat($this->SzuloObjektum())->EsemenyHozzad("");
//            $Vissza["Youtube"]=$this->TablaAdatKi("YOUTUBE_S");
            $Vissza["Link"]=$this->NevAd();
            
              
            return $Vissza;
        }
        
        function Adatlistkozep_publ()
        {
   
            
            $Vissza["Lista"]=$this->Eleres_allom("LISTA");
            $Vissza["Eredeti"]=$this->Eleres_allom("EREDETIKEP");
            $Vissza["Nev"]=$this->NevAd();
//            $Vissza["LINK_S"]=$this->TablaAdatKi("LINK_S");
//            $Vissza["Youtube"]=$this->TablaAdatKi("YOUTUBE_S");
              
            return $Vissza;
        }
        
        function Nyitokep()
        {
            $Vissza=$this->Eleres_allom("NYITOKEP");
              
            return $Vissza;
        }
          
        function Eredetikep()
        {
            $Vissza=$this->Eleres_allom("EREDETIKEP");
              
            return $Vissza;
        }
                    
          
        function Listakep()
        {
            $Vissza=$this->Eleres_allom("LISTA");
              
            return $Vissza;
        }
                          
        
         function Adatlist_adm_tag()
        {
            $Vissza["TOROL"]=1;
            
            $LISTA=$this->Eleres_allom("LISTA");

            
            $Vissza["EGYEB"][0]["Nev"]="<img src='".$LISTA."' width='60'>";
            $Vissza["EGYEB"][0]["Link"]="#";
            
            
            return $Vissza;    
        }         

}


    
 class CGalkepdok extends CGalkep
{
    
        
        function Tarol($Allomany,$Allomany_nev,$NEV)
        {
            $Jo=true;
            
            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","LISTA");
            $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET,"k600-560")));

//            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","NYITOKEP");
  //          $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET,"k531-362")));
            
           
            $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","EREDETIKEP");
            $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET)));
            

            $this->TablaAdatBe("NEV_S",$NEV);

            if ($Jo)$this->Szinkronizal();
                else $this->TablaTarol();
            return $this->VisszaLep();
            
        }
}

?>