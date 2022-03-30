$(document).ready(function()
{
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // click sur le btn upload
    $("#btn-import-accepte").on("click",function() 
    {
        if($("#chemin_source").val().trim() != "" && $("#chemin_final").val().trim() != "")
        {
            $("#file_upload_acceptes").trigger("click")
        } 
        else
        {
            alert("les champs chemin, ne doivent pas être vide ")
        }
    });    
    var data1 = [];  

    readFileInventaire = function () {
        var reader = new FileReader();
        reader.onloadend = function () 
        { 
            var lines = reader.result.split("\r\n"); 
            data1 = [];
            let isChampValid = true;       
            let errorValidText = "";     
                                                
            lines.forEach(function(l,i)
            {    
                let field = l.split(";") 
                // console.log(l + "\n")  
                if(i != 0)
                {      
                    if(typeof field[1] !== "undefined" && field[1].length == 14 && !data1.some(s=>s.lot == field[1]))
                    {
                        data1.push({livraison:field[0],lot:field[1]
                            ,chemin_source:$("#chemin_source").val().trim()
                            ,chemin_final:$("#chemin_final").val().trim()
                        });               
                    }                            
                }                
                else if(field[0].toLowerCase() != "nomlivraison" || field[1].toLowerCase() != "lot")
                {
                  isChampValid = false;  
                  errorValidText = "l'entête des champs d'importation ou le format des colonnes n'est pas valide, le fichier est rejeté !!";
                }
            });   

            console.log(data1);   

            if(isChampValid)
            {
                $("#btn-script-prepare_bd_accepte").removeAttr("disabled");
            }      
            else
            {
                $("#btn-script-prepare_bd_accepte").attr("disabled","true");
                alert(errorValidText)
            }
        };

        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString($("#file_upload_acceptes")[0].files[0]);
    };

    $("#file_upload_acceptes").on("change",readFileInventaire);   

    $("#btn-script-prepare_bd_accepte").on("click",function()
    {
        $("#loader-script-2").css("display","inherit");
        $("#btn-script-prepare_bd_accepte").attr("disabled","true");
        $("#btn-import-accepte").attr("disabled","true");

        console.log("lunch script")
        
        // Lancement de la requête de mise à jour
        $.post(HostLink+"/proccess/ajax/srcipt_utiles/split_bd_acceptes.php",
        {myData: JSON.stringify(data1)},
        function(data,status,XhrJq) {            
            $("#loader-script-2").css("display","none");
            $("#btn-script-prepare_bd_accepte").removeAttr("disabled");
            $("#btn-import-accepte").removeAttr("disabled");
            alert("script terminé");
            console.log(data);
        })
        .fail(function(error) {     
            $("#loader-script-2").css("display","none");
            $("#btn-script-prepare_bd_accepte").removeAttr("disabled");
            $("#btn-import-accepte").removeAttr("disabled");
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });   
});