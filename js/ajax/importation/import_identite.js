
$(document).ready(function()
{
    // click sur le btn upload
    $("#btn-upload-csv").on("click",function() 
    {
        $("#file_upload").trigger("click");
    });    
    var data1 = [];  

    readFile = function () {
        var reader = new FileReader();
        reader.onloadend = function () 
        { 
            var lines = reader.result.split("\r\n"); 
            data1 = [];            
            
            lines.forEach(function(l)
            {    
                let field = l.split(";");   
                if(field[0].trim().toLowerCase() != "prenom_fr")
                {
                if(!data1.some(s=>s.prenom_fr == field[0] && s.prenom_ar == field[1] && s.genre_prenom == field[2]))
                {
                    data1.push({prenom_fr:field[0],prenom_ar:field[1],genre_prenom:field[2],id_user_add:0});               
                }
                }
            });

            console.log(data1);

            $("#btn-valide-import").removeAttr("disabled");
        };

        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString($("#file_upload")[0].files[0]);
    };

    $("#file_upload").on("change",readFile);   

    $("#btn-valide-import").on("click",function()
    {
        $("#loader-import-1").css("display","inherit");
        $("#btn-valide-import").attr("disabled","true");
        $("#btn-upload-csv").attr("disabled","true");
        // Lancement de la requête de mise à jour
        $.post("./proccess/ajax/importations/import_identite_bd.php",
        {myData: JSON.stringify(data1)},
        function(data,status,XhrJq) {
            $("#loader-import-1").css("display","none");
            $("#btn-valide-import").removeAttr("disabled");
            $("#btn-upload-csv").removeAttr("disabled");
            alert("importation effectuée");
            console.log(data);
        })
        .fail(function(error) {     
            $("#loader-import-1").css("display","none");
            $("#btn-valide-import").removeAttr("disabled");
            $("#btn-upload-csv").removeAttr("disabled");
            alert("Une erreure s'est produite ");
            console.log("fail : " + error);
        });
    });   
});