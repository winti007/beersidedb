<?php


/**
 * CSablon - alap sablon osztály. Oldal állandói design elemei, általános formázási függvények. 
 * Sablonokat osztályokba lehet sorolni VAZ osztályok alapján - VAZ osztály_sablon néven
 */





class CSablon extends CAlap  
{
    
/**
 * Gombcsinal - gombot ad vissza
 * @param $Felirat gomb felirata 
 * @param $Onclick onclick esemény 
 * @param $Type  típus. button vagy submit lehet  
 * @param $Name neve 
 * @param $Egyebtag akármilyen tag

 */
        function Gombcsinal($Felirat,$Onclick="",$Type="button",$Name="button2",$Egyebtag='')
        {
                return "<input name='$Name' onclick=\"$Onclick \" type='$Type' $Egyebtag id='$Name' value='$Felirat' class='butt_kosarba formbutt'/>
                ";
        }
        
        function Gombcsinal_v($Felirat,$Onclick="",$Type="button",$Name="button2",$Egyebtag='')
        {
                return "<input name='$Name' onclick=\"$Onclick \" type='$Type' $Egyebtag id='$Name' value='$Felirat' class='butt_kosarba formbutt vissza'/>
                ";
        }
        
        
        
        
        function ArFormaz($AR,$Ft=true)
        {
                $ARSZ=round($AR);

                if ($ARSZ==0)return "";

                $tort=fmod($AR,1);

                if ($tort=="0")$Tized=0;
                        else $Tized=2;
                $Tized=0;
                $AR=number_format($AR,$Tized,'.',' ');
                if ($Ft)$AR.="- Ft";
                return $AR;

        }
                
       function Hirlevelbe($Data)
        {
                $CIM=$Data["Targy"];
                $SZOVEG=$Data["Szoveg"];
           
                $Erre="
<BR";

                $SZOVEG=str_replace("<BR",$Erre,$SZOVEG);

/*                $fajl=fopen("style/style.css","r");
                $stilusok=fread($fajl,filesize("style/style.css"));
                fclose($fajl);

                $stilusok=str_replace("../images",OLDALCIM."images",$stilusok);
                */
                $Tartalom=" <!doctype html>
<html>
<head>
<meta charset='utf-8'>
<title>Untitled Document</title>
</head>

<body style='padding: 0; margin: 0; font-family:Arial, Helvetica, sans-serif; font-size: 12px;'>
<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td style='border: 1px solid #000;' ><table width='100%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><a href='".OLDALCIM."'><img src='".OLDALCIM."templ/images/beerside-logo_new600.png' width='100%' alt=''/></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  style='padding-left: 10px; padding-right: 10px'>
                        <h1>$CIM </h1>
                        $SZOVEG
          
       </td>
      </tr>
      <tr>
        <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td height='30'>&nbsp;</td>
          </tr>
          <tr>
            <td style='padding-left: 10px; padding-right: 10px' align=left><!--leiratk--></td>
          </tr>
          <tr>
            <td align=left height=5></td>
          </tr>

          <tr>
            <td bgcolor='#000'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td width='171'><a href='#'></a></td>
                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style='text-align: right; color: #FFF; font-size: 12px;'>K&ouml;sz&ouml;nj&uuml;k, hogy minket v&aacute;lasztott!</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style='text-align: right; color: #FFFFFF; font-size: 12px;'><a href='".OLDALCIM."' style='text-decoration: none; color: #FFFFFF;'>www.beerside.hu</a>&nbsp;&nbsp;&nbsp;&nbsp;  </td>
                      </tr>
                    </table></td>
                    <td width='20'>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
";
/*
                $Tartalom="
<html xmlns:v=\"urn:schemas-microsoft-com:vml\"
xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:w=\"urn:schemas-microsoft-com:office:word\"
xmlns:m=\"http://schemas.microsoft.com/office/2004/12/omml\"
xmlns=\"http://www.w3.org/TR/REC-html40\">
<head>
<title>$CIM</title>
<meta name=ProgId content=Word.Document>
<meta name=Generator content=\"Microsoft Word 12\">
<meta name=Originator content=\"Microsoft Word 12\">
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>


</head><body ><p class=txt_main>
<b>$CIM</b><br><br>
$SZOVEG      ";*/
                        $Tartalom=str_replace("src='/images","src='".OLDALCIM."images",$Tartalom);
                        $Tartalom=str_replace("src='/images/","src='".OLDALCIM."images/",$Tartalom);
                        $Tartalom=str_replace("src=\"/images","src=\"".OLDALCIM."images",$Tartalom);
                        $Tartalom=str_replace("src='/gfx","src='".OLDALCIM."gfx",$Tartalom);
                        $Tartalom=str_replace("src='/templ/images/","src='".OLDALCIM."/templ/images/",$Tartalom);

                        $Tartalom=str_replace("src='/templ/gfx/","src='".OLDALCIM."/templ/gfx/",$Tartalom);
                        $Tartalom=str_replace("src=\"/gfx","src=\"".OLDALCIM."gfx",$Tartalom);

                        $Tartalom=str_replace("src='/editor_up","src='".OLDALCIM."editor_up",$Tartalom);
                        $Tartalom=str_replace("src=\"/editor_up","src=\"".OLDALCIM."editor_up",$Tartalom);
                        $Tartalom=str_replace("src=/editor_up","src=".OLDALCIM."editor_up",$Tartalom);
                        $Tartalom=str_replace("href='/editor_up","href='".OLDALCIM."editor_up",$Tartalom);
                        $Tartalom=str_replace("href=\"/editor_up","href=\"".OLDALCIM."editor_up",$Tartalom);
                        $Tartalom=str_replace("href=/editor_up","href=".OLDALCIM."editor_up",$Tartalom);



                        $Tartalom=str_replace("src='/upload","src='".OLDALCIM."upload/",$Tartalom);
                        $Tartalom=str_replace("src=\"/upload/","src=\"".OLDALCIM."upload/",$Tartalom);

                        $Tartalom=str_replace("src='upload","src='".OLDALCIM."upload/",$Tartalom);
                        $Tartalom=str_replace("src=\"upload/","src=\"".OLDALCIM."upload/",$Tartalom);

                        $Tartalom=str_replace("window.open('/","window.open('".OLDALCIM."",$Tartalom);

                        $Tartalom=str_replace("href='/upload","href='".OLDALCIM."upload",$Tartalom);
                        $Tartalom=str_replace('href="/upload','href="'.OLDALCIM.'upload',$Tartalom);
                        $Tartalom=str_replace("background=/images","background=".OLDALCIM."images",$Tartalom);
                        $Tartalom=str_replace("background='/images","background='".OLDALCIM."images",$Tartalom);
                        $Tartalom=str_replace("background=\"/images","background=\"".OLDALCIM."images",$Tartalom);
                        $Tartalom=str_replace("href='style","href='".OLDALCIM."style",$Tartalom);
                        $Tartalom=str_replace("href='?","href='".OLDALCIM."main.php?",$Tartalom);
                        $Tartalom=str_replace("window.open('?","window.open('".OLDALCIM."?",$Tartalom);
                        $Tartalom=str_replace("'/anim/","'".OLDALCIM."anim/",$Tartalom);


                return $Tartalom;

        }
        
        function Hirlevel($DATA)
        {
            return "<div class='wrapper'>
        <div class='container'>
            <div class='newsletter'>
                <form class='newsletter_form'>
                    <h5>Hírlevél</h5>
                    <label>
                        <input type='email' placeholder='Adja meg e-mail címét' />
                    </label>
                    <label>
                        <input type='text' placeholder='Adja meg a nevét' />
                    </label>
                    <input type='submit' value='Feliratkozás' />
                    <div class='qaptcha_holder'>
                        <div class='half'>
                            <p class='pt'>Az űrlap elküldéséhez kérem húzza át az alábbi kapcsolót balról jobbra.</p>
                            <p>A hírlevélről való leiratkozáshoz kattintson <a href='#'>ide</a></p>
                        </div>
                        <div class='half'><div id='newsletter-chapcha' class='qaptcha'></div></div>              
                    </div>
                </form>

                <script type='text/javascript'>
                $(document).ready(function(){

                    // More complex call
                    $('#newsletter-chapcha').QapTcha({
                        autoSubmit : false,
                        autoRevert : true,
                        PHPfile : '/php/qaptcha.jquery.php'
                    });
                });
                </script>
            </div>
        </div>
    </div>";
            $Hirlink=$DATA["Hirlevlink"];
            return " <script type='text/javascript'>
                function HirlevelEllenor()
                {
                        if (document.HirlevelForm.HIRLEVEL_EMAIL.value=='')
                        {
                                alert('@@@Az email mező nem lehet üres!§§§');
                                document.HirlevelForm.HIRLEVEL_EMAIL.focus();
                        }else return true;
                        return false;
                }
                </script>
                    <h3>
                        <i class='fa fa-envelope-open' aria-hidden='true'></i>
                        <span><strong>@@@Hírlevél§§§</strong><br>@@@Feliratkozás§§§</span>
                    </h3>
                    <br class='clear' />
                    <p>@@@Amennyiben szeretne értesűlni legújabb ajánlatainkról,<br>akcióinkról adja meg email címét és jelentkezzen hírlevelünkre.§§§</p>                    
                <form class='newsletter' method='post' name='HirlevelForm' id='HirlevelForm'>
                <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='$Hirlink'>
                <input type='hidden' name='Igazit' id='Igazit' value='Fel'>
                <input type='hidden' name='HIRLEVEL_NEV' id='HIRLEVEL_NEV' value=' '>

                        <input type='email' name='HIRLEVEL_EMAIL' id='HIRLEVEL_EMAIL' placeholder='@@@E-mail cím§§§' />
                        <input type='submit' value='@@@Feliratkozás§§§' onclick=\"document.HirlevelForm.Igazit.value='Fel';return HirlevelEllenor();\"  />
                        <input type='button' name='' value='@@@Leiratkozás§§§' onclick=\"document.HirlevelForm.Igazit.value='Le';if (HirlevelEllenor()){document.HirlevelForm.submit();} \" />
                    </form>
                    ";
        }
                
