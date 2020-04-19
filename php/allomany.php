<?php

class CAlapAllomany extends CVaz_bovit
{
        var $Tabla_nev="ALAPALLOMANY";
    

       public function Tablasql()
        {
            $SQL="

CREATE TABLE IF NOT EXISTS `ALAPALLOMANY` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `ALLOMANY_S` varchar(255) DEFAULT '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE." 

";
            return $SQL;    
        }    


        function FizikailagTorol()
        {
                $Mit=$this->RelativEleres();
                if($Mit!="")
                {
                        unlink($Mit);
                        $konyvtar=mb_substr($Mit,0,mb_strrpos($Mit,"/"),STRING_CODE);
                        rmdir($konyvtar);
                }
        }

        function AdatokMasol($MinekObj)
        {
                parent::AdatokMasol($MinekObj);
                $ELERES=$MinekObj->RelativEleres();

                $this->TablaAdatBe("ALLOMANY_S","");
                $this->Tablatarol();

                if ($ELERES!="")$this->Fajltarol($ELERES,$MinekObj->FajlNev(),MAXALLOMANYMERET);
        }

          function Vizjeljpgre($Vizjelforras,$Fajlba=true,$Vizjelmeret=0)
        {
            $Kep=$this->RelativEleres();
//                $Vizjelforras="images/wmark_se.png";
  //              $Kep=$this->RelativEleres();
                if($Kep)
                {

                        if (class_exists('Imagick'))
                        {

                                $im2=$this->Kepletrehoz($Kep);
                                $vizjel=$this->Kepletrehoz($Vizjelforras);

                                $KEPX=$im2->getImageWidth();
                                $KEPY=$im2->getImageHeight();

                                $SX=$vizjel->getImageWidth();
                                $SY=$vizjel->getImageHeight();


                                if ($Vizjelmeret==0)$Vizjelmeret=(80/100)*$KEPX;

                                if ($Vizjelmeret!=0)
                                {
                                        $SX_UJ=$Vizjelmeret;
                                        $SY_UJ=$SY*$SX_UJ/$SX;
                                }else
                                {
                                        $SX_UJ=$SX;
                                        $SY_UJ=$SY;
                                }


                                $vizjel->resizeImage($SX_UJ,$SY_UJ,FILTER_CATROM,1);
                                $im2->compositeImage($vizjel,COMPOSITE_OVERLAY,round($KEPX/2)-round($SX_UJ/2), round($KEPY/2)-round($SY_UJ/2));


//                        imagick_composite($im2,IMAGICK_COMPOSITE_OP_OVER  ,$vizjel,($KEPX)-($SX_UJ), ($KEPY)-($SY_UJ));


                        if ($Fajlba)  $im2->writeImage($Kep);
                        else
                        {
//                                $temp=imagick_image2blob($im2);
                                $temp=$im2->getimageblob();
                                 echo $temp;
                        }

//                        imagick_free($vizjel);
                        }else
                        {
                                $im2=ImageCreateFromJpeg($Kep);
                                $KEPX=ImageSX($im2);

                                $vizjel = @imagecreateFrompng($Vizjelforras);

                                if (is_resource($vizjel))
                                {
                                $SX=ImageSX($vizjel);
                                $SY=ImageSY($vizjel);

                                if ($Vizjelmeret==0)$Vizjelmeret=(100/1024)*$KEPX;

                                if ($Vizjelmeret!=0)
                                {
                                        $SX_UJ=$Vizjelmeret;
                                        $SY_UJ=$SY*$SX_UJ/$SX;
                                }else
                                {
                                        $SX_UJ=$SX;
                                        $SY_UJ=$SY;
                                }

                                $vizjelkicsi=ImageCreate ($SX_UJ,$SY_UJ);
                                imagecolortransparent ($vizjelkicsi, imagecolorat ($vizjelkicsi, 0, 0));

                                imagecopyresampled ($vizjelkicsi, $vizjel, 0, 0, 0, 0, $SX_UJ, $SY_UJ, $SX, $SY);

                                imagecolortransparent ($im2, imagecolorat ($im2, 0, 0));

                                $meret=getimagesize($Kep);
                                $width=$meret[0];
                                $height=$meret[1];

                                $watermark_width=$SX_UJ;
                                $watermark_height=$SY_UJ;

                                $atlatsz=40;
                                $Kozepre=false;
                                if ($Kozepre)
                                {

                                        imagecopymerge($im2, $vizjelkicsi, ($width/2)-($watermark_width/2), ($height/2)-($watermark_height/2), 0, 0, $watermark_width, $watermark_height, $atlatsz);
                                }
                                else
                                {
                                        imagecopymerge($im2, $vizjelkicsi, ($width)-($watermark_width), ($height)-($watermark_height), 0, 0, $watermark_width, $watermark_height, $atlatsz);
                                }
                                }
                                if ($Fajlba)
                                ImageJpeg ($im2,$Kep);
                                else
                                ImageJpeg ($im2);

                                imagedestroy($vizjelkicsi);
                        }
                }
        }
        
