<?php
define ("ARDB",21);



define ("SZALL_KLTS_AZON",9818);

function Kedvtabla()
{
$SQL="
-- phpMyAdmin SQL Dump

--
-- Adatbázis: `marclean`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `KEDVEZMENY`
--

CREATE TABLE `KEDVEZMENY` (
  `AZON` int(13) NOT NULL,
  `NEV` varchar(80) DEFAULT '',
  `AR0` text,
  `AR1` text,
  `AR2` text,
  `AR3` text,
  `AR4` text,
  `AR5` text,
  `AR6` text,
  `AR7` text,
  `AR8` text,
  `AR9` text,
  `CIMKE` text,
  `AR10` varchar(200) DEFAULT '',
  `AR11` varchar(200) DEFAULT '',
  `AR12` varchar(200) DEFAULT '',
  `AR13` varchar(200) DEFAULT '',
  `AR14` varchar(200) DEFAULT '',
  `AR15` varchar(200) DEFAULT '',
  `AR16` varchar(200) DEFAULT '',
  `AR17` varchar(200) DEFAULT '',
  `AR18` varchar(200) DEFAULT '',
  `AR19` varchar(200) DEFAULT '',
  `AR20` varchar(200) DEFAULT ''
) ENGINE=innodb DEFAULT CHARSET=latin2;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `KEDVEZMENY`
--
ALTER TABLE `KEDVEZMENY`
  ADD PRIMARY KEY (`AZON`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `KEDVEZMENY`
--
ALTER TABLE `KEDVEZMENY`
  MODIFY `AZON` int(13) NOT NULL AUTO_INCREMENT;


";

}


class CSzallktlsgcsoport extends CCsoport
{


        function Adatlist_adm_tag()
        {
   
            $Vissza["LISTA"]=1;
            return $Vissza;    
        }

        


        function Kedvezmeny($AR)
        {
                
              //  $AR=round($AR);
                $Vissza=$this->Futtatgy("KOLTSEG")->Kedvezmeny($AR);
                
                return $Vissza["Eredm"][0];
        }

        function Lista_fut()
        {
                
/*                $Obj=$this->UjObjektumLetrehoz("CSzallkltsg","KOLTSEG");
                $Obj->Szinkronizal();
                return "f";
                */
                $Vissza=$this->Futtatgy("KOLTSEG")->UrlapKi();

                return $Vissza["Eredm"][0];
        }

}




class CSzallkltsg extends CVaz_bovit
{

        var $Tabla_nev="KEDVEZMENY";

        function SzerkesztoSorBeallit()
        {
                $this->Szerkeszto["URLAP"]=1;
        }

       function UrlapKi_fut()
        {
            return $this->UrlapKi();
        }

        function UrlapKi()
        {

                $Szul=$this->SzuloObjektum();
                $Cim=$Szul->NevAd()." szerkesztése";


                $Tarol=$this->EsemenyHozzad("Tarol_fut");

                $this->AllapotBe("Feladat","UrlapKi");

                $CIMKE=$this->TablaAdatKi("CIMKE");

                $Vissza=$this->VisszaEsemenyAd();
                $this->ScriptTarol("
                function Ellenor()
                {
                        return true;
                }
                ");

                $Cimkek=explode("§",$CIMKE);


                $Form=new CForm("TermekForm","","");
                $Form->Hidden("Esemeny_Uj","$Tarol");

                $Tablazat="<table cellspacing=0 cellpadding=0 width=100% border='0'>
                <tr>
                   <td align=center><span class=txt_main>Rendelés bruttó értéke tól </span></td>
                   <td align=center><span class=txt_main>Rendelés bruttó értéke ig </span></td>


                   <td align=center><span class=txt_main>Fizetendő nettó </span></td>
                </tr>
                   ";

                for ($c=0;$c<ARDB;$c++)
                {
                        $Adat=$this->TablaAdatKi("AR".$c);
                        
                        $Ertek=explode("-",$Adat);
                        if (!isset($Ertek[1]))$Ertek[1]="";
                        if (!isset($Ertek[2]))$Ertek[2]="";

                        $Tablazat.="<tr>
                         <td align=center><span class=txt_main><input style=\"font-size: 12px; width: 70px;\" type=text name='AR0$c' value='".$Ertek[0]."'> tól </span></td>
                         <td align=center><span class=txt_main><input style=\"font-size: 12px; width: 70px;\" type=text name='AR1$c' value='".$Ertek[1]."'> ig</span></td>
                         <td align=center><span class=txt_main><input type=text name='AR2".$c."' style=\"font-size: 12px; width: 70px;\" value='$Ertek[2]' size=5> Ft</span></td>
                         </tr>
                         ";
                }
                $Tablazat.="</table>";
                $Form->Szabad1($Tablazat);


                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();

                $Cim="Szállítási költség szerkesztése";
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));

                

        }


        function Kedvezmeny($REND_AR)
        {
                $Vissza=0;
                
                for ($c=0;$c<ARDB;$c++)
                {
                        $AR=$this->TablaAdatKi("AR$c");
                        $Adatok=explode("-",$AR);
                        $Ertektol=$Adatok[0];
                        if (isset($Adatok[1]))$Ertekig=$Adatok[1];
                                        else $Ertekig="";
                        if (isset($Adatok[2]))$Ertek=$Adatok[2];
                                        else $Ertek="";

                        if (((int)$REND_AR>=(int)$Ertektol)&&((int)$REND_AR<=(int)$Ertekig))
                        {
                            $Vissza=$Ertek;
                                
                        }
                }
                return $Vissza;


        }

        function Tarol_fut()
        {

                $Submit=$this->Postgetv("Submit");

                if ($Submit!="Mégsem")
                {
                        $CIMKE="";
                        for ($c=0;$c<ARDB;$c++)
                        {
                                $TOL=$_POST["AR0$c"];
                                $TOL=str_replace(",",".",$TOL);

                                $IG=$_POST["AR1$c"];
                                $IG=str_replace(",",".",$IG);
                                
                                $CIMKE=$TOL."-".$IG."-".$_POST["AR2$c"];
                                $this->TablaAdatBe("AR$c",$CIMKE);

                        }

                        $this->Szinkronizal();

                        $jo=true;

                        if ($jo)
                        {
                                $this->ScriptUzenetAd("Adatok tárolva!");
                                $Szul=$this->SzuloObjektum();
                                $Szul=$Szul->SzuloObjektum();
                                $Vissza=$Szul->Alap_fut();
                        }else
                        {
                                $Vissza=$this->UrlapKi();
                        }

                }else
                {
                        $Vissza=$this->VisszaLep();
                }


                return $Vissza;

        }


}


?>
