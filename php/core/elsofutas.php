<?php


 /**
 * CElsofutas - legelső futást szabályozó osztály. Létrehozza az adatbázist, mysql usert, root jelszavakat,  alaptáblákat, ha  ADATBAZISNEV konstans üres 
 */
 
class CElsofutas extends CVaz_bovit  
{
 
 /**
 * Mutat - ha tényleg az első futásnál vagyunk, kirakja az alap jelszó bekérő űrlapot, űrlap elküldés után pedig a tárolást.    
 * @return 0 vagy 1 
 */ 
    function Mutat()
    {
        if ($this->Elsofutase())
        {
            $Formbol=$this->Postgetv("Formbol");
            if (isset($Formbol)&&("$Formbol"=="1"))    
            {
                $Tartalom=$this->Sqlfuttat();
                    
            }else
            {
                $Jelszo="";
                for($i=0;$i<3;$i++)
                {
                        $Jelszo.=chr(rand(65,90));
                }
                for($i=0;$i<3;$i++)
                {
                        $Jelszo.=chr(rand(48,57));
                }
                $Jelszo=strtolower($Jelszo);

                $USER_JELSZO="";
                $kodbetu="1234567890qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM";
                $betuhossz=strlen($kodbetu);
                for ($c=0;$c<8;$c++)
                {
                        $rand=rand(0,$betuhossz-1);
                        $USER_JELSZO.=substr($kodbetu,$rand,1);
                }

                $Form=new CForm("Elsoform","");
                $Form->Hidden("Formbol",1);
                $Form->Szabad2(" ","Alap sql-ek létrehozása");
                $Form->Textbox("Adatbázis neve: (adatbázis user neve is ez lesz)","ADATB_NEV","","");
                $Form->Textbox("Adatbázis user jelszava:","USER_JELSZO",$USER_JELSZO,"");
                

                $Form->Checkbox("Felhasználó-csoport látszik:","FELHCSOPORT","1","checked");
                $Form->Checkbox("Portál feliratai látszik (több nyelv esetén):","NYELV_LESZ","1","checked");
                $Form->Textbox("ROOT jelszó:","JELSZO","","");
                $Form->Textbox("DEBUGROOT jelszó:","DEBUGJELSZO",$Jelszo,"");
                $Form->Gomb("Létrehozás","return true","submit");
                $Tartalom=$this->Sablonbe("Oldal_ures",$Form->OsszeRak());
                
                
            }
            return $Tartalom;
        }else die("Hiba");        
    }
    



 /**
 * Sqlfuttat - futtatja az sql-eket 
 
 */ 

    function Sqlfuttat()
    {
        $Sql=$this->Defsqlek();
        
        $ADATB_NEV=$this->Postgetv("ADATB_NEV");
        self::$Sql->Connect(ADATBAZIS_HOST, ADATBAZIS_USER,ADATBAZIS_PASS,$ADATB_NEV);
        
        $Sorok=explode("§",$Sql["Sql_strukt"]);
        foreach ($Sorok as $Egy)
        {
            self::$Sql->Modosit($Egy);

        }
        $USER_JELSZO=$this->Postgetv("USER_JELSZO");

        self::$Sql->Connect(ADATBAZIS_HOST, $ADATB_NEV, $USER_JELSZO,$ADATB_NEV);
        
        $Sorok=explode("§",$Sql["Sql_data"]);
        foreach ($Sorok as $Egy)
        {
                $Jo=self::$Sql->Modosit($Egy);

        }
        return $Sql["Uzen"];        
    }

    


 /**
 * Elsofutase - tényleg az első futásnál vagyunk e. Ha ADATBAZISNEV üres akkor igen, ha nem akkor vmi adatbázis hiba miatt nem futhat a php. Ilyenkor hibát dobunk.  
 * @return 0 vagy 1 
 */ 
    function Elsofutase()
    {
        if (ADATBAZISNEV=="")$Vissza=true;
                              else $Vissza=false;
        return $Vissza;
    }