        function Social($Nyelv)
        {

            switch ($Nyelv)
            {
                case "HU":
                 $NYELV_IR="    <a class='langs pop-upper' title='English' style='cursor: pointer;' href='".DEF_PHP."?Lang=EN'>
    <!--<i class='margin-top-5 fa fa-language'></i>-->
    <span>EN</span>
    </a>
";
                break; 
                case "EN":
                 $NYELV_IR="    <a class='langs pop-upper' title='English' style='cursor: pointer;' href='".DEF_PHP."?Lang=HU'>
    <!--<i class='margin-top-5 fa fa-language'></i>-->
    <span>HU</span>
    </a>
";
                break; 
            }
            return "<div id='floatingSocialShare'>
<div class='top-left'>
    <a class='facebook pop-upper' title='Facebook - MAXCity Lakberendezési Áruház' href='https://www.facebook.com/maxcitytorokbalint' target='_blank'>
    <i class='margin-top-5 fa fa-facebook'></i>
    </a>
    <a class='pinterest pop-upper' title='Pintetest - MAXCity Lakberendezési Áruház' href='https://hu.pinterest.com/lakberendezsi/' target='_blank'>
    <i class='margin-top-5 fa fa-pinterest'></i>
    </a>

    <a class='instagram pop-upper' title='Instagram - MAXCity Lakberendezési Áruház' href='https://www.instagram.com/explore/locations/1007832924/' target='_blank'>
    <i class='margin-top-5 fa fa-instagram'></i>
    </a>
    $NYELV_IR    

    </div>
</div>
";
        }
        
