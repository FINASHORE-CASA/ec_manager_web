$(document).ready(function()
{
    // click sur le btn upload
    $("#btn-upload-csv-inventaire").on("click",function() 
    {
        $("#file_upload_inventaire").trigger("click");
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
                        data1.push({id_bec:field[0],lot:field[1],tome:field[2]
                                ,indice:field[3],annee_g:field[4],annee_h:field[5]
                                ,naissance:field[6],deces:field[7],mariage:field[8]
                                ,divorce:field[9],total:field[10]});               
                    }                            
                }                
                else if(field[0].toLowerCase() != "id_bec" || field[1].toLowerCase() != "lot" || field[2].toLowerCase() != "tome" 
                        || field[3].toLowerCase() != "indice" || field[4].toLowerCase() != "annee_g" || field[5].toLowerCase() != "annee_h"
                        || field[6].toLowerCase() != "naissance" || field[7].toLowerCase() != "deces" || field[8].toLowerCase() != "mariage"
                        || field[9].toLowerCase() != "divorce" || field[10].toLowerCase() != "total")
                {
                  isChampValid = false;  
                  errorValidText = "l'entête des champs d'importation ou le format des colonnes n'est pas valide, le fichier est rejeté !!";
                }
            });   

            console.log(data1);   

            if(isChampValid)
            {
                $("#btn-valide-import-inventaire").removeAttr("disabled");
            }      
            else
            {
                $("#btn-valide-import-inventaire").attr("disabled","true");
                alert(errorValidText)
            }
        };

        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString($("#file_upload_inventaire")[0].files[0]);
    };

    $("#file_upload_inventaire").on("change",readFileInventaire);   

    $("#btn-valide-import-inventaire").on("click",function()
    {
        $("#loader-import-2").css("display","inherit");
        $("#btn-valide-import-inventaire").attr("disabled","true");
        $("#btn-upload-csv-inventaire").attr("disabled","true");
        // Lancement de la requête de mise à jour
        $.post("./proccess/ajax/importations/import_inventaire.php",
        {myData: JSON.stringify(data1)},
        function(data,status,XhrJq) {
            $("#loader-import-2").css("display","none");
            $("#btn-valide-import-inventaire").removeAttr("disabled");
            $("#btn-upload-csv-inventaire").removeAttr("disabled");
            alert("importation effectuée");
            console.log(data);
        })
        .fail(function(error) {     
            $("#loader-import-2").css("display","none");
            $("#btn-valide-import-inventaire").removeAttr("disabled");
            $("#btn-upload-csv-inventaire").removeAttr("disabled");
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });   
});