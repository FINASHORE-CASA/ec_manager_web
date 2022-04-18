$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var textListLot = $("#text-list-lot")
    ,indicTermine = $("#indic-termine")
    ,formLoader = $("#form-lot-loader");
    textListLot.val("");
    var data1 = [];    

    var show_alert = function(theme_color,title,text="",time=5)
    {
        $("#alert-container").html('<div id="alert_box" class="alert alert-'+theme_color+' alert-dismissible fade show mt-2" role="alert">'
                                    +'<strong> '+title+' </strong>' + text
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                   +'</button>'
                                +'</div>');

        setTimeout(() => { $("#alert-container #alert_box").fadeOut("slow");} , (time*1000));                                
    }              
    
    // click sur le btn upload
    $("#btn-upload-csv-maj").on("click",function() 
    {
        $("#file_upload_maj").trigger("click");
    });     

    readFilemaj = function () {
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
                if(i != 0)
                {      
                    data1.push({prestataire:field[0],province:field[1],commune:field[2]
                            ,bec:field[3],numlivraison:field[4],nomlivraison:field[5]
                            ,id_lot:field[6],nbactetotal:field[7],statut_lot:field[8]});                                         
                }                
                else if(field[0].toLowerCase() != "prestataire" || field[1].toLowerCase() != "province" || field[2].toLowerCase() != "commune" 
                        || field[3].toLowerCase() != "bec" || field[4].toLowerCase() != "num livraison" || field[5].toLowerCase() != "nomlivraison"
                        || field[6].toLowerCase() != "num lot" || field[7].toLowerCase() != "nbactetotal" || field[8].toLowerCase() != "statut_lot")
                {
                  isChampValid = false;  
                  errorValidText = "l'entête des champs d'importation ou le format des colonnes n'est pas valide, le fichier est rejeté !!";
                }
            });  

            if(isChampValid)
            {
                $("#btn-valide-import-maj").removeAttr("disabled");
            }      
            else
            {
                $("#btn-valide-import-maj").attr("disabled","true");
                alert(errorValidText)
            }
        };

        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString($("#file_upload_maj")[0].files[0]);
    };
    
    $("#file_upload_maj").on("change",readFilemaj); 

    $("#btn-valide-import-maj").on("click",function(e)
    {
        e.preventDefault()
        $("#loader-import-2").css("display","inherit");
        $("#btn-valide-import-maj").attr("disabled","true");
        $("#btn-upload-csv-maj").attr("disabled","true");
        
        indicTermine.fadeOut("slow");
        $("#NombreMaj").html("")

        // Lancement de la requête de mise à jour
        $.post(`${HostLink}/proccess/ajax/SheetApi/setMajLot.php`,
        {myData: JSON.stringify(data1)},
        function(data,status,XhrJq) {
            $("#loader-import-2").css("display","none");
            $("#btn-valide-import-maj").removeAttr("disabled");
            $("#btn-upload-csv-maj").removeAttr("disabled");
                        
            let res = JSON.parse(data)
            if(res[0] == "success")
            {
                $("#NombreMaj").html(res[1])
                indicTermine.fadeIn(3000)  
            }
            else
            {
                $("#NombreMaj").html("erreur _log")
            }
            console.log(data);                                
        })
        .fail(function(error) {     
            $("#loader-import-2").css("display","none");
            $("#btn-valide-import-maj").removeAttr("disabled");
            $("#btn-upload-csv-maj").removeAttr("disabled");                                                  
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });         
});