 /**
 * Defsqlek - legelső sql parancsokat adja vissza. Létrehozza adatbázist, mysql usert/jelszót, alap táblákat.   
 * @return array [Sql_strukt] - adatbázis, mysql usert hozza létre
 *               [Sql_data] - alap táblák létrehozása    
 */
    function Defsqlek()
    {
        $ADATB_NEV=$this->Postgetv("ADATB_NEV");
        $USER_JELSZO=$this->Postgetv("USER_JELSZO");
        $JELSZO=$this->Postgetv("JELSZO");
        $DEBUGJELSZO=$this->Postgetv("DEBUGJELSZO");

        $FELHCSOPORT=$this->Postgetv("FELHCSOPORT",1);
        if ($FELHCSOPORT)$Lista="CSOPORT";
                else $Lista="REJTCSOPORT";

        $NYELV_LESZ=$this->Postgetv("NYELV_LESZ",1);
        if ($NYELV_LESZ)$Lista_nyelv="CSOPORT";
                else $Lista_nyelv="REJTCSOPORT";
        
        
        $Vissza["Uzen"]="$ADATB_NEV adatbázis létrehozva. <br>
php/valtozok.php -ba írd át: <br> 
ADATBAZISNEV: $ADATB_NEV <br>
ADATBAZIS_USER $ADATB_NEV <br>
ADATBAZIS_PASS $USER_JELSZO <br>

         
        ";
        $Vissza["Sql_strukt"]="
 create database $ADATB_NEV CHARACTER SET utf8 COLLATE utf8_general_ci;§

CREATE USER '$ADATB_NEV'@'".ADATBAZIS_HOST."' IDENTIFIED BY '$USER_JELSZO';§
  

GRANT ALL PRIVILEGES ON $ADATB_NEV.* TO  '$ADATB_NEV'@'".ADATBAZIS_HOST."' IDENTIFIED BY '$USER_JELSZO'  WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0

   ";
        $Vissza["Sql_data"]="
        
CREATE TABLE FELHASZNALO (
  `AZON_I` int(13) auto_increment,
  `SZAML_NEV_S` varchar(200) default '',
  `SZAML_VAROS_S` varchar(200)  default '',
  `SZAML_IRSZAM_S` varchar(10) default '',
  `SZAML_CIM_S` varchar(150)  default '',
  `SZALL_NEV_S` varchar(200) default '',
  `SZALL_VAROS_S` varchar(200)  default '',
  `SZALL_IRSZAM_S` varchar(10) default '',
  `SZALL_CIM_S` varchar(150)  default '',
  `AKTIV_I` tinyint default 0,
  `LOGIN_S` varchar(50) default '',
  `JELSZO_S` varchar(50) default '',
  `EMAIL_S` varchar(50) default '',
  `TELSZAM_S` varchar(20) default '',
  `AKTIVALO_KOD_S` varchar(40),
  `SESSID_S` varchar(40) DEFAULT '',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."§

create index LOGIN_S on FELHASZNALO(LOGIN_S)§
create index EMAIL_S on FELHASZNALO(EMAIL_S)§
create index JELSZO_S on FELHASZNALO(JELSZO_S)§
create index AKTIV_I on FELHASZNALO(AKTIV_I)§



CREATE TABLE `VAZ` (
  `VZ_AZON_I` int(13) NOT NULL auto_increment,
  `VZ_SZULO_I` int(13) default 0,
  `VZ_KESZULT_D` DATETIME default '1900-01-01 00:00:00',
  `VZ_MASOLAT_I` int(1) default 0,
  `VZ_SZINKRONIZALT_I` tinyint(1) default 0,
  `VZ_TABLA_S`  char(30) default '',
  `VZ_TABLA_AZON_I` int(13) default 0,
  `VZ_OBJEKTUM_S` char(30) default '',
  `VZ_LISTA_S` char(30) default 'ALAP',
  `VZ_KULSO_KELL_I` tinyint(1) default '1',
  `VZ_KULSO_LINK_S` varchar(80) default '',
  `VZ_FELHASZNALO_I` int(11) default '0',
  `VZ_SORREND_I` int(11) default 0,
  `VZ_NYELV_S` char(3) default 'HU',
  PRIMARY KEY  (`VZ_AZON_I`)
) ENGINE=".MYSQL_ENGINE."§

create index VZ_SZULO_I on VAZ(VZ_SZULO_I)§
create index VZ_KESZULT_D on VAZ(VZ_KESZULT_D)§
create index VZ_MASOLAT_I on VAZ(VZ_MASOLAT_I)§
create index VZ_SZINKRONIZALT_I on VAZ(VZ_SZINKRONIZALT_I)§
create index VZ_TABLA_S on VAZ(VZ_TABLA_S)§
create index VZ_TABLA_AZON_I on VAZ(VZ_TABLA_AZON_I)§
create index VZ_OBJEKTUM_S on VAZ(VZ_OBJEKTUM_S)§
create index VZ_LISTA_S on VAZ(VZ_LISTA_S)§
create index VZ_KULSO_LINK_S on VAZ(VZ_KULSO_LINK_S)§
create index VZ_SORREND_I on VAZ(VZ_SORREND_I)§
create index VZ_NYELV_S on VAZ(VZ_NYELV_S)§


CREATE TABLE `SZOVEG` (
  `AZON_I` int(13) NOT NULL auto_increment,
  `NEV_S` varchar(210) default '',
  `BEVEZETO_S` text,
  `SZOVEG_S` text,
  `KERESO_TITLE_HU_S` text,
  `KERESO_KEY_HU_S` text,
  `KERESO_DESC_HU_S` text,
  `BEILLESZTES_S` varchar(150) default '',
  `AKTIV_I` tinyint(1) default '1',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."§

create index AKTIV_I on SZOVEG(AKTIV_I)§




CREATE TABLE `PARAMETER`(
  `NEV_S` varchar(90) default '',
  `ERTEK_S` text,
  PRIMARY KEY  (`NEV_S`)  
) ENGINE=".MYSQL_ENGINE."§

create index NEV_S on PARAMETER(NEV_S)§


CREATE TABLE IF NOT EXISTS `MULTIMEDIA` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `NEV_S` varchar(155) DEFAULT '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE." 
§

CREATE TABLE IF NOT EXISTS `FONYELV` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `NEV_S` varchar(255) DEFAULT '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE."§ 

ALTER TABLE `FONYELV` CHANGE `NEV_S` `NEV_S` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL§

create index NEV_S on FONYELV(NEV_S)§

CREATE TABLE `NYELV` (
  `AZON_I` int(11) NOT NULL auto_increment,
  `TARTALOM_S` text,
   PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."§



INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I,VZ_NYELV_S) VALUES(1,0,NOW(),'SZOVEG',1,'CMainCsoport',1,0,'fo',3,'HU')§
INSERT INTO `SZOVEG`(`AZON_I`,`NEV_S`,`SZOVEG_S`) VALUES(1,'Főcsoport','')§


INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I,VZ_SORREND_I,VZ_LISTA_S) 
VALUES(2,1,NOW(),'SZOVEG',2,'CFelhasznaloCsoport','1','0','felhcsoport',3,'1','$Lista')§
INSERT INTO `SZOVEG`(`AZON_I`,`NEV_S`,`SZOVEG_S`) VALUES(2,'Felhasználók','')§

INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_LISTA_S,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I) 
VALUES(3,2,NOW(),'FELHASZNALO',1,'CAdminFelhasznalo',1,'ADMIN',0,'felhasznalo1',3)§
INSERT INTO `FELHASZNALO`(`AZON_I`,`SZAML_NEV_S`,`LOGIN_S`,`JELSZO_S`,`AKTIV_I`) VALUES(1,'Adminisztrátor','ROOT','".md5($JELSZO)."',1)§

INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_LISTA_S,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I) 
VALUES(4,2,NOW(),'FELHASZNALO',2,'CSenkiFelhasznalo',1,'NOBODY',0,'felhasznalo2',3)§
INSERT INTO `FELHASZNALO`(`AZON_I`,`SZAML_NEV_S`,`LOGIN_S`,`JELSZO_S`,`AKTIV_I`) VALUES(2,'Nincs belépve','".md5('Olyan szöveg amit senki nem tud kitalálni.8')."','Ehhez a szöveghezz illő jelszó.9',1)§

INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_LISTA_S,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I) 
VALUES(5,2,NOW(),'FELHASZNALO',3,'CDebugFelhasznalo',1,'DEBUGADMIN',0,'felhasznalo3',3)§
INSERT INTO `FELHASZNALO`(`AZON_I`,`SZAML_NEV_S`,`LOGIN_S`,`JELSZO_S`,`AKTIV_I`) VALUES(3,'Debug adminisztrátor','DEBUGROOT','".md5($DEBUGJELSZO)."',1)§

INSERT INTO `VAZ`(`VZ_AZON_I`,`VZ_SZULO_I`,`VZ_KESZULT_D`,`VZ_TABLA_S`,`VZ_TABLA_AZON_I`,`VZ_OBJEKTUM_S`,`VZ_SZINKRONIZALT_I`,VZ_KULSO_KELL_I,VZ_KULSO_LINK_S,VZ_FELHASZNALO_I,VZ_SORREND_I,VZ_LISTA_S) 
VALUES(6,1,NOW(),'SZOVEG',3,'CNyelvForditCsoport','1','0','Portal_feliratai',3,'2','$Lista_nyelv')§

INSERT INTO `SZOVEG`(`AZON_I`,`NEV_S`,`SZOVEG_S`) VALUES(3,'Portál feliratai','')

                ";
        return $Vissza;                        
    }

}


?>