      function Kepmeretezkeretbe($Meretx,$Merety)
        {

                if($this->Eleres())
                {
                        $start=$this->Kepletrehoz($this->RelativEleres());
                        
                        if (class_exists('Imagick'))
                        {
                                $SX=$start->getImageWidth();
                                $SY=$start->getImageHeight();
                        }else
                        {
                                $SX=ImageSX($start);
                                $SY=ImageSY($start);
                        }


                        $SX_UJ=$Meretx;
                        $SY_UJ=$SY*$SX_UJ/$SX;
                        if ($SY_UJ>$Merety)$this->KepMeretezMagassagfix($Merety);
                        else
                        {
                                $SY_UJ=$Merety;
                                $SX_UJ=$SX*$SY_UJ/$SY;
                                if ($SX_UJ>$Meretx)$this->KepMeretezSzelessegfix($Meretx);
                                            else $this->KepMeretezMagassagfix($Merety);
                        }
                 }

        }
        
      function Kepmeretezpontosan($Meretx,$Merety)
        {

                if($this->Eleres())
                {
                        $start=$this->Kepletrehoz($this->RelativEleres());
                        if (class_exists('Imagick'))
                        {
                                $SX=$start->getImageWidth();
                                $SY=$start->getImageHeight();
                        
                                if (($SX>=$Meretx)&&($SY>$Merety))
                                {
                                    $SX_UJ=$Meretx;
                                    $SY_UJ=$SY*$SX_UJ/$SX;
                                    if($SY_UJ>$Merety)
                                    {
                                        $this->KepMeretezSzelessegfix($Meretx);
                                        $start=$this->Kepletrehoz($this->RelativEleres());    

                                        $SX=$start->getImageWidth();
                                        $SY=$start->getImageHeight();
                                    }


                                    $SY_UJ=$Merety;
                                    $SX_UJ=$SX*$SY_UJ/$SY;
                                    if($SX_UJ>$Meretx)
                                    {
                                        $this->KepMeretezMagassagfix($Merety);
                                        $start=$this->Kepletrehoz($this->RelativEleres());    

                                        $SX=$start->getImageWidth();
                                        $SY=$start->getImageHeight();
                                    }
                                   
                                    $Felx=round($SX/2);
                                    $Fely=round($SY/2);
                                    $start->cropImage ($Meretx,$Merety,$Felx-round($Meretx/2),$Fely-round($Merety/2));
                                    $start->writeImage($this->RelativEleres());
                                    
                                }
                        }
                        
                        
                 }

        }         
        