        function Headadmin($DATA)
        {
            return "         <!doctype html>
<html lang='en'>

<head>
	<meta charset='utf-8'/>
	<title>".$DATA["Metatags"]["Title"]."</title>
	
	<link rel='stylesheet' href='/templ_admin/css/layout.css' type='text/css' media='screen' />
	<!--[if lt IE 9]>
	<link rel='stylesheet' href='/templ_admin/css/ie.css' type='text/css' media='screen' />
	<script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
	<![endif]-->
	<script src='/templ_admin/js/jquery-1.8.0.js' type='text/javascript'></script>
	<script src='/templ_admin/js/hideshow.js' type='text/javascript'></script>
	<script src='/templ_admin/js/jquery.tablesorter.min.js' type='text/javascript'></script>
  <script src='/templ_admin/js/jquery-ui.js'></script>
  <script src='/templ_admin/js/jquery-ui-timepicker-addon.js'></script>

<link rel=\"stylesheet\" href=\"/templ_admin/css/jquery-ui.css\" />
<link rel=\"stylesheet\" href=\"/templ_admin/css/jquery-ui-timepicker-addon.css\" />
	
    <script type='text/javascript' src='/templ_admin/js/jquery.equalHeight.js'></script>
	<script type='text/javascript'>
	$(document).ready(function() 
    	{ 
      	  $('.tablesorter').tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$('.tab_content').hide(); //Hide all content
	$('ul.tabs li:first').addClass('active').show(); //Activate first tab
	$('.tab_content:first').show(); //Show first tab content

	//On Click Event
	$('ul.tabs li').click(function() {

		$('ul.tabs li').removeClass('active'); //Remove any 'active' class
		$(this).addClass('active'); //Add 'active' class to selected tab
		$('.tab_content').hide(); //Hide all tab content

		var activeTab = $(this).find('a').attr('href'); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type='text/javascript'>
    $(function(){
        $('.column').equalHeight();
    });
</script>

 ".$DATA["Headba"]."
<script type='text/javascript'>
 $(document).ready(function() {
".$DATA["Scriptuzen"]."

})

           
".$DATA["Script"]."
</script>


</head>

                ";
            
        }
        
        function Chat()
        {
            return "";
            return "<!-- Smartsupp Live Chat script -->
<script type='text/javascript'>
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'b55df7152f8345e6739a92e9a91aea2982fe9b19';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>";
        }
        
        function Face_pixel()
        {
            return "<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2138612819724010'); 
fbq('track', 'PageView');
</script>
<noscript>
<img height=\"1\" width=\"1\" 
src=\"https://www.facebook.com/tr?id=2138612819724010&ev=PageView
&noscript=1\"/>
</noscript>
<!-- End Facebook Pixel Code -->
";
        }
        
        function Head($DATA)
        {          
            $KEP_IR="";
            $URL_IR=OLDALCIM;
            if (isset($GLOBALS["METAKEP"]))
            {
                $KEP_IR="<meta property='og:image' content='".$GLOBALS["METAKEP"]."' />";                
            }
            if (isset($GLOBALS["METAURL"]))$URL_IR=$GLOBALS["METAURL"];
            

            if ($this->Ujfelso())
            {
                $Stilus="<link href='/templ/css/style2018.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/media2018.css' rel='stylesheet' type='text/css'>
";
            }else
            {
                $Stilus="<link href='/templ/css/style.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/media.css' rel='stylesheet' type='text/css'>
";
            }
            
            return "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>".$DATA["Metatags"]["Title"]."</title>
<link rel='shortcut icon' href='/favicon.ico' type='image/x-icon' />
  <link rel='apple-touch-icon' href='/apple-touch-icon.png' />
  <link rel='apple-touch-icon' sizes='57x57' href='/apple-touch-icon-57x57.png' />
  <link rel='apple-touch-icon' sizes='72x72' href='/apple-touch-icon-72x72.png' />
  <link rel='apple-touch-icon' sizes='76x76' href='/apple-touch-icon-76x76.png' />
  <link rel='apple-touch-icon' sizes='114x114' href='/apple-touch-icon-114x114.png' />
  <link rel='apple-touch-icon' sizes='120x120' href='/apple-touch-icon-120x120.png' />
  <link rel='apple-touch-icon' sizes='144x144' href='/apple-touch-icon-144x144.png' />
  <link rel='apple-touch-icon' sizes='152x152' href='/apple-touch-icon-152x152.png' />
  <link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon-180x180.png' />
<meta name=\"cache-control\" content=\"Public\" />
<meta name=\"keywords\" content=\"".$DATA["Metatags"]["Key"]."\" />
<meta name=\"description\" content=\"".$DATA["Metatags"]["Desc"]."\" />


	<meta property='og:type' content='website' />    
	<meta property='og:title' content='".$DATA["Metatags"]["Title"]."' />
	<meta property='og:url' content='".$URL_IR."'/>
	<meta property='og:description' content='".$DATA["Metatags"]["Desc"]."' />
    $KEP_IR


<!-- Bootstrap -->
 
<link href='/templ/css/bootstrap.css' rel='stylesheet'>
<link href='/templ/css/slicknav.min.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/slick.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/slick-theme.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
<link href='/templ/css/listak_kosar_rendeles.css' rel='stylesheet' type='text/css'>
$Stilus


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
		  <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
		  <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
		<![endif]-->

<!--FOOTER END-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src='/templ/js/jquery-1.11.3.min.js'></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src='/templ/js/bootstrap.js'></script> 
<script type='text/javascript' src='/templ/js/jquery.slicknav.min.js'></script> 
<script type='text/javascript' src='/templ/js/slick.min.js'></script> 
<script type='text/javascript' src='/templ/js/ysolutions.js'></script>

<link href='/templ/js/ui/jquery-ui.min.css' rel='stylesheet' type='text/css'>

 
<script type='text/javascript' src='/templ/js/ui/jquery-ui.min.js'></script> 
<script type='text/javascript' src='/templ/js/jquery-ui-timepicker-addon.js'></script> 


        <script type='text/javascript' src='/templ/js/fancybox/jquery.fancybox.min.js'></script>
        <link rel='stylesheet' type='text/css' href='/templ/js/fancybox/jquery.fancybox.min.css' media='screen' />
        
             ".$DATA["Headba"]."
<script type='text/javascript'>
 $(document).ready(function() {
".$DATA["Scriptuzen"]."

})
            
".$DATA["Script"]."
     </script>    
               ".$this->Chat()."
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-109992633-1\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109992633-1');
</script>
[[[sablon Face_pixel]]]
</head>
";
 
//                $Oldal=str_replace("alert(","sweetAlert(",$Oldal);
                return $Oldal;
            
        }
        
  
        
        function _SablonMenuMunkaTeruletre($DATA)
        {
           
            $Vissza="";
            if ($DATA)
            {
                $Vissza="<table>";
                foreach ($DATA as $egy)
                {
                    $Vissza.="
                              <tr align='center'>
                              <td><img src='/images/arrow.gif' border=0></td>
                <td height='17' ><a href='".$egy["LINK"]."' class='link'>".$egy["NEV"]."</a></td>
                        </tr>";
                }
                $Vissza.="</table>";
            }
            return $Vissza;
        }
  
 
 
   
        
        function Oldal_ures($Kozep)
        {
            return "
[[[sablon Head]]]
<body>      
".$this->Facelogin()."      
$Kozep
<br>
[[[sablon Google_remark]]]
[[[sablon Oldalvege]]]            
            ";            
        }
        

        
        
 /**
 * Oldal_admin - admin oldal 
 */        
        
        function Oldal_admin($Data)
        {
            $Data["Admin"]=1;
            return $this->Oldal($Data);
            
            if (!(isset($Data["Cim"])))$Data["Cim"]="";
            if (!(isset($Data["Tartalom"])))$Data["Tartalom"]="";
            $Viszalink="";
            if (isset($Data["Vissza"]))$Viszalink="<br>".$this->Gombcsinal("Vissza","location.href='".$Data["Vissza"]."'");
            
            
            $Vissza="[[[sablon Headadmin]]]
           <body> 
	<header id='header'>
		<hgroup>
			<h1 class='site_title'><a href='/main.php'>Website Admin</a></h1>
			<h2 class='section_title'>Dashboard</h2><div class='btn_view_site'> </div>
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id='secondary_bar'>
		<div class='user'>
			<p>ADMINNEV</p>
			
		</div>
		<div class='breadcrumbs_container'>
			<article class='breadcrumbs'><a href='/admin'>Website Admin</a> </article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id='sidebar' class='column'>
		<hr/>
            [[[sablon Adminbelep]]]
		
		<footer>
			<hr />
			<p><strong>@@@Copyright§§§ &copy; @@@2016 YSolutions§§§</strong></p>
			<p></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id='main' class='column'>

                <!--munkaterulet-->
		<article class='module width_full'>
		<header><h3 class='tabs_involved'>".$Data["Cim"]."</h3>
		</header>
		<div class='message'><p>".$Data["Tartalom"]."</p></div>
$Viszalink
		
		</article><!-- end of content manager article -->


		
		
		<!-- end of styles article -->
		<div class='spacer'></div>
	</section>
   [[[sablon Oldalvege]]]
    ";
            return $Vissza;
            
         }

        function Adminbelep($DATA)
        {
            $Vissza="";
            if (isset($DATA["Menu"]))
            {
                    $Vissza="";
               // $Vissza="<br>";
                foreach ($DATA["Menu"] as $egymenu)
                {
                    $Vissza.="<a href='".$egymenu["Link"]."'>".$egymenu["Nev"]."</a>                    
                     ";
                }
                $Vissza.="";
            }
            if ($Vissza!="")$Vissza="$Vissza ";
            return $Vissza;
                        
        }
        
        function Menujablak($Data)
        {
            $Vissza="";
            
            if (isset($Data["Ujablak"]))
            {
                if ($Data["Ujablak"])$Vissza="target='_blank' ";
            }
            return $Vissza;
        }
               
        
        function Kapcsolat()
        {
            return "<div class='bekero'>
          <div class='csibefalat'></div>
          <h3 class='sajatfont text-center'>Kapcsolat </h3>
          <form class='form-horizontal'>
            <div class='form-group m-b-10'>
              <div class='col-sm-offset-2 col-sm-10 forduljon_hozzank'> Kérdéseivel, véleményével forduljon hozzánk bizalommal! </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-sm-2' for='email'>Név:</label>
              <div class='col-sm-10'>
                <input type='text' class='form-control' id='nev'>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-sm-2' for='email'>Elérhetőség:</label>
              <div class='col-sm-10'>
                <input type='text' class='form-control' id='elerh'>
              </div>
            </div>
            <div class='form-group m-b-32'>
              <label class='control-label col-sm-2' for='pwd'>Üzenet:</label>
              <div class='col-sm-10'>
                <textarea class='form-control' rows='5' id='comment'></textarea>
              </div>
            </div>
            <div class='form-group'>
              <div class='col-sm-offset-2 col-sm-10'>
                <button type='submit' class='btn btn-default'>ÜZENET ELKÜLDÉSE</button>
              </div>
            </div>
          </form>
        </div>";
        
        }
        
        function Felsomenu()
        {
            return "<div class='wrapper wrapper_submenu' id='menu-shop-submenu'>
        <div class='container'>
            <div class='col'>
                <h3>Fegyver</h3>
                <ul>
                    <li><a href='#' >Golyós</a></li>
                    <li><a href='#'>Sörétes</a></li>
                    <li><a href='#'>Kiskaliberű</a></li>
                    <li><a href='#'>Duplagolyós</a></li>
                    <li><a href='#'>Vegyescsövű</a></li>
                    <li><a href='#'>Drilling</a></li>
                    <li><a href='#'>Maroklőfegyver</a></li>
                    <li><a href='#'>Használt fegyver</a></li>


                </ul>
            </div>
            <div class='col'>
                <h3>Lőszer</h3>
                <ul>
                    <li><a href='#'>Részletfizetés</a></li>
                    <li><a href='#'>Háztól- házig</a></li>
                    <li><a href='#'>Szerviz</a></li>
                </ul>
            </div>

            <div class='col'>
                <h3>Optika</h3>
                <ul>
                    <li><a href='#'>Keresőtávcső</a></li>
                    <li><a href='#'>Céltávcső</a></li>
                    <li><a href='#'>Red dot</a></li>
                    <li><a href='#'>Éjjellátó</a></li>
                    <li><a href='#'>Hőkamera</a></li>
                    <li><a href='#'>Spektív</a></li>
                    <li><a href='#'>Távolságmérő</a></li>
                    <li><a href='#'>Vadkamera</a></li>
                    <li><a href='#'>Szerelék</a></li>
                    <li><a href='#'>Kiegészítők</a></li>
                </ul>
            </div>

            <div class='col'>
                <h3>Felszerelés</h3>
                <ul>
                    <li><a href='#'>Fegyvertárolás</a></li>
                    <li><a href='#'>Fegyvertok</a></li>
                    <li><a href='#'>Hátizsák / táska</a></li>
                    <li><a href='#'>Lámpa</a></li>
                    <li><a href='#'>Vadhívás</a></li>
                    <li><a href='#'>Csapdák / csalianyagok</a></li>
                    <li><a href='#'>Fegyverápolás</a></li>
                    <li><a href='#'>Védőfelszerelés</a></li>
                    <li><a href='#'>Hasznos dolgok</a></li>
                </ul>
            </div>


            <div class='col'>
                <h3>Ruha</h3>
                <ul>
                    <li><a href='#'>Kabát</a></li>
                    <li><a href='#'>Nadrág</a></li>
                    <li><a href='#'>Mellény</a></li>
                    <li><a href='#'>Pulóver / polár</a></li>
                    <li><a href='#'>Ing / póló</a></li>
                    <li><a href='#'>Alsóruházat</a></li>
                    <li><a href='#'>Sapka / sál / kesztyű / zokni</a></li>
                    <li><a href='#'>Fűthető ruházat</a></li>
                    <li><a href='#'>Lesruházat</a></li>
                    <li><a href='#'>Kiegészítők</a></li>
                </ul>
            </div>

            <div class='col'>
                <h3>Cipő</h3>
                <ul>
                    <li><a href='#'>Félcipő</a></li>
                    <li><a href='#'>Bakancs</a></li>
                    <li><a href='#'>Gumicsizma</a></li>
                    <li><a href='#'>Hótaposó</a></li>
                    <li><a href='#'>Kiegészítők</a></li>
                </ul>
            </div>




        </div>
    </div>";
        }
        
            
   

        function Korvalaszto($Data)
        {
            $Vissza="";
            
            if ($Data["Kell"])
            {
                $Vissza="<div class='korvalaszto_bg'>
	<div class='korvalaszto_popup'>
	
	<div class='korvalaszto_popup_tartalom'>
		
		Az oldalon különleges,  kézműves söröket forgalmazunk a világ minden tájáról. Skót, dán, holland, amerikai, észt és skót főzdék különlegességei egy helyen. <br>
		Rendeld meg ma és holnap már kóstolhatod!
<br>
</div>
<div class='korvalaszto_popup_title'>ELMÚLTÁL 18 ÉVES?</div>
<div class='korvalaszto_btns'>
	<a class='btn-block korellenorzes' href=\"javascript:$('#tmpsp').load('/fo-Korvalasz_pb_fut.html');void(0);\" tabindex='-1'>IGEN</a>

		<a class='btn-block korellenorzes' href='http://google.com' tabindex='-1'>NEM</a>	
		<!--<a href='https://www.bestofbudapest.com/szavazas' target='_blank' style='margin-top: 15px'><img class='img-responsive' src='templ/images/best-of-budapest-2019.jpg' /></a>-->
</div>
	
	
	</div>
</div><span id='tmpsp' style=\"display:none\"></span>";
            }
            return $Vissza;
        }
    
        function Belepes($DATA)
        {
            
            
            $Stil="";            
            if ((isset($DATA["Belepve"]))&&($DATA["Belepve"]=="1"))
            {
                $MENU_IR="";
                $ind=0;
                if (isset($DATA["Menu"]))
                {

                    foreach ($DATA["Menu"] as $egymenu)
                    {
                        $MENU_IR.="<a href='".$egymenu["Link"]."' class='arrow'>".$egymenu["Nev"]."</a> ";
                        
                        $ind++;
                    }
                }
                              
                return "<a class='button login' id='login'><i class='fa fa-unlock' aria-hidden='true'></i><br>Belépve</a>

                    <div id='top-login-box'>
                        <a id='popclose'>X</a>
                        $MENU_IR
                    </div>
                    
                    ";
            }else
            {
                return "            <div class='content-main txt-main'>
  <div class='row bejelentkezes'>
    <div class='col-sm-6 box'>
      <h2>BEJELENTKEZÉS</h2>
      <div class='box_keretes'>
        
         <form method='post' action='".$DATA["Beleplink"]."' name='Belepform' id='Belepform'>
          <div class='form-group'>
            <label for='LOGIN'>@@@Felhasználónév vagy e-mail cím§§§ <span class='required'>*</span></label>
            <input id='LOGIN' name='LOGIN' class='form-control'  type='text'>
          </div>
          <div class='form-group'>
            <label for='JELSZO'>@@@Jelszó§§§<span class='required'>*</span></label>
            <input id='JELSZO' name='JELSZO' class='form-control'  type='password'>
          </div>
          <div class='form-group gombok'>
            <input type='submit' class='butt_kosarba formbutt' value='BEJELENTKEZÉS'>
            <label for='EMLEKEZZ'>
              <input id='EMLEKEZZ' name='EMLEKEZZ' value='on' type='checkbox'>
              @@@Emlékezz rám§§§ </label>
          </div>
          <p><a href='".$DATA["Jellink"]."'>Elfelejtett jelszó?</a></p>
          ".$this->Facelogin_butt()."
        </form>
      </div>
    </div>
    <div class='col-sm-6 box'>
      <h2>REGISZTRÁCIÓ</h2>
      <div class='box_keretes'>
        <form action='?' method='post' name='Regform' id='Regform'>
        <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='felhcsoport-Regisztral_pb_fut'>
                
          <div class='form-group'>
            <label for='LOGIN'>@@@Felhasználónév§§§<span class='required'>*</span></label>
            <input id='LOGIN' name='LOGIN' class='form-control'  type='text'>
          </div>
          <div class='form-group'>
            <label for='EMAIL'>@@@E-mail cím§§§ <span class='required'>*</span></label>
            <input id='EMAIL' name='EMAIL' class='form-control'  type='email'>
          </div>
          <div class='form-group'>
            <label for='JELSZO'>@@@Jelszó§§§<span class='required'>*</span></label>
            <input id='JELSZO' name='JELSZO' class='form-control'  type='password'>
          </div>
          <div class='form-group gombok'>
            <input type='submit' class='butt_kosarba formbutt' value='@@@REGISZTRÁCIÓ§§§' onclick=\"return REllenor();\">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function REllenor()
{
    if (document.Regform.JELSZO.value=='')
    {
        alert('@@@A jelszó nem lehet üres!§§§');
    }else return true;
    return false;
}
</script>";


                
                
            }
        }
        
          function Termek_menu($DATA)
        {
            $MENU_IR="";
            $Hanyas=0;
            foreach ($DATA["Menu"] as $egymenu)
            {
                $MENU_IR.="<li>
                ";
                if (isset($egymenu["Menu"]))
                {
                    $Stil="";
                    if ($egymenu["Aktiv"])$Stil=" current";
                    $MENU_IR.="<a class='submenu_link".$Stil."' id='menu$Hanyas'><span class='ico'>&nbsp;</span>".$egymenu["Nev"]."</a>";
                    
                    $MENU_IR.="<ul class='subsub$Stil' id='menu".$Hanyas."-submenu'>
                    ";
                    foreach ($egymenu["Menu"] as $egyalmenu)
                    {
                        $Stil="";
                        if ($egymenu["Aktiv"])$Stil="class='active'";

                        $MENU_IR.="<li><a href='".$egyalmenu["Link"]."' $Stil><span class='ico'>&nbsp;</span>".$egyalmenu["Nev"]."</a></li>";
                    }
                    $MENU_IR.="</ul>";
                }else
                {

                    $Stil="";
                    if ($egymenu["Aktiv"])$Stil="class='current'";
                    $MENU_IR.="<a href='".$egymenu["Link"]."' $Stil><span class='ico'>&nbsp;</span>".$egymenu["Nev"]."</a>";    
                }
                $MENU_IR.="</li>";
                $Hanyas++;
            }
            return "<div class='left_menu'>
                    <ul class='left_submenu'>
                        $MENU_IR
                    </ul>
                    <hr />
                    <hr />
                </div>";            
            return "<div class='left_menu'>
                    <ul class='left_submenu'>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Akciós termékek</a></li>
                        <li><a class='submenu_link' id='teljesitmenynoveles'><span class='ico'>&nbsp;</span>Teljesítménynövelés</a>
                            <ul class='subsub' id='teljesitmenynoveles-submenu'>
                                <li><a href='#'><span class='ico'>&nbsp;</span>CoreControl</a></li>
                                <li><a href='#'><span class='ico'>&nbsp;</span>Titin</a></li>
                                <li><a href='#'><span class='ico'>&nbsp;</span>SKLZ</a></li>
                                <li><a href='#'><span class='ico'>&nbsp;</span>booost</a></li>
                                <li><a href='#'><span class='ico'>&nbsp;</span>Beet It</a></li>
                                <li><a href='#'><span class='ico'>&nbsp;</span>O2 bár</a></li>
                                                            </ul>
                        </li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Rehabilitáció</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Diagnosztikai eszközök</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Labdák</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Mezek</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Mez nadrágok</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Melegítők</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Egyéb csapatruházat</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Cipők</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Védőfelszerelések</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Táskák</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Pályatartozékok</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Táplálék kiegészítők</a></li>
                        <li><a href='#'><span class='ico'>&nbsp;</span>Fitness</a></li>
                    </ul>
                    <hr />
                    <hr />
                </div>";
        }
        
        function Mailberendeles($PARAM)
        {
            return "    <!doctype html>
<html>
<head>
<meta charset='utf-8'>
<title>Untitled Document</title>
</head>

<body style='padding: 0; margin: 0; font-family:Arial, Helvetica, sans-serif; font-size: 12px;'>
<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td style='border: 1px solid #000;' ><table width='100%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><a href='".OLDALCIM."'><img src='".OLDALCIM."templ/images/beerside-logo_new600.png' width='100%' alt=''/></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  style='padding-left: 10px; padding-right: 10px'>".$PARAM["Mailbe"]."
          
       </td>
      </tr>
      <tr>
        <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td height='30'>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor='#000'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td width='171'><a href='#'></a></td>
                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style='text-align: right; color: #FFF; font-size: 12px;'>K&ouml;sz&ouml;nj&uuml;k, hogy minket v&aacute;lasztott!</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style='text-align: right; color: #FFFFFF; font-size: 12px;'><a href='".OLDALCIM."' style='text-decoration: none; color: #FFFFFF;'>www.beerside.hu</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                    </table></td>
                    <td width='20'>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>

";
 
        }
                

        
        function Nav_menseged($Data,$Ind,$Felul=false)
        {
            $Vissza="";
                    $NEV=$Data["Nev"];
                    $LINK=$Data["Link"];
            if (isset($Data["Almenuk"]))$Menuk=$Data["Almenuk"];
                    else $Menuk=false;
            if (is_array($Menuk))$Db=count($Menuk);
                else $Db=0;
                $Target="";
                if ((isset($Data["Ujablak"]))&&($Data["Ujablak"]=="1"))
                {
                    $Target="target='_blank'";
                }


            if ($Db>0)
            {
                                
                $Vissza.="<li><a href='$LINK' $Target>$NEV</a>
                ";
                $Stil="";
                if ($Felul)$Stil=" class='submenu'";
                
                $Almenu="    <ul $Stil>

                ";

                for ($c=0;$c<$Db;$c++)
                {
                    $Almenu.="<li><a href='".$Menuk[$c]["Link"]."'>".$Menuk[$c]["Nev"]."</a>
";    
                    //$Almenu.=$this->Felso_layeresmenu($Menuk[$c]);
                    $Almenu.="</li>";
                }

                $Almenu.="</ul>
                ";
                $Vissza.="$Almenu
                </li>";
            }else
            {
                $Alir="";
//                if ((isset($Data["Aldb"]))&&($Data["Aldb"]>0))$Alir="<span class='has_sub'></span>";


                $Vissza.="<li><a href='$LINK' $Target>$NEV $Alir</a></li>                
                ";
            }                
            
            return $Vissza;
        }          
                
        function Navmenu($DATA)
        {
            return "<nav id='mobile-menu'>
<ul>
    <li><a href='index.php'>Főoldal</a></li>   
    <li class='pinned'><a href='?page=webshop-kategoria'>Webshop</a></li>
    <li><a href='#'>Lapszabászat</a></li>
    <li><a href='#'>Bútorkészítés</a></li>
    <li><a href='?page=kapcsolat'>Kapcsolat</a></li>  
</ul>
</nav>";
           if (isset($DATA["Menu"]))$Menu=$DATA["Menu"];
            else $Menu=false;
            
            $Menuir1="";
            $Almenuk="";
            if ($Menu)
            {
                $ind=0;
                foreach ($Menu as $egymenu)
                {
                    //if ($egymenu["Futotte"])
                    $Menuir1.=$this->Nav_menseged($egymenu,$ind);
                    $ind++;
                    
                }
            }
                        
            return "<nav id='mobile-menu'>
<ul>
<li><a href='".DEF_PHP."'>Főoldal</a></li>
  $Menuir1  
</ul>
</nav>";
                        
 
   }
        

    function Seged_kiem($Felirat,$Ertek)
    {
        if ($Ertek=="")return "";
        return "$Felirat $Ertek<br />";
    }        
    


        function inc_logo_big()
        {
            return "<div class='container-fluid'>
  <div class='logo_big'><img class='img-responsive center-block' src='/templ/images/beerside-logo_new.png' width='2000'  alt=''/></div>
</div>
";
        }
        
        function inc_fozdek()
        {
            return "<div class='fozdek'>
  <ul>
    <li><a href='/Phjala.html'><img src='/templ/images/logo-300x90.png' width='300' height='90' title='Põhjala' alt='Põhjala' /></a></li>
    <li><a href='/To_l.html'><img src='/templ/images/tool-96x300.png' width='96' height='300' title='To Øl' alt='To Øl'/></a></li>
    <li><a href='/De_Molen.html'><img src='/templ/images/DE-MOLEN-logo-278x300.png' width='278' height='300' title='De Molen' alt='De Molen'/></a></li>
    <li><a href='/Tempest.html'><img src='/templ/images/logo_tempest.png' width='408' height='95' title='Tempest' alt='Tempest' /></a></li>
    <li><a href='/Mikkeller.html'><img src='/templ/images/ClientLogos-Mikkeller.png' width='294' height='156' title='Mikkeller' alt='Mikkeller'/></a></li>
    <li><a href='/Omnipollo.html'><img src='/templ/images/Omnipollo.jpg'  title='Omnipollo' alt='Omnipollo'/></a></li>
    <li><a href='/Dugges.html'><img src='/templ/images/Dugges.png'  title='Dugges' alt='Dugges'/></a></li>
    <li><a href='/Tempel.html'><img src='/templ/images/tempel_logo_thetrue_black.jpg'  title='tempel_logo_thetrue_black' alt='tempel_logo_thetrue_black'/></a></li>
	 
    <li><a href='/Evil_TwinTwo_Roads.html'><img src='/templ/images/EvilTwin_logo.jpg' width='518' height='171' title='EvilTwin' alt='EvilTwin'/></a></li>
    <li><a href='/Flying_dog.html'><img src='/templ/images/flying-dog.jpg' width='604' height='405' title='Flying-dog' alt='Flying-dog' /></a></li>
	<li><a href='/Brekeriet.html'><img src='/templ/images/brekeriet_logo.png' width='736' height='108' title='Brekeriet' alt='Brekeriet'/></a></li>
    <li><a href='/Poppels.html'><img src='/templ/images/poppels-logo.png' width='921' height='346' title='Poppels-logo' alt='Poppels-logo'/></a></li>
    <li><a href='/Prairie.html'><img src='/templ/images/PrairieArtisanAlesLogo.png'  title='PrairieArtisanAles' alt='PrairieArtisanAles'/></a></li>
        
  </ul>
</div>
";
            return "<div class='fozdek'>
  <ul>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=582'><img src='/templ/images/logo-300x90.png' width='300' height='90' title='Põhjala' alt='Põhjala' /></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=584'><img src='/templ/images/tool-96x300.png' width='96' height='300' title='To Øl' alt='To Øl'/></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=583'><img src='/templ/images/DE-MOLEN-logo-278x300.png' width='278' height='300' title='De Molen' alt='De Molen'/></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=586'><img src='/templ/images/logo_tempest.png' width='408' height='95' title='Tempest' alt='Tempest' /></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=585'><img src='/templ/images/ClientLogos-Mikkeller.png' width='294' height='156' title='Mikkeller' alt='Mikkeller'/></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=588'><img src='/templ/images/Omnipollo.jpg'  title='Omnipollo' alt='Omnipollo'/></a></li>
    <li><a href='/SHOP.html?Honnan=0&K_FOZDE=6035'><img src='/templ/images/Dugges.png'  title='Dugges' alt='Dugges'/></a></li>
<li><a href='#'><img src='/templ/images/tempel_logo_thetrue_black.jpg'  title='tempel_logo_thetrue_black' alt='tempel_logo_thetrue_black'/></a></li>
	 
    <li><a href='#'><img src='/templ/images/EvilTwin_logo.jpg' width='518' height='171' title='EvilTwin' alt='EvilTwin'/></a></li>
    <li><a href='#'><img src='/templ/images/flying-dog.jpg' width='604' height='405' title='Flying-dog' alt='Flying-dog' /></a></li>
	<li><a href='#'><img src='/templ/images/brekeriet_logo.png' width='736' height='108' title='Brekeriet' alt='Brekeriet'/></a></li>
    <li><a href='#'><img src='/templ/images/poppels-logo.png' width='921' height='346' title='Poppels-logo' alt='Poppels-logo'/></a></li>
    <li><a href='#'><img src='/templ/images/PrairieArtisanAlesLogo.png'  title='PrairieArtisanAles' alt='PrairieArtisanAles'/></a></li>
        
  </ul>
</div>
";
        }
        
        function inc_elv_jel()
        {
            return "<div class='svg_divider text-center'>
  <svg width='40px' height='7px' viewBox='0 0 40 7' version='1.1' xmlns='http://www.w3.org/2000/svg'>
    <path d=' M 0.00 0.00 L 0.44 0.00 C 2.63 1.84 4.60 3.92 6.59 5.98 C 8.44 3.96 10.37 2.02 12.21 0.00 L 13.67 0.00 C 15.80 1.90 17.78 3.96 19.79 5.99 C 21.65 3.98 23.54 2.01 25.41 0.00 L 26.87 0.00 C 28.98 1.95 30.98 4.01 33.03 6.02 C 34.91 4.03 36.75 2.00 38.62 0.00 L 40.00 0.00 L 40.00 0.49 C 38.09 2.77 35.91 4.81 33.90 7.00 L 32.25 7.00 C 30.19 5.14 28.26 3.14 26.30 1.17 C 24.44 3.10 22.60 5.05 20.76 7.00 L 17.86 7.00 L 19.01 6.99 C 17.01 5.05 15.02 3.11 13.05 1.15 C 11.24 3.11 9.41 5.04 7.60 7.00 L 5.81 7.00 C 3.83 5.18 1.93 3.28 0.00 1.42 L 0.00 0.00 Z'></path>
  </svg>
</div>
";
        }
        
        function inc_slider($Data)
        {
            return "<div class='row slider_mosterkezett'>".$Data["Html"]."</div>";
            
            return "<div class='row slider_mosterkezett'>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/must-kuld-101x300.png' width='101' height='300' alt=''/></span>
    <h3 class='termekcim'>PÕHJALA MUST KULD 7,8%, PORTER</h3>
    <span class='termekar'>1200 Ft</span> </a>
    <div class='kosargombok'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/virmalised-101x300.png' width='101' height='300' alt=''/></span>
    <h3 class='termekcim'>PÕHJALA VIRMALISED IPA 6,5%,  IPA</h3>
    <span class='termekar'>1250 Ft</span> </a>
    <div class='kosargombok active'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/rukkiraak-101x300.png' width='101' height='300' alt=''/></span>
    <h3 class='termekcim'>PÕHJALA RUKKIRÄÄK 5,9%, RYE ALE</h3>
    <span class='termekar'>1150 Ft</span> </a>
    <div class='kosargombok'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/must-kuld-colombia-101x300.png' width='101' height='300' alt=''/></span>
    <h3 class='termekcim'>PÕHJALA MUST KULD COLOMBIA 7.8% COFFEE PORTER</h3>
    <span class='termekar'>1350 Ft</span> </a>
    <div class='kosargombok'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/7399-17397-thickbox-300x300.png' width='300' height='300' alt=''/></span>
    <h3 class='termekcim'>NORTH COAST LE MERLE SAISON 7,9% SAISON</h3>
    <span class='termekar'>1900 Ft</span> </a>
    <div class='kosargombok active'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/oceanside-dude-double-ipa-300x300.jpg' width='300' height='300' alt=''/></span>
    <h3 class='termekcim'>OCEANSIDE DUDE 9,4% IMPERIAL IPA</h3>
    <span class='termekar'>3100 Ft</span> </a>
    <div class='kosargombok'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/beer_319161-79x300.jpg' width='79' height='300' alt=''/></span>
    <h3 class='termekcim'>BASE CAMP PILGRIMAGE SAISON 7,1% SAISON</h3>
    <span class='termekar'>2500 Ft</span> </a>
    <div class='kosargombok'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
  <div class='col-xs-3 termek'> <a class='butt_reszletes' href='#'> <span class='termekkep'><img src='/templ/images/Mikkeller-Drinkin-Barely-Berliner-1-214x300.jpg' width='214' height='300' alt=''/></span>
    <h3 class='termekcim'>MIKKELLER DRINK’IN BARELY BERLINER 0,1%, NON ABV BERLINER WEISSE</h3>
    <span class='termekar'>1150 Ft</span> </a>
    <div class='kosargombok active'> <a class='butt_kosarba' href='#'>KOSÁRBA</a> <a class='butt_kosarba megtekint' href='#'>KOSÁR MEGTEKINTÉSE</a> </div>
  </div>
</div>
";
        }
        
        function Nyito_mosterk($Data)
        {
            $SZOV_IR="";
            if ($Data["Nyitoszov"]["Szoveg"]!="")
            {
                $SZOV_IR=$Data["Nyitoszov"]["Szoveg"];
            }
            return "
  <div class='cimsor'>$SZOV_IR
    <h1 class='text-center'>@@@MOST ÉRKEZETT§§§</h1>
    <span class='subtitle text-center'>@@@A LEGFRISEBB SÖRÖK§§§</span>
    <!-- ELVALASZTÓ JEL-->
    [[[sablon inc_elv_jel]]]	
   <!--ELVÁLASZTÓ JEL END-->
  </div>
<!--SLIDER-->
 [[[sablon inc_slider]]]
 	
<!--SLIDER END-->
  <div class='text-center teljes_valasztek'><a class='btn-lg btn btn-inv btn-success' href='".$Data["Shoplink"]."'>@@@NÉZD MEG A TELJES VÁLASZTÉKOT§§§</a></div>
  <div class='cimsor'>
    <h1 class='text-center'>@@@FŐZDÉK§§§</h1>
   <!-- ELVALASZTÓ JEL-->
   [[[sablon inc_elv_jel]]]	
   <!--ELVÁLASZTÓ JEL END-->
  </div>
 ";
        }
        
        function Nyito_partnerek($Data)
        {
            $Vissza="";
            foreach ($Data["Partn"] as $egy)
            {
                $Nev=$egy["Nev"];
                if ($egy["Kep"]=="")$Kep=$Nev;
                            else $Kep="<img alt='$Nev' title='$Nev' class='vc_single_image-img attachment-medium' src='".$egy["Kep"]."'>";
                
                $Vissza.="<li><a href='".$egy["Link"]."'>$Kep</a></li>
                ";    
            }
                    if (isset($_GET["TT"]))var_dump($Vissza);
            
            return " <div class='cimsor'>
    <h2 class='text-center'>@@@PARTNEREK§§§</h2>
   <!-- ELVALASZTÓ JEL-->
   <div class='svg_divider text-center'>
  <svg width='40px' height='7px' viewBox='0 0 40 7' version='1.1' xmlns='http://www.w3.org/2000/svg'>
    <path d=' M 0.00 0.00 L 0.44 0.00 C 2.63 1.84 4.60 3.92 6.59 5.98 C 8.44 3.96 10.37 2.02 12.21 0.00 L 13.67 0.00 C 15.80 1.90 17.78 3.96 19.79 5.99 C 21.65 3.98 23.54 2.01 25.41 0.00 L 26.87 0.00 C 28.98 1.95 30.98 4.01 33.03 6.02 C 34.91 4.03 36.75 2.00 38.62 0.00 L 40.00 0.00 L 40.00 0.49 C 38.09 2.77 35.91 4.81 33.90 7.00 L 32.25 7.00 C 30.19 5.14 28.26 3.14 26.30 1.17 C 24.44 3.10 22.60 5.05 20.76 7.00 L 17.86 7.00 L 19.01 6.99 C 17.01 5.05 15.02 3.11 13.05 1.15 C 11.24 3.11 9.41 5.04 7.60 7.00 L 5.81 7.00 C 3.83 5.18 1.93 3.28 0.00 1.42 L 0.00 0.00 Z'></path>
  </svg>
</div>
	
   <!--ELVÁLASZTÓ JEL END-->
  </div>
  <div class='fozdek partnerek'>
  <ul>
  $Vissza
  </ul>
  </div>";
        }
     
        function Oldal_nyito($Data)
        {

          
            
           return "[[[sablon Head]]]
<body class='nyitoold'>
".$this->Facelogin()."

[[[sablon inc_search]]]
[[[sablon inc_pagetop]]]
[[[sablon inc_header]]]
[[[sablon inc_logo_big]]]

<div class='container'>
[[[sablon Nyito_mosterk]]]
[[[sablon inc_fozdek]]]

[[[sablon Nyito_partnerek]]]

</div> 



[[[sablon inc_footer]]]

[[[sablon Google_remark]]]
[[[sablon Korvalaszto]]]
</body>
</html>

";
    
            return $Vissza;

/*
 <div class='footer_links'>
                <div class='thrid'>
                    <h3>Közbeszerzések</h3>
                    <ul class='footer_menu'>
                        <li><a href='#'>Ajánlattételi felhívások</a></li>
                        <li><a href='#'>Közbeszerzési terv</a></li>
                        <li><a href='#'>Közbeszerzési eljárások</a></li>
                        <li><a href='#'>Statisztikai összegzés</a></li>
                    </ul>
                </div>
                <div class='thrid'>
                    <h3>Álláslehetőség</h3>
                    <div class='footer_jobs'>
                        <div class='item'>
                            <div class='text'>
                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
                            </div>
                            <div class='date'><date>2016. november 10.</date></div>
                        </div>
                        <div class='item'>
                            <div class='text'>
                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
                            </div>
                            <div class='date'><date>2016. november 10.</date></div>
                        </div>
                    </div>
                </div>
                <div class='thrid'>
                    <h3>Bérbeadás</h3>
                    <div class='rental'>
                        <div class='item'>
                            <div class='image'><a href=''><img src='/templ/gfx/content/img_rental.jpg' /></a></div>
                            <div class='text'>
                                <h5><a href='#'>Terembérlés</a></h5>
                                <p><time>2014. február 03. (hétfő), 09:55:26</time></p>
                            </div>
                        </div>
                        <div class='item'>
                            <div class='image'><a href=''><img src='/templ/gfx/content/img_rental.jpg' /></a></div>
                            <div class='text'>
                                <h5><a href='#'>Raktár helyiségek BÉRBEADÓK!</a></h5>
                                <p><time>2014. február 03. (hétfő), 09:55:26</time></p>
                            </div>
                        </div>
                        <div class='item'>
                            <div class='image'><a href=''><img src='/templ/gfx/content/img_rental.jpg' /></a></div>
                            <div class='text'>
                                <h5><a href='#'>Üzlethelyiség kiadó</a></h5>
                                <p><time>2014. február 03. (hétfő), 09:55:26</time></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
*/
        }          
        
   
        function Navigalosor($DATA)
        {

            $Db=count($DATA["Menu"]);
            $ind=0;
            $Vissza="";
            
            for ($c=0;$c<$Db;$c++)
            {
                $egy=$DATA["Menu"][$c];
                $Link=$egy["Link"];
                $Vissza.=" | ";
                if ($c+1==$Db)
                {
                    $Vissza.=" <strong>".$egy["Nev"]."</strong>
                    ";
                }else
                {
                    $Vissza.="<a href='".$Link."' >".$egy["Nev"]."</a>
                    ";
                }
                
                $ind++;
            }
            if ($Vissza=="")return "";
            
            return "<div class='bread_crumbs'>
    <p>
        <a href='".DEF_PHP."'><i class='fa fa-home' aria-hidden='true'></i></a>   $Vissza  
    </p>
</div>
       
";                           
    
        }
        
        function Kosar($Dati)
        {
            $DATA=$Dati["Kosar"];
            
            if ($DATA["DB"]>0)
            {
            return "    <div id='cartBox' >
        <a href='".$DATA["Link"]."' gaec='Kosár' gaee='Kattintás' gael='Kosár gomb' class='ed_pghead_cart'>
            <div class='triangle'></div>
                <div class='imagebox'>
                    <div class='image'></div>
                    <div class='label'>Kosár</div>
                </div>
                <div class='contentBox'>
                    <div class='content'>
                        <span class='box' id='ed_pghead_minicart'>
                            A kosárba ".$DATA["DB"]." db termék.
                        <span class='showCart'>kosár megtekintése</span>
                    </span>
                </div>
            </div>
        </a>
    </div>";
            }
            return "    <div id='cartBox' >
        <a href='javascript:void(0);' gaec='Kosár' gaee='Kattintás' gael='Kosár gomb' class='ed_pghead_cart'>
            <div class='triangle'></div>
                <div class='imagebox'>
                    <div class='image'></div>
                    <div class='label'>Kosár</div>
                </div>
                <div class='contentBox'>
                    <div class='content'>
                        <span class='box' id='ed_pghead_minicart'>
                            A kosár üres.
                        <span class='showCart'>kosár megtekintése</span>
                    </span>
                </div>
            </div>
        </a>
    </div>";
        }
        
        function Kereso($Data)
        {
            return "    <div class='wrapper dark_wrapper'>
        <div class='container'>
            <form method='post' action='".$Data["Kereslink"]."'>
            <input type='hidden' name='Kerbol' id='Kerbol' value='1'>
                <div class='sliders'>
                    <div class='rooms'>
                        <p class='title'>Szobák száma:<span id='spSZOBA_TOL'>1</span> - <span id='spSZOBA_IG'>4</span></p>
                        <div id='sliderrange'></div>
                        <div class='scale quarter'>
                            <span class='unit' id='u1'>1</span>
                            <span class='unit' id='u2'>2</span>
                            <span class='unit' id='u3'>3</span>
                            <span class='unit' id='u4'>4</span>
                        </div>
                    </div>

                    <div class='size'>
                        <p class='title'>Méret: <span id='spMERET_TOL'>30</span> - <span id='spMERET_IG'>70</span><sup>2</sup></p>
                        <div id='sliderrange2'></div>
                        <div class='scale seventh'>
                            <span class='unit' id='u1'>30</span>
                            <span class='unit' id='u2'>35</span>
                            <span class='unit' id='u3'>40</span>
                            <span class='unit' id='u4'>45</span>
                            <span class='unit' id='u5'>50</span>
                            <span class='unit' id='u6'>55</span>
                            <span class='unit' id='u7'>60</span>
                            <span class='unit' id='u8'>65</span>
                            <span class='unit' id='u9'>70</span>
                        </div>
                    </div>
                    <div class='submit'>
                        <input type='submit' value='Keresés' />
                    </div>
                </div>
                 <input type='hidden' id='SZOBA_TOL' name='SZOBA_TOL' value='1' >
                  <input type='hidden' id='SZOBA_IG' name='SZOBA_IG'  value='4'>
                 <input type='hidden' id='MERET_TOL' name='MERET_TOL'  value='30'>
                  <input type='hidden' id='MERET_IG' name='MERET_IG'  value='90'>
                
            </form>


            <script type='text/javascript'>
                //Ez itt a csúszka
                 $( '#sliderrange' ).slider({
                    range: true,
                    min: 1,
                    max: 4,
                    values: [ 1, 4 ],
                    slide: function( event, ui ) {
                            $( '#SZOBA_TOL' ).val( '' + ui.values[ 0 ] );
                            $( '#SZOBA_IG' ).val( '' + ui.values[ 1 ] );
                            $('#spSZOBA_TOL').html(ui.values[ 0 ]);
                            $('#spSZOBA_IG').html(ui.values[ 1 ]);
                        }                    
                });

                 $( '#sliderrange2' ).slider({
                    range: true,
                    min: 30,
                    max: 70,
                    values: [ 30, 70 ],
                    slide: function( event, ui ) {
                            $( '#MERET_TOL' ).val( '' + ui.values[ 0 ] );
                            $( '#MERET_IG' ).val( '' + ui.values[ 1 ] );
                            $('#spMERET_TOL').html(ui.values[ 0 ]);
                            $('#spMERET_IG').html(ui.values[ 1 ]);
                        }                    
                    
                });

            </script>
        </div>
    </div>
";
        }
        
        function Termekajanlat($DATA)
        {
            return $DATA;
                    
            return "<div class='wrapper wrapper_pattern'>
    <div class='container'>
        <div class='section_title'>
            <h2>Termék ajánlatunk</h2>
        </div>

        <div class='products'>
            <div class='product_row'>
                <div class='item'>
                    <div class='layered_relativ'>
                        <div class='more_info'>
                            <div class='more_info_content'>
                                <div class='more_info_links'>
                                    <a href='' class='animated slideInDown'>Nagyítás</a><br>
                                    <a href='' class='gray animated slideInUp'>Kosárba</a>
                                </div>
                            </div>
                        </div>
                        <div class='image_holder'>
                            <a>
                                <img src='/templ/gfx/_temp/product.jpg' />
                            </a>
                        </div>
                        <div class='product_title'>
                            <h3>Purdey Double Rifle No. 30147</h3>
                            <h4>Double Rifle</h4>
                        </div>
                        <div class='product_lead'>
                            <p>A New Purdey 470 Double Rifle No. 30147 with Large Scroll Engraving by Simon Coggan. 24” (70cm) Barrels, Square Bar Action with Side Bolsters, Double Trigger, Bolted Safe, ¼ & Concave Rib, 14 5/8”(292 mm) Pistol Grip with Engraved Cap & Trap, Top Strap Extensions, Rifle Weight of 11 lbs 10 ½ oz (5.3kg). Gold Oval.</p>
                        </div>
                    </div>
                    <div class='product_placeholder'></div>
                    <div class='product_preis'>
                        <p>1 199 000 Ft <a href=''><i class='icofont icofont-listing-box'></i></a></p>

                    </div>
                </div>



                <div class='item'>
                    <div class='layered_relativ'>
                        <div class='more_info'>
                            <div class='more_info_content'>
                                <div class='more_info_links'>
                                    <a href='' class='animated slideInDown'>Nagyítás</a><br>
                                    <a href='' class='gray animated slideInUp'>Kosárba</a>
                                </div>
                            </div>
                        </div>
                        <div class='image_holder'>
                            <a>
                                <img src='/templ/gfx/_temp/product.jpg' />
                            </a>
                        </div>
                        <div class='product_title'>
                            <h3>Purdey Double Rifle No. 30147</h3>
                            <h4>Double Rifle</h4>
                        </div>
                        <div class='product_lead'>
                            <p>A New Purdey 470 Double Rifle No. 30147 with Large Scroll Engraving by Simon Coggan. 24” (70cm) Barrels, Square Bar Action with Side Bolsters, Double Trigger, Bolted Safe, ¼ & Concave Rib, 14 5/8”(292 mm) Pistol Grip with Engraved Cap & Trap, Top Strap Extensions, Rifle Weight of 11 lbs 10 ½ oz (5.3kg). Gold Oval.</p>
                        </div>
                    </div>
                    <div class='product_placeholder'></div>
                    <div class='product_preis'>
                        <p>1 199 000 Ft <a href=''><i class='icofont icofont-listing-box'></i></a></p>

                    </div>
                </div>

                <div class='item'>
                    <div class='layered_relativ'>
                        <div class='more_info'>
                            <div class='more_info_content'>
                                <div class='more_info_links'>
                                    <a href='' class='animated slideInDown'>Nagyítás</a><br>
                                    <a href='' class='gray animated slideInUp'>Kosárba</a>
                                </div>
                            </div>
                        </div>
                        <div class='image_holder'>
                            <a>
                                <img src='/templ/gfx/_temp/product.jpg' />
                            </a>
                        </div>
                        <div class='product_title'>
                            <h3>Purdey Double Rifle No. 30147</h3>
                            <h4>Double Rifle</h4>
                        </div>
                        <div class='product_lead'>
                            <p>A New Purdey 470 Double Rifle No. 30147 with Large Scroll Engraving by Simon Coggan. 24” (70cm) Barrels, Square Bar Action with Side Bolsters, Double Trigger, Bolted Safe, ¼ & Concave Rib, 14 5/8”(292 mm) Pistol Grip with Engraved Cap & Trap, Top Strap Extensions, Rifle Weight of 11 lbs 10 ½ oz (5.3kg). Gold Oval.</p>
                        </div>
                    </div>
                    <div class='product_placeholder'></div>
                    <div class='product_preis'>
                        <p>1 199 000 Ft <a href=''><i class='icofont icofont-listing-box'></i></a></p>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>";
        }
        
        function Kozepmenu($Data)
        {
            return "    <div class='wrapper navigation_wrapper'>
        <div class='container'>
            <nav id='top-nav'>
                <ul id='top-menu'>
                    <li class='pinned'><a href='".$Data["Menu1"]["Link"]."'>".$Data["Menu1"]["Nev"]."</a></li>
                    <li><a href='".$Data["Menu2"]["Link"]."'>".$Data["Menu2"]["Nev"]."</a></li>
                    <li><a href='".$Data["Menu3"]["Link"]."'>".$Data["Menu3"]["Nev"]."</a></li>
                    <li><a href='".$Data["Menu4"]["Link"]."'>".$Data["Menu4"]["Nev"]."</a></li>
                </ul>
            </nav>
        </div>
    </div>
";
        }
        
        function Kosar_felul($DATA)
        {
            if ($DATA["Kosdata"]["DB"]>0)
            {
                $KOS_IR="<a href='".$DATA["Kosdata"]["Link"]."'><i class='fa fa-shopping-cart'><span class='kosarbanelem'>".$DATA["Kosdata"]["DB"]."</span></i><span class='kosarosszeg'>".$this->ArFormaz($DATA["Kosdata"]["ERTEK"])."</span></a>";
            }else
            {
                $KOS_IR="<a href='javascript:void(0);'><i class='fa fa-shopping-cart'><span class='kosarbanelem'>0</span></i><span class='kosarosszeg'>0 Ft</span></a>";
            }
            return $KOS_IR;
        }
        
        function inc_header_uj_admin($DATA)
        {
            $DATA["Admin"]=1;
            return $this->inc_header_uj($DATA);
        }
        
        function inc_header_uj($DATA)
        {
            $MEN_IR="";
            if (isset($DATA["Menu"]))
            {
                foreach ($DATA["Menu"] as $item)
                {
                    $MEN_IR.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
                }
            }
            $Nyelv=$this->Nyelvadpub();
            $Nytag["HU"]="hu";
            $Nytag["EN"]="en";
            switch ($Nyelv)
            {
                case "HU":
                    $Nytag["HU"]="<strong>hu</strong>";
                break;
                case "EN":
                    $Nytag["EN"]="<strong>en</strong>";
                break;
            }
          //  $Nytag[$Nyelv]="class='active'";
            
            $Felh_menu="";
            foreach ($DATA["Bemenu"] as $item)
            {
                $Felh_menu.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
            }
            $Admst="";
            if (isset($DATA["Admin"])&&($DATA["Admin"]=="1"))$Admst=" admin";
            
            if (isset($DATA["Kedvlink"]))
            {
                $KLink=$DATA["Kedvlink"];
            }else
            {
                $KLink="javascript:alert('@@@Csak belépett felhasználóknak!§§§');void(0);";
            }    
            
            return "<header class='fejlec clearfix".$Admst."'>
  <div class='container-fluid'>
  <div class='row nav1'>
 <div class='col-xs-12'>
	<ul id='fomenu0'>
	
	$Felh_menu
    
    <li> <div class='fejlec_kosar' id='kosdiv'>[[[sablon Kosar_felul]]]</div></li>
	<li><a href=\"".$KLink."\" alt='@@@Kedvencek§§§' title='@@@Kedvencek§§§'><i class='fa fa-heart-o'><span class='kivansagbanelem'>".$DATA["Kedvdb"]."</span></i></a></li>
	
    <li><a href='".DEF_PHP."?Lang=HU' >".$Nytag["HU"]."</a> | <a href='".DEF_PHP."?Lang=EN' lass='aktnyelv'>".$Nytag["EN"]."</a></li>
	<li><a class='icon_facebook' href='https://www.facebook.com/beerside.hu' target='_blank'><i class='fa fa-facebook-square' aria-hidden='true'></i></a></li>
	<li><span class='keres_toggle'><i class='fa fa-search'></i>@@@KERESÉS§§§</span></li>
	</ul>
 </div>
  </div>
    <div class='row header'>
      <div class='col-xs-4 col-sm-2'>
        <div class='logo'><a href='".DEF_PHP."'><img class='img-responsive' src='/templ/images/beerside-logo_new.png' width='200' height='46' border='0' alt=''/></a></div>
      </div>
      <div class='col-xs-8 col-sm-8'>
       
        <div class='menu-kosar'>
          <div class='menu'>
            <ul id='fomenu'>
           $MEN_IR             
              
             
            </ul>
          </div>
         
        </div>
      </div>
	  <div class='col-sm-2'></div>
	  <div class='mobilmenu'></div>
    </div>
  </div>
</header>";

           return "<header class='fejlec clearfix'>
  <div class='container-fluid'>
    <div class='row header'>
      <div class='col-xs-4 col-sm-2'>
        <div class='logo'><a href='".DEF_PHP."'><img class='img-responsive' src='/templ/images/beerside-logo_new.png' width='200' height='46' border='0' alt=''/></a></div>
      </div>
      <div class='col-xs-8 col-sm-10'>
        <div class='nyelvvalto_box'> <a href='".DEF_PHP."?Lang=HU' >".$Nytag["HU"]."</a> | <a href='".DEF_PHP."?Lang=EN' lass='aktnyelv'>".$Nytag["EN"]."</a>
         <a class='icon_facebook' href='https://www.facebook.com/beerside.hu' target='_blank'><i class='fa fa-facebook-square' aria-hidden='true'></i>

</a>
             
            </div>      
        <div class='menu-kosar'>
          <div class='menu'>
            <ul id='fomenu'>
             $MEN_IR
              
              
              $Felh_menu
              <li><span class='keres_toggle'><i class='fa fa-search'></i>@@@KERESÉS§§§</span></li>
            </ul>
          </div>
          <div class='fejlec_kosar' id='kosdiv'>[[[sablon Kosar_felul]]]</div>
        </div>
      </div>
	  <div class='mobilmenu'></div>
    </div>
  </div>
</header>";
        }        
        
        function Ujfelso()
        {
            return true;
            if (isset($_GET["Teszt"]))return true;
            return false;
        }
        
        function inc_header($DATA)
        {
            if ($this->Ujfelso())return $this->inc_header_uj($DATA);
            $MEN_IR="";
            if (isset($DATA["Menu"]))
            {
                foreach ($DATA["Menu"] as $item)
                {
                    $MEN_IR.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
                }
            }
            $Nyelv=$this->Nyelvadpub();
            $Nytag["HU"]="hu";
            $Nytag["EN"]="en";
            switch ($Nyelv)
            {
                case "HU":
                    $Nytag["HU"]="<strong>hu</strong>";
                break;
                case "EN":
                    $Nytag["EN"]="<strong>de</strong>";
                break;
            }
          //  $Nytag[$Nyelv]="class='active'";
            
            $Felh_menu="";
            foreach ($DATA["Bemenu"] as $item)
            {
                $Felh_menu.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
            }
/*
 <div class='nyelvvalto_box'> <span class='aktnyelv'>$Nyelv</span>
              <ul class='nyelvvalto'>
                <li><a href='".DEF_PHP."?Lang=HU' ".$Nytag["HU"].">HU</a></li>
                <li><a href='".DEF_PHP."?Lang=EN' ".$Nytag["EN"].">EN</a></li>
              </ul>
            </div>   */            
            
            //<li><a href='https://www.facebook.com/beerside.hu' target='_blank'>FACEBOOK</a></li>
            return "<header class='fejlec clearfix'>
  <div class='container-fluid'>
    <div class='row header'>
      <div class='col-xs-4 col-sm-2'>
        <div class='logo'><a href='".DEF_PHP."'><img class='img-responsive' src='/templ/images/beerside-logo_new.png' width='200' height='46' border='0' alt=''/></a></div>
      </div>
      <div class='col-xs-8 col-sm-10'>
        <div class='nyelvvalto_box'> <a href='".DEF_PHP."?Lang=HU' >".$Nytag["HU"]."</a> | <a href='".DEF_PHP."?Lang=EN' lass='aktnyelv'>".$Nytag["EN"]."</a>
         <a class='icon_facebook' href='https://www.facebook.com/beerside.hu' target='_blank'><i class='fa fa-facebook-square' aria-hidden='true'></i>

</a>
             
            </div>      
        <div class='menu-kosar'>
          <div class='menu'>
            <ul id='fomenu'>
             $MEN_IR
              
              
              $Felh_menu
              <li><span class='keres_toggle'><i class='fa fa-search'></i>@@@KERESÉS§§§</span></li>
            </ul>
          </div>
          <div class='fejlec_kosar' id='kosdiv'>[[[sablon Kosar_felul]]]</div>
        </div>
      </div>
	  <div class='mobilmenu'></div>
    </div>
  </div>
</header>";
        }
        
        function inc_pagetop()
        {
            return "<a href='#' id='page_top'><i class='fa fa-chevron-up'></i></a>";
        }

        function inc_footer_seged($DATA)
        {
            $Vissza="      <div class='col-sm-6 col-md-3'>
        <div class='footer_menu'>
          <h4>".$DATA["Nev"]."</h4>
";
            if (isset($DATA["Almenuk"]))
            {
                $Vissza.=" <ul>
";
                foreach ($DATA["Almenuk"] as $item)
                {
                    $Vissza.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
                }
                $Vissza.=" </ul>
";
            }
            $Vissza.="</div>
            </div>";
            
            return $Vissza;
        }


        function inc_footer_seged2($DATA,$Kellcim=true)
        {
            $Vissza="";
            if ($Kellcim)
            {
                $Vissza="      
          <h4>".$DATA["Nev"]."</h4>
";
            }
            if (isset($DATA["Almenuk"]))
            {
                $Vissza.=" <ul>
";
                foreach ($DATA["Almenuk"] as $item)
                {
                    $Vissza.="<li><a href='".$item["Link"]."'>".$item["Nev"]."</a></li>
";
                }
                $Vissza.=" </ul>
";
            }
            
            return $Vissza;
        }
       
        function inc_footer2($DATA)
        {
            $MEN_IR[1]="";
            $MEN_IR[2]="";
            $MEN_IR[3]="";
            $MEN_IR_0_1="";
            $MEN_IR_0_2="";
            $NEV1="";
            if (isset($DATA["Menu"]))
            {
                $Ind=0;
                foreach ($DATA["Menu"] as $item)
                {
                    if ("$Ind"=="0")
                    {
                        $NEV1=$item["Nev"];
                        $Sor1["Almenuk"]=array();
                        $Sor2["Almenuk"]=array();
                        
                        if (isset($item["Almenuk"]))
                        {
                            $Ind2=0;
                            foreach ($item["Almenuk"] as $item2)
                            {
                                if ($Ind2<7)$Sor1["Almenuk"][]=$item2;
                                    else $Sor2["Almenuk"][]=$item2;
                                $Ind2++;
                            }    
                        }
                        $MEN_IR_0_1=$this->inc_footer_seged2($Sor1,false);
                        $MEN_IR_0_2=$this->inc_footer_seged2($Sor2,false);
                           
                    }else
                    {
                        $MEN_IR[$Ind]=$this->inc_footer_seged2($item);
                    }
                    $Ind++;
                }
            }
                          
            return "
<footer class='lablec'>
  <div class='container'>
    <div class='row'>
     
           <div class='col-xs-12 col-sm-6 col-md-4'>
        <div class='footer_menu'>
          <h4>$NEV1</h4>
 <div class='row'>
  <div class='col-sm-6'>
  $MEN_IR_0_1
  </div>
   <div class='col-sm-6'>
$MEN_IR_0_2
 
  </div>
 </div>
</div>
            </div>     
			<div class='col-xs-12 col-sm-3 col-md-4'>
        <div class='footer_menu'>
         ".$MEN_IR[1]."
</div>

            </div>
			 <div class='col-xs-12 col-sm-3 col-md-4'>
        <div class='footer_menu'>
          ".$MEN_IR[2]."
</div>
<img class='img-responsive logo_footer' src='/templ/images/beerside-logo_new.png' width='1000' height='230' alt=''/>
            </div>   
    </div>
  </div>
  <div class='container copyright'>
    <div class='row'>
      <div class='col-sm-5 text-left xs-text-center'>@@@Minden jog fent tartva.§§§ &copy; 2017 Beerside . </div>
      <div class='col-sm-7 text-right xs-text-center'><img class='img-responsive'  src='/templ/images/Barion-card-payment-banner-2016-432x50px.jpg' width='299' height='30' alt=''/></div>
    </div>
  </div>
</footer>";    
        }
        
        function inc_footer($DATA)
        {
            return $this->inc_footer2($DATA);
            $MEN_IR="";
            if (isset($DATA["Menu"]))
            {
                foreach ($DATA["Menu"] as $item)
                {
                    $MEN_IR.=$this->inc_footer_seged($item);
                }
            }
                        
            return "<footer class='lablec'>
  <div class='container'>
    <div class='row'>
      <div class='col-sm-6 col-md-3'><img class='img-responsive logo_footer' src='/templ/images/beerside-logo_new.png' width='1000' height='230' alt=''/></div>
     $MEN_IR
    </div>
  </div>
  <div class='container copyright'>
    <div class='row'>
      <div class='col-sm-5 text-left xs-text-center'>@@@Minden jog fent tartva.§§§ &copy; 2017 Beerside . </div>
      <div class='col-sm-7 text-right xs-text-center'><img class='img-responsive'  src='/templ/images/Barion-card-payment-banner-2016-432x50px.jpg' width='299' height='30' alt=''/></div>
    </div>
  </div>
</footer>

";
        }
        

        
        
       function Oldal_uni($Data)
        {
            
            if (!(isset($Data["Tartalom"])))$Data["Tartalom"]="";
            $Viszalink="";
            if (isset($Data["Vissza"]))
            {
                $Viszalink="<br class='cf'>".$this->Gombcsinal_v("@@@Vissza§§§","location.href='".$Data["Vissza"]."'","button");
            }
            


           return "[[[sablon Head]]]
<body class='belsoold'>
".$this->Facelogin()."
[[[sablon inc_search]]]
[[[sablon inc_pagetop]]]
[[[sablon inc_header]]]
<div class='container'>
    ".$Data["Tartalom"]."
    $Viszalink
</div>    

[[[sablon inc_footer]]]
[[[sablon Google_remark]]]
</body>
</html>

";
            return $Vissza;

        }
        
        function Facelogin_butt()
        {
          //  if (!(isset($_GET["TTT"])))return "";
            
        include(Face_incl);
        $fb = new Facebook\Facebook([
  'app_id' => Face_appid, // Replace {app-id} with your app id
  'app_secret' => Face_secret,
  'default_graph_version' => Face_version,
  ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(Face_backurl, $permissions);

        return "<a href='".htmlspecialchars($loginUrl)."'><img src='/templ/images/FBloginbutton.png' border='0' width=240 ></a>";
                     
        return "<input type='button' onclick=\"location.href='".htmlspecialchars($loginUrl)."';\" class='butt_kosarba formbutt' value='FACEBOOK BELÉPÉS'>";

        }
        
        function Facelogin()
        {
            return "";
            return "<script>
  window.fbAsyncInit = function() {
    
    FB.init({
      appId      : '913947108775443',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.12'
    });
      
    FB.AppEvents.logPageView();
    Facebelepe();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = \"https://connect.facebook.net/en_US/sdk.js\";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

function Facebelepe()
{   
    FB.getLoginStatus(function(response) {
    alert(response.status);
    alert(response.authResponse.accessToken);
    alert(response.authResponse.userID);
     
});
}
   
function checkLoginState() {
    alert('ch');
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}
   
</script>
";
        }
                    
       function Oldal($Data)
        {
            
            $Viszalink="";
            if (isset($Data["Vissza"]))
            {
                $Viszalink="<br class='cf'>".$this->Gombcsinal_v("Vissza","location.href='".$Data["Vissza"]."'","button");
            }
            
            
            if (!(isset($Data["Cim"])))$Data["Cim"]="";
            if (!(isset($Data["Tartalom"])))$Data["Tartalom"]="";
            if (!(isset($Data["Almenuk"])))$Data["Almenuk"]="";



            $Cim=$Data["Cim"];
            $Kozep=$this->inc_document($Data);
            
            if (isset($Data["Admin"])&&($Data["Admin"]=="1"))
            {
                $Felso="[[[sablon inc_header_uj_admin]]]";
            }else
            {
                $Felso="[[[sablon inc_header]]]";   
            }


           return "[[[sablon Head]]]
<body class='belsoold'>
".$this->Facelogin()."


[[[sablon inc_search]]]
[[[sablon inc_pagetop]]]
$Felso



<div class='container'>
$Kozep
$Viszalink

</div>


[[[sablon inc_footer]]]
[[[sablon Google_remark]]]
[[[sablon Korvalaszto]]]
</body>
</html>

";
            return $Vissza;

        }
        
        function Google_remark()
        {
            return "<!-- Google remarketingcímke-kód -->
<script type=\"text/javascript\">
/* <![CDATA[ */
var google_conversion_id = 846764962;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\">
</script>
<noscript>
<div style=\"display:inline;\">
<img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"\" src=\"//googleads.g.doubleclick.net/pagead/viewthroughconversion/846764962/?guid=ON&amp;script=0\"/>
</div>
</noscript>
";
        }
        
        function inc_document($Data)
        {
            if (isset($Data["Cim2"]))
            {
                return "<div class='container'>
                <div class='cimsor'>
  <h1 class='text-center'>".$Data["Cim"]."</h1>
  [[[sablon inc_elv_jel]]]  	
  ".$Data["Tartalom"]."
  ".$Data["Almenuk"]."


</div>
</div>
";
            }else
            {
            return "<div class='content-main txt-main'>
  <h1>".$Data["Cim"]."</h1>
  ".$Data["Tartalom"]."
  ".$Data["Almenuk"]."


</div>
";
            }
        }
        
        function inc_search($Data)
        {
            return "<div id='search'> 
    <span class='close'>X</span>
    <form role='search' id='searchform' action='".$Data["Link"]."' method='post'>
    <input type='hidden' name='Kerbol' id='Kerbol' value='1'>
    <input type='hidden' name='Honnan' id='Honnan' value='0'>
    <input type='hidden' name='K_TIPUS' id='K_TIPUS' value=''>
        <input type='hidden' name='K_FOZDE' id='K_FOZDE' value=''>    
        <input value='' name='Mitkeres' id='Mitkeres' placeholder='@@@Keresés§§§' type='search'>
        <input name='post_type' value='product' type='hidden'>
    </form>
</div>
";
        }


        
        function Alul($DATA)
        {
           
            $Menu=$DATA["Menu"];
            $Menuir="";

            if ($Menu)
            {
                $ind=0;
                foreach ($Menu as $egymenu)
                {

                    $Menuir.="<li><a href='".$egymenu["Link"]."'>".$egymenu["Nev"]."</a></li>
";
                    $ind++;
                    
                }
            }
            return "<div class='wrapper'>
        <div class='container'>
            <footer>
                <div class='half'>
                    <div class='half'>
                        <h3>Információk</h3>
                        <ul class='links'>
                            $Menuir
                        </ul>
                    </div>
                    <div class='half'>
                        <h3>Profilom</h3>
                        <ul class='links'>
                            <li><a href='#'>Adataim</a></li>
                            <li><a href='#'>Rendeléseim</a></li>
                            <li><a href='?page=kosar'>Kosár tartalma</a></li>
                        </ul>
                    </div>
                </div>
                <div class='half'>
                    <div class='half'>
                        <h3>KAPCSOLATFELVÉTEL</h3>
                        <p>Vasi Korpusz Kft. 9700 </p>
                        <p>Szombathely, Pálya u. 10-16.</p>
                        <p><br></p>
                        <p>Telefon/Fax: 06 94 318-641 </p>
                        <p>E-mail: <a href='mailto:info@vasikorpusz.hu'>info@vasikorpusz.hu</a></p>
                    </div>
                    <div class='half'>
                        <h3><br></h3>
                        <p>Bútorkészítés: 06 30 560-6644</p>
                        <p>E-mail: <a href='mailto:butorkeszítes@vasikorpusz.hu'>butorkeszítes@vasikorpusz.hu</a></p>
                        <p><br></p>
                        <p>Lapszabászat: 06 30 226-5687</p>
                        <p>E-mail: <a href='mailto:lapszabaszat@vasikorpusz.hu'>lapszabaszat@vasikorpusz.hu</a></p>
                    </div>
                </div>
                <div class='copy'>Copyright <a class='nolink' href='#'>©</a> 2017. <a href='index.php'>Vasi Korpusz Kft.</a>. Minden jog fenntartva.  <a id='y-solutions' href='http://ysolutions.hu/' target='_blank'><img title='developed by _ Y'SOLUTIONS INFORMATICS' alt='developed by _ Y'SOLUTIONS INFORMATICS' src='/templ/gfx/footer/y-solutions.png'></a>
                </p>                   
                </div>
            </footer>
        </div>
    </div>
";

        }
        
        
        function Oldalvege($DATA)
        {
                $Layer="               
";
                if ($DATA["Link"]!="")
                {

                            $Layer="<a href='".$DATA["Link"]."' id='nyitkep' class='zoomnyit' rel='iframe' data-fancybox-type='iframe'></a>
           <script type='text/javascript'>
                $(document).ready(function() {
                    $('#nyitkep').trigger( 'click' );
                });
        </script>";
                   
                        

                }
                return  "$Layer
                </body>
</html>               ";
        }
        


    
}


?>