
$(document).ready(function()
{
    // click sur le btn upload  
    $("#btn-script-ultiles").on("click",function()
    {
        $("#loader-script-1").css("display","inherit");
        $("#btn-script-ultiles").attr("disabled","true");
        // Lancement de la requête de mise à jour
        $.get("./proccess/ajax/srcipt_utiles/add_bd_fonctions.php",
        function(data,status,XhrJq) 
        {            
            $("#loader-script-1").css("display","none");
            $("#btn-script-ultiles").removeAttr("disabled");
            alert("action effectuée");
            console.log(data);
        })
        .fail(function(error) 
        {                 
            $("#loader-import-1").css("display","none");
            $("#btn-script-ultiles").removeAttr("disabled");
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });   
});