        function KepMeretezSzelessegfix($Meret)
        {
                if($this->Eleres())
                {
                        $start=$this->Kepletrehoz($this->RelativEleres());
                        
                        if (class_exists('Imagick'))
                        {
                                $SX=$start->getImageWidth();
                                $SY=$start->getImageHeight();
                        }else
                        {
                                $SX=ImageSX($start);
                                $SY=ImageSY($start);
                        }
                        

                        $SX_UJ=$Meret;
                        $SY_UJ=round($SY*$SX_UJ/$SX);
                        if ($SX_UJ>$SX)return "";


                        if (class_exists('Imagick'))
                        {
                                $jo0=$start->resizeImage($SX_UJ,$SY_UJ,FILTER_CATROM,1);
                                $jo=$start->writeImage($this->RelativEleres());

                        }else
                        {
                                $cel=ImageCreatetruecolor ($SX_UJ,$SY_UJ);
                                imagecopyresampled ($cel, $start, 0, 0, 0, 0, $SX_UJ, $SY_UJ, $SX, $SY);
                                ImageJpeg ($cel,$this->RelativEleres(),100);
                        }

                }
        }

        function KepMeretezMagassagfix($Meret)
        {
                if($this->Eleres())
                {
                        $start=$this->Kepletrehoz($this->RelativEleres());
                        
                        if (class_exists('Imagick'))
                        {
                                $SX=$start->getImageWidth();
                                $SY=$start->getImageHeight();
                        }else
                        {
                                $SX=ImageSX($start);
                                $SY=ImageSY($start);
                        }
                        $SY_UJ=$Meret;
                        $SX_UJ=round($SX*$SY_UJ/$SY);
                        if ($SY_UJ>$SY)return "";
                        if (class_exists('Imagick'))
                        {
                                $start->resizeImage($SX_UJ,$SY_UJ,FILTER_CATROM ,1);
                                $start->writeImage($this->RelativEleres());

                        }else
                        {
                                $cel=ImageCreatetruecolor ($SX_UJ,$SY_UJ);
                                imagecopyresampled ($cel, $start, 0, 0, 0, 0, $SX_UJ, $SY_UJ, $SX, $SY);
                                ImageJpeg ($cel,$this->RelativEleres(),100);
                        }
                }
        }

        function RekurzivTorol()
        {
                $this->FizikailagTorol();
                CVaz::RekurzivTorol();
        }

        function FajlTorol()
        {
                $this->FizikailagTorol();
                $this->TablaAdatBe("ALLOMANY_S","");
                $this->Szinkronizal();
        }
        
        function FajlNev()
        {
              $Vissza=substr($this->Eleres(),strrpos($this->Eleres(),"/")+1,strlen($this->Eleres()));
              return $Vissza;

        }
        
        static function Konyvtarbeolvas($Konyvtar)
        {

          $handle = opendir($Konyvtar);
          $vissza=false;
          while (false !== ($file = readdir($handle)))
          {
                if ($file != "." && $file != "..")
                {
                        if (is_dir($Konyvtar.chr(47).$file))
                        {
                        }
                        else
                        {
                            $vissza[]="$Konyvtar"."$file";
                        }
                 }
          }
          closedir($handle);
          return $vissza;
        }

        
function Konyvtarad()
{
         $Evho=date("Y_m");
         $Nap=date("d");
         $tempdir=true;
         $tempdirbe="";
         if (!(is_dir(FELTOLTES."$Evho")))
         {
                 if(!mkdir(FELTOLTES."$Evho", 0777))
                 {
                        $tempdir=false;
                 }
         }
         if ($tempdir)
         {
                 if (!(is_dir(FELTOLTES."$Evho/$Nap")))
                 {
                         if(!mkdir(FELTOLTES."$Evho/$Nap", 0777))
                         {
                                $tempdir=false;
                         }
                 }

         }
         if ($tempdir)
         {
                $tempdir=FELTOLTES."$Evho/$Nap/".date("U").rand(1000,9000).rand(1000,9000)."/";
                if(!mkdir($tempdir, 0777))
                {
                        $tempdir=false;
                }
         }
         return $tempdir;

}

