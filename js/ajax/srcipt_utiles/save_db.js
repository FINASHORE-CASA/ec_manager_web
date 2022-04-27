$(document).ready(function()
{
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    $("#btn-script-lancer-sauve").on("click",function()
    {
        if($("#select_choix_save").val() != "" && $("#chemin_final_bd").val() != "" && $("#liste_bd").val() != "" )
        {
            $("#loader-script-4").css("display","inherit")

            let data1 = {
                list_bd : $("#liste_bd").val(),
                select_choix_save : $("#select_choix_save").val(),
                chemin_final_bd : $("#chemin_final_bd").val()
            }            

            // Lancement de la requête de mise à jour
            $.post(HostLink+"/proccess/ajax/srcipt_utiles/save_db.php",
            {myData: JSON.stringify(data1)},
            function(data,status,XhrJq) {   
                console.log(data)         
                $("#loader-script-4").css("display","none")
                alert("script terminé")
            })
            .fail(function(error) {     
                $("#loader-script-4").css("display","none")
                alert("Une erreure s'est produite ")
                console.log("fail : " + error)
            });
        }
        else
        {
            alert("Veuillez renseigner toutes les informations ")
        }
        
    });  

});