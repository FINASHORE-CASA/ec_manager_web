$(document).ready(function(e)
{
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    var ListeDataBase = [];

    // Recherche des données    
    var getData = function()
    { 
        $.get(HostLink+'/proccess/ajax/gestion_db/get_dbs.php')
        .done(function(data)
        {            
            var result = JSON.parse(data);        

            // injection des données                             
            result[1].forEach(e => 
            {                            
                e.is_checked = false;
                if(!ListeDataBase.some(e1=>e1.oid == e.oid))                    
                    ListeDataBase.push(e)                       
            })
            showList(ListeDataBase)            
        });               
    };

    getData();

    function showList(data)
    {                
        // injection des données                             
        htmlDataDb = "";   
        data.forEach(e => {                            
            htmlDataDb += '<div id="card'+ e.oid+'" class="card mb-2" '+ ((e.is_checked == true) ? "border-primary" : "") +'>'
                            +'<div class="card-body row ml-1">'
                                +'<input type="checkbox" class="mr-2 btn-chk-change" style="width:15px;height:15px;" '
                                +'id="customSwitch'+e.oid+'" oid="'+e.oid+'"  datname ="'+e.datname+'" ' + ((e.is_checked == true) ? "checked" : "") +'/>'                                        
                                +'<label class="card-title h4" for="customSwitch'+e.oid+'"  style="font-size:14px;"> '+ e.datname +'</label>'                                        
                            +'</div>'
                        +'</div>';                     
        });
        
        $("#list-db").html(htmlDataDb);    
        
        $(".btn-chk-change").each((i,e) =>
            {               
                $(this).on("change", (e) => 
                {
                    if(ListeDataBase.some(l=>l.oid == e.target.getAttribute("oid")))
                    {
                        ListeDataBase.filter(l=>l.oid == e.target.getAttribute("oid"))[0].is_checked = e.target.checked;
                    }
                });
            }
        ) 
    }

    $("#search_db").on("keyup",function()
    {                
        showList(ListeDataBase.filter(l=>l.datname.toUpperCase().includes($(this).val().trim().toUpperCase())))
    })

    $("#btn-gen-stats-global").on("click",(e) =>
    {
        console.log(ListeDataBase)
        e.preventDefault();
    })
});