        function Fajltarol($Allomany,$Allomany_nev,$Limit,$Meretez=null)
        {
            if ($Meretez===null)$Meretez="";
            
                $kezd=strrpos($Allomany_nev,".");
                $Kiterjesztes=strtoupper(substr($Allomany_nev,$kezd+1));
                if (($Kiterjesztes=="PHP")||($Kiterjesztes=="PHTML")||($Kiterjesztes=="PHP3"))
                {
/*                        $this->ScriptUzenetAd("Ilyen formátumu fájl felvitele nem megengedett!");
                        $Allomany_nev=substr($Allomany_nev,0,$kezd).".txt";*/
                        $Allomany_nev.=".txt";
                }
                $Allomany_nev = strtolower($Allomany_nev);
                $Allomany_nev=$this->Erveny_nevre($Allomany_nev);

                $Limit=1024*$Limit;
                $AllomanyMeret=(int) filesize($Allomany);
                $eredmeny=($Limit-$AllomanyMeret);
                if($eredmeny>0)
                {
                        if (($Allomany)&&($Allomany!="none"))
                        {
                                $this->FizikailagTorol();
                                $tempdir=$this->Konyvtarad();
                                if(!$tempdir)
                                {
                                        $this->TablaAdatBe("ALLOMANY_S","");
                                        $Vissza=0;
                                        $this->ScriptUzenetAd("A könyvtár nem jött létre!");
                                }
                                else
                                {
                                        if(!copy($Allomany,$tempdir.$Allomany_nev))
                                        {
                                                $this->ScriptUzenetAd("Nem sikerült a másolás!");
                                                $this->TablaAdatBe("ALLOMANY_S","");
                                                rmdir($tempdir);
                                                $Vissza=0;

                                        }
                                        else
                                        {
                                                $this->TablaAdatBe("ALLOMANY_S",$tempdir.$Allomany_nev);
                                                $this->Szinkronizal();
                                                
                                                if ($Meretez!="")
                                                {
                                                    $this->_Meretseged($Meretez);
                                                }
                                                $Vissza=1;
                                        }
                                }
                        }else
                        {
                                $this->ScriptUzenetAd("Nem adot meg állományt!");
                                $Vissza=0;
                        }
                }
                else
                {
                        $this->ScriptUzenetAd("Nem adot meg állományt, vagy az állomány mérete meghaladhatja $Limit bájtot!");
                        $Vissza=0;
                }
                return $Vissza;
        }
        
        function _Meretseged($Meret)
        {
            $Kar=mb_substr($Meret,0,1,STRING_CODE);
            if ("$Kar"=="m")
            {
                $Meret=mb_substr($Meret,1,9999999,STRING_CODE);
                $this->KepMeretezMagassagfix($Meret);
            }else
            if ("$Kar"=="s")
            {
                $Meret=mb_substr($Meret,1,9999999,STRING_CODE);
                
                $this->KepMeretezSzelessegfix($Meret);
            }
            if ("$Kar"=="k")
            {
                $Meret=mb_substr($Meret,1,9999999,STRING_CODE);

                $Reszek=explode("-",$Meret);
                $this->Kepmeretezkeretbe($Reszek[0],$Reszek[1]);
            }
            if ("$Kar"=="p")
            {
                $Meret=mb_substr($Meret,1,9999999,STRING_CODE);

                $Reszek=explode("-",$Meret);
                $this->Kepmeretezpontosan($Reszek[0],$Reszek[1]);
            }
            
            
        }

        function Eleres()
        {
                $Vissza=$this->TablaAdatKi("ALLOMANY_S");

                $Eleje=mb_substr($Vissza,0,1,STRING_CODE);
                if (("$Eleje"!="/")&&($Eleje!=""))
                {
                        $Vissza="/$Vissza";
                }
                return $Vissza;
        }
        function RelativEleres()
        {
                $Vissza=$this->Eleres();
                $Eleje=mb_substr($Vissza,0,1,STRING_CODE);
                if ($Eleje=="/")$Vissza=mb_substr($Vissza,1,9999,STRING_CODE);
                return $Vissza;
        }
        
