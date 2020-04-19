function Auto_kieg(Mezonev,Link) {
$( "#"+Mezonev).autocomplete
(
{

        select: function( event, ui )
        {

        },
        source: function( request, response )
        {
                 $.ajax(
                {
                        url: Link,
                        data:
                        {
                                featureClass: "P",
                                style: "full",
                                maxRows: 12,
                                Atad: request.term
                        },
                        success: function( data )
                        {
                            response( $.map( data.Names, function( item )
                            {
                                 return {
                                label: item.name,
                                 value: item.name
                                 }

                             }
                              ));
                        }
                });
        }
});
};



function Ajax_tolt(link,hova)
{

        $('#'+hova).load(link);
}
function form_kuld (formnev,hova){
$.ajax({
type: "POST",
url: "main.php",
data: $('#'+formnev).serialize(),
success: function(data){
$('#'+hova).html(data);
}
});
}

                      function Masolatallit(mezo)
                        {
                                komboobj=document.getElementById(mezo+'_lat');
                                hossz=komboobj.length;
                                vissza=';';
                                h=0;
                                for (c=0;c<hossz;c++)
                                {
                                        if (komboobj.options[c].selected)
                                        {
                                                if ((komboobj.options[c].value!=-1)&&(komboobj.options[c].value!=0))
                                                {
                                                        h++;
                                                        vissza=vissza+komboobj.options[c].value+';';
                                                }
                                        }
                                }

                                document.getElementById(mezo).value=vissza;
                        }
