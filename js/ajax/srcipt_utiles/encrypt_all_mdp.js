
$(document).ready(function()
{
    // click sur le btn upload  
    $("#btn-script-encrypt-mdp").on("click",function()
    {
        $("#loader-script-3").css("display","inherit");
        $("#btn-script-encrypt-mdp").attr("disabled","true");
        // Lancement de la requête de mise à jour
        $.get("./proccess/ajax/srcipt_utiles/crypt_all_mdp.php",
        function(data,status,XhrJq) 
        {            
            $("#loader-script-3").css("display","none");
            $("#btn-script-encrypt-mdp").removeAttr("disabled");
            alert("action effectuée");
            console.log(data);
        })
        .fail(function(error) 
        {                 
            $("#loader-import-1").css("display","none");
            $("#btn-script-encrypt-mdp").removeAttr("disabled");
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });   
});