         function Kepletrehoz($Kep)
        {
                if (class_exists('Imagick'))
                {
                        $res=new Imagick($Kep);
                        if (!$res)
                        {
                                $Vissza=false;
                                $this->ScriptUzenetAd("A kép formátuma hibás!");
                        }else $Vissza=$res;
                }else
                {
                     $info = getImageSize($Kep) ;
                switch ($info[2])
                {
                        case 1:
                          $info[2]="gif";
                          if (imagetypes() & IMG_GIF)
                          {
                                $Vissza = @imageCreateFromGIF($Kep);
                                if ($Vissza=="")
                                {
                                        $Vissza=false;
                                        $this->ScriptUzenetAd("A gif kép formátuma hibás!");
                                }
                          } else
                          {
                                $Vissza=false;
                                $this->ScriptUzenetAd("Gif képek nem támogatottak");
                          }
                        break;
                        case 2:
                          $info[2]="jpg";
                          if (imagetypes() & IMG_JPG)
                          {
                               $Vissza=imageCreateFromJPEG($Kep) ;
                                if ($Vissza=="")
                                {
                                        $Vissza=false;
                                        $this->ScriptUzenetAd("A jpg kép formátuma hibás!");
                                }
                          } else
                          {
                                $Vissza=false;
                                $this->ScriptUzenetAd("Jpg képek nem támogatottak");
                          }
                        break;
                        case 3:
                          $info[2]="png";
                          if (imagetypes() & IMG_PNG)
                          {
                                $Vissza = @imageCreateFromPNG($Kep) ;
                                if ($Vissza=="")
                                {
                                        $Vissza=false;
                                        $this->ScriptUzenetAd("A png kép formátuma hibás!");
                                }
                          } else
                          {
                                $this->ScriptUzenetAd("Png képek nem támogatottak");
                          }
                       break;
                      case 4:
                      $info[2]="wbmp";
                          if (imagetypes() & IMG_WBMP)
                          {
                                $Vissza = @imageCreateFromWBMP($Kep) ;
                          } else
                          {
                                $this->ScriptUzenetAd("Wbmp képek nem támogatottak");
                          }
                      break;
                      default:
                                $this->ScriptUzenetAd($image_info['mime']." nem támogatott");
                      break;
                }
                }
                return $Vissza;

        }
        
}




class CAllomany extends CAlapAllomany
{
        var $Tabla_nev="ALLOMANY";
    

       public function Tablasql()
        {
            $SQL="

CREATE TABLE IF NOT EXISTS `ALLOMANY` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `ALLOMANY_S` varchar(255) DEFAULT '',
  `NEV_S` varchar(150) DEFAULT '',
  KLINK_S char(230) default '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE." 
alter table ALLOMANY add column NEV_EN_S char(240) default ''

";
            return $SQL;    
        } 
        
        function Adatlist_publ($Kellkep=true)
        {
            $Vissza["File"]=$this->Eleres();
            $Vissza["Link"]=$this->NevAd();
            $Vissza["Azon"]=$this->AzonAd();
              
            return $Vissza;
        }        
        
         function Adatlist_adm_tag()
        {
            $Vissza["TOROL"]=1;
            $im2=$this->Kepletrehoz($this->RelativEleres());
            if ($im2)
            {
                $Vissza["EGYEB"][0]["Nev"]="<img src='".$this->Eleres()."' width='60'>";
                $Vissza["EGYEB"][0]["Link"]="#";
            }
            
            return $Vissza;    
        }        
        
        function NevAd()
        {
            return $this->TablaAdatKi("NEV_S");
        }
        
        function Tarol($Allomany,$Allomany_nev,$NEV)
        {
            $this->Fajltarol($Allomany,$Allomany_nev,MAXALLOMANYMERET);
            $this->TablaAdatBe("NEV_S",$NEV);
            $this->TablaTarol();
            return $this->VisszaLep();
        }
}  


class CAllomanykep extends CAlapAllomany
{

        function NevAd()
        {
            return "";
        }
        
         function Adatlist_adm_tag()
        {
            $Vissza["TOROL"]=1;
            
            $LISTA=$this->Eleres();

            
            $Vissza["EGYEB"][0]["Nev"]="<img src='".$LISTA."' width='60'>";
            $Vissza["EGYEB"][0]["Link"]=$this->EsemenyHozzad("UrlapKi");
            $Vissza["URLAP"]=1;
            
            
            return $Vissza;    
        } 
          
  
}

?>