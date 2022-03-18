$(document).ready(function(e)
{
    var initBtnDb = function()
    {        
        var btnSwitch = $(".btn-chk-change");

        btnSwitch.on("click",function()
        {
            // Récupération de l'Id du click
            var data1 = 
            {
                oid: $(this).attr("oid"), 
                datname:$(this).attr("datname")               
            }       

            if($(this)[0].checked)
            {                
                // Set as active database
                $.post('./proccess/ajax/gestion_db/set_active_db.php',   // url
                    { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {                  
                        var result = JSON.parse(data);                               
                        if(result[0] == "success")
                        {
                            // désactivation des checkbox/switch
                            getData();
                        }
                        else
                        {
                            console.log('message error : ' + result);
                            console.log(result);
                        }
                    });
            }
            else
            {
               // Unset as active database
                $.post('./proccess/ajax/gestion_db/disactive_db.php',   // url
                    { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {            
                        console.log(data);        
                        var result = JSON.parse(data);                               
                        if(result[0] == "success")
                        {
                            getData();
                        }
                        else
                        {
                            console.log('message error : ' + result);
                            console.log(result);
                        }
                    });
            }
        });
    };

    // Recherche des données    
    var getData = function(search=null)
    { 
        if(search == null)
        {
            $.get('./proccess/ajax/gestion_db/get_dbs.php')
            .done(function(data)
            {            
                var result = JSON.parse(data);

                // injection des données                             
                htmlDataDb = "";    
                result[1].forEach(e => {                            
                    htmlDataDb += '<div id="card'+ e.oid+'" class="card mb-2 '+ ((e.is_active_db == 1) ? "border-primary" : "") +'">'
                                    +'<div class="card-body">'
                                        +'<h4 class="card-title text-xs"> '+ e.datname +'</h4>'
                                        +'<div class="custom-control custom-switch">' 
                                            +'<input type="checkbox" class="custom-control-input btn-chk-change" id="customSwitch'+e.oid+'" '+ ((e.is_active_db == 1) ? "checked" : "") +' oid="'+e.oid+'"  datname ="'+e.datname+'"/>'
                                            +'<label class="custom-control-label" for="customSwitch'+e.oid+'">  </label>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>';
                });

                $("#list-db").html(htmlDataDb);          
                initBtnDb();
            });               
        }
        else
        {            
            var obj = { 
                search_el : search,
            };

            $.post('./proccess/ajax/gestion_db/get_dbs_search.php',
            { myData: JSON.stringify(obj)},
                function(data,status,jqXHR)
                {
                    console.log(data);
                    var result = JSON.parse(data);

                    // injection des données                             
                    htmlDataDb = "";    
                    result[1].forEach(e => {                            
                        htmlDataDb += '<div id="card'+ e.oid+'" class="card mb-2 '+ ((e.is_active_db == 1) ? "border-primary" : "") +'">'
                                        +'<div class="card-body">'
                                            +'<h4 class="card-title text-xs"> '+ e.datname +'</h4>'
                                            +'<div class="custom-control custom-switch">' 
                                                +'<input type="checkbox" class="custom-control-input btn-chk-change" id="customSwitch'+e.oid+'" '+ ((e.is_active_db == 1) ? "checked" : "") +' oid="'+e.oid+'"  datname ="'+e.datname+'"/>'
                                                +'<label class="custom-control-label" for="customSwitch'+e.oid+'">  </label>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>';
                    });

                    $("#list-db").html(htmlDataDb);          
                    initBtnDb();
                }
            ); 
        }
    };

    getData();


    $("#search_db").on("keyup",function()
    {
        getData($(this).val().trim().toUpperCase());
    });
});