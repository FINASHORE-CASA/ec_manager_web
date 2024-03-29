﻿$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif")
    ,indicTermine = $("#indic-termine")
    ,notifResultat = $("#notif-Resultat-bell")
    ,ResultatData = $("#resultat_data");
    textListLot.val("");
    var acteInfo = [];
    
    var listLotError = "";

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };     

    var show_alert = function(theme_color,title,text="",time=5)
    {
        $("#alert-container").html('<div id="alert_box" class="alert alert-'+theme_color+' alert-dismissible fade show mt-2" role="alert">'
                                    +'<strong> '+title+' </strong>' + text
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                   +'</button>'
                                +'</div>');

        setTimeout(() =>{ $("#alert-container #alert_box").fadeOut("slow");},(time*1000));                                
    }  

    $('#dataTableVerifImage').on('draw.dt', function () {
        startEditActe();                           
    });

    $(".btn-form-modal-cancel").on("click", function(e) {
        // Rétablissement des champs du formulaire
        $("#field-IdLot").val("");                                                                                                                      
        $("#field-IdActe").val("");                                                                                                                   
        $("#field-PrenomFr").val("");                                                                                                                      
        $("#field-PrenomAr").val("");                                                                                                                      
        $("#field-Genre").val("");                                                                                                                       
        $("#field-NomFr").val("");                                                                                                                      
        $("#field-NomAr").val("");                                                                                                                      
        $("#field-PrenomMargeFr").val("");                                                                                                                      
        $("#field-PrenomMargeAr").val("");                                                                                                                           
        $("#field-NomMargeFr").val("");                                                                                                                          
        $("#field-NomMargeAr").val("");                                                                                                                 
        $("#field-PrenomPereFr").val("");                                                                                                                      
        $("#field-PrenomPereAr").val("");                                                                                                                          
        $("#field-PrenomMereFr").val("");                                                                                                                          
        $("#field-PrenomMereAr").val("");                                                                                                                  
        $("#field-AscPereFr").val("");                                                                                                                      
        $("#field-AscPereAr").val("");                                                                                                                          
        $("#field-AscMereFr").val("");                                                                                                                          
        $("#field-AscMereAr").val("");                                                                                                                          
        $("#field-jour_g").val("");                                                                                                                          
        $("#field-mois_g").val("");                   
        $("#field-annee_g").val("");                   
        $("#field-jour_h").val("");                   
        $("#field-mois_h").val("");                   
        $("#field-annee_h").val("");                      
        $("#field-jd_etabli_acte_g").val("");                                                                                                                          
        $("#field-md_etabli_acte_g").val("");                   
        $("#field-ad_etabli_acte_g").val("");                   
        $("#field-jd_etabli_acte_h").val("");                   
        $("#field-md_etabli_acte_h").val("");                   
        $("#field-ad_etabli_acte_h").val("");
        $("#field-Id_user_saisi").val("");
        $("#field-Id_lot").val("");
        $("#field-NbMention").val("");
        $("#field-NbMentionCorr").val("");
        $("#img-block").html("");                                                                                                                    

        $("#btnTabMention").css("display","none");        
        $("#mention>div.row").html("")
        $("#form-group7").css("display","none");
    });
    
    $(".form-update-save").on("click",function(e){            
        // Récupération de l'Id du click
        var data1 = {
            id_acte: $("#field-IdActe").val(),
            prenom_fr:$("#field-PrenomFr").val().trim(),
            prenom_ar:$("#field-PrenomAr").val().trim(),
            sexe:$("#field-Genre").val().trim(),
            nom_fr:$("#field-NomFr").val().trim(),
            nom_ar:$("#field-NomAr").val().trim(),
            nom_marge_fr:$("#field-NomMargeFr").val().trim(),            
            nom_marge_ar:$("#field-NomMargeAr").val().trim(),            
            prenom_marge_fr:$("#field-PrenomMargeFr").val().trim(),            
            prenom_marge_ar:$("#field-PrenomMargeAr").val().trim(),                                                                                                                 
            prenom_pere_fr:$("#field-PrenomPereFr").val().trim(),                                                                                                                      
            prenom_pere_ar:$("#field-PrenomPereAr").val().trim(),                                                                                                                          
            prenom_mere_fr:$("#field-PrenomMereFr").val().trim(),                                                                                                                          
            prenom_mere_ar:$("#field-PrenomMereAr").val().trim(),                                                                                                                 
            ascendant_pere_fr:$("#field-AscPereFr").val().trim(),                                                                                                                      
            ascendant_pere_ar:$("#field-AscPereAr").val().trim(),                                                                                                                          
            ascendant_mere_fr:$("#field-AscMereFr").val().trim(),                                                                                                                          
            ascendant_mere_ar:$("#field-AscMereAr").val().trim(),   
            jd_naissance_g:$("#field-jour_g").val().trim(),            
            md_naissance_g:$("#field-mois_g").val().trim(),            
            ad_naissance_g:$("#field-annee_g").val().trim(),            
            jd_naissance_h:$("#field-jour_h").val().trim(),            
            md_naissance_h:$("#field-mois_h").val().trim(),            
            ad_naissance_h:$("#field-annee_h").val().trim(),
            jd_etabli_acte_g:$("#field-jd_etabli_acte_g").val().trim(),                                                                                                                          
            md_etabli_acte_g:$("#field-md_etabli_acte_g").val().trim(),                   
            ad_etabli_acte_g:$("#field-ad_etabli_acte_g").val().trim(),                   
            jd_etabli_acte_h:$("#field-jd_etabli_acte_h").val().trim(),                   
            md_etabli_acte_h:$("#field-md_etabli_acte_h").val().trim(),                   
            ad_etabli_acte_h:$("#field-ad_etabli_acte_h").val().trim(),
            jd_deces_h:$("#field-jd_deces_h").val().trim(),                                                                                                                          
            md_deces_h:$("#field-md_deces_h").val().trim(),                   
            ad_deces_h:$("#field-ad_deces_h").val().trim(),                   
            jd_deces_g:$("#field-jd_deces_g").val().trim(),                   
            md_deces_g:$("#field-md_deces_g").val().trim(),                   
            ad_deces_g:$("#field-ad_deces_g").val().trim()
        }   

        // ajout de la mention
        let mentions = []
        $(".val-mention").each((i,m) => {
            mentions.push({id_mention:m.getAttribute("id_mention"),txtmention:m.value.trim()});
        });

        data1.mentions = mentions;
        let champs_corriges = "";

        for (const key in data1) 
        {
            if (Object.hasOwnProperty.call(data1, key)) 
            {
                champs_corriges += (data1[key].toString().trim().toLowerCase() == (acteInfo[key]+'').trim().toLowerCase()) ? '' : (key+',');  
            }
        }        

        if(champs_corriges != "")
        {     
            $.post(HostLink+'/proccess/ajax/saisi/update_acte_identite.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {                        
                        // success callback                    
                        if(result[1])
                        {
                            var data2 = {
                                id_acte:data1.id_acte,
                                id_sup_user:$("#field-Id_user").val().trim(),
                                champs_corriges: champs_corriges.trim().substring(0,(champs_corriges.trim().length - 1)),
                                id_saisi_user:$("#field-Id_user_saisi").val(),
                            }

                            // Lancement de l'enregistrement des stats         
                            $.post(HostLink+'/proccess/ajax/saisi/set_stats_correction.php',   // url
                            { myData: JSON.stringify(data2) }, // data to be submit
                                function(data, status, jqXHR) 
                                {
                                    if(result[0] == "success")
                                    {           
                                        $("#ActeRow"+data1.id_acte).fadeOut();
                                        $(".btn-form-modal-cancel").trigger("click");                        
                                        show_alert("success","Modification effectuée ");
                                        $("#notif-Resultat-1").text(parseInt($("#notif-Resultat-1").text()) - 1);
                                    }
                                }
                            )
                        }                    
                    }
                    else
                    {
                        show_alert("danger","erreur lors de la modification",result,10);
                        console.log('message error : ' + result);
                        console.log(result);
                    }
                }
            ); 
        }
        else
        {   
            $("#ActeRow"+data1.id_acte).fadeOut();                        
            $(".btn-form-modal-cancel").trigger("click");
            show_alert("warning","Aucune modification enregistrée");
            $("#notif-Resultat-1").text(parseInt($("#notif-Resultat-1").text()) - 1);
        }    

        // Enregistrement de la mention manquante
        console.log($("#field-NbMentionCorr").val());
        console.log($("#field-NbMentionCorr").val());
        if($("#field-NbMentionCorr").val() != "" /*&& parseInt($("#field-NbMentionCorr").val()) >= 0*/)
        {
            dataMention = {id_acte:$("#field-IdActe").val()
                          ,mention_acte:$("#field-NbMention").val()
                          ,mention_corr:$("#field-NbMentionCorr").val()
                          ,id_user:$("#field-Id_user").val()
                          ,id_lot:$("#field-IdLot").val()}

            console.log(dataMention)                          

            $.post(HostLink+'/proccess/ajax/saisi/insert_nombre_mention_declare.php',   // url
            { myData: JSON.stringify(dataMention) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                }
            )
        }
    });  

    var startSwitchImage = function() 
    {
        $(".img-switch").on("click",function(e)
        {
            e.preventDefault();
            if($(this).attr("ownid") == "1")
            {
                if($("#image1").css("display") == "none")
                {
                    $("#image2").fadeOut(); 
                    var src = $("#image1").attr("src");
                    $("#image1").removeAttr("src").attr("src",src);                   
                    $("#image1").fadeIn(2000); 
                    $("#image2").css("display","none")

                    $(".img-switch1").each((i,e) =>  
                    {                        
                        e.style.color = "black";
                    })

                    $(".img-switch2").each((i,e) =>  
                    {                        
                        e.style.color = "gray";
                    })
                }                
            }      
            else
            {
                if($("#image2").css("display") == "none")
                {
                    $("#image1").fadeOut(); 
                    var src = $("#image2").attr("src");
                    $("#image2").removeAttr("src").attr("src",src);  
                    $("#image2").fadeIn(2000); 
                    $("#image1").css("display","none")

                    $(".img-switch1").each((i,e) =>  
                    {      
                        e.style.color = "gray";
                    })
                    $(".img-switch2").each((i,e) =>  
                    {
                        e.style.color = "black";     
                    })
                }    
            }      
        });
    };

    //function de modification de l'acte
    var startEditActe = function(e) 
    {
        var btnEdit = $(".btn-edit");

        // Remplissage des informations de l'acte
        btnEdit.on("click",function(e){

            // Lancement du chargement des information
            // $("#form-acte-field").css("display","none");
            $("#form-group1").css("background","none")
            $("#form-group2").css("background","none")
            $("#form-acte-loader").css("display","block");

            // Récupération de l'Id du click
            var data1 = {
                id_acte: $(this).attr("idActe"),
                is_mention_void: $(this).attr("mention_vide")
            }     

            acteInfo = [];
            let erroColor = "#c22b3040";
            
            $("#field-NomMargeFr").css("background","none") 
            $("#field-PrenomMargeFr").css("background","none")             
            $("#field-NomMargeAr").css("background","none") 
            $("#field-PrenomMargeAr").css("background","none") 
            $("#field-jour_g").css("background","none") 
            $("#field-mois_g").css("background","none") 
            $("#field-annee_g").css("background","none") 
            $("#field-jour_h").css("background","none") 
            $("#field-mois_h").css("background","none") 
            $("#field-annee_h").css("background","none")              
            $("#form-group3>div:eq(0)").css("background","none");
            $("#form-group3>div:eq(1)").css("background","none");    
            $("#field-PrenomFr").css("background","none")
            $("#field-PrenomAr").css("background","none")
            $("#field-Genre").css("background","none")    
            $("#form-group7").css("display","none");            
            $("#field-ad_deces_g").css("background","none") 
            $("#field-ad_deces_h").css("background","none") 

            if($(this).attr("nom_mg_fr_s") == "1" || $(this).attr("nom_with_i") == "1" )
            {
                $("#field-NomMargeFr").css("background",erroColor) ;
            }

            if($(this).attr("prenom_mg_fr_s") == "1" || $(this).attr("prenom_with_i") == "1"
             || $(this).attr("prenom_with_a") == "1")
            {
                $("#field-PrenomMargeFr").css("background",erroColor)  
            }

            if($(this).attr("nom_mg_ar_s") == "1" || $(this).attr("nom_ar_with_error") == "1")
            {
                $("#field-NomMargeAr").css("background",erroColor)       
            }

            if($(this).attr("prenom_mg_ar_s") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)                
            }

            if($(this).attr("crt_pren_1") == "1")
            {
                $("#field-PrenomAr").css("background",erroColor)
                $("#field-AscPereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_2") == "1")
            {
                $("#field-PrenomAr").css("background",erroColor)
                $("#field-AscMereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_3") == "1")
            {
                $("#field-NomAr").css("background",erroColor)
                $("#field-AscPereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_4") == "1")
            {
                $("#field-NomAr").css("background",erroColor)
                $("#field-AscMereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_5") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomPereAr").css("background",erroColor)
                $("#field-NomMargeAr").css("background",erroColor)
                $("#field-AscPereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_6") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomMereAr").css("background",erroColor)
                $("#field-NomMargeAr").css("background",erroColor)
                $("#field-AscMereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_7") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomPereAr").css("background",erroColor)
                $("#field-NomMargeAr").css("background",erroColor)
                $("#field-AscPereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_8") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomMereAr").css("background",erroColor)
                $("#field-NomMargeAr").css("background",erroColor)
                $("#field-AscMereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_9") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomPereAr").css("background",erroColor)                
                $("#field-AscPereAr").css("background",erroColor)
            }

            if($(this).attr("crt_pren_10") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)
                $("#field-PrenomMereAr").css("background",erroColor)                
                $("#field-AscMereAr").css("background",erroColor)
            }

            // Critère sur la date d'établissement de l'acte        
            if($(this).attr("je_g_s") == "1")
            {
                $("#field-jd_etabli_acte_g").css("background",erroColor)  
            }

            if($(this).attr("me_g_s") == "1")
            {
                $("#field-md_etabli_acte_g").css("background",erroColor) 
            }

            if($(this).attr("ae_g_s") == "1")
            {
                $("#field-ad_etabli_acte_g").css("background",erroColor) 
            }

            if($(this).attr("je_h_s") == "1")
            {
                $("#field-jd_etabli_acte_h").css("background",erroColor)
            }

            if($(this).attr("me_h_s") == "1")
            {
                $("#field-md_etabli_acte_h").css("background",erroColor)   
            }

            if($(this).attr("ae_h_s") == "1")
            {
                $("#field-ad_etabli_acte_h").css("background",erroColor)  
            }

            if($(this).attr("date_etab_g_format") == "1")
            {
                $("#field-jd_etabli_acte_g").css("background",erroColor) 
                $("#field-md_etabli_acte_g").css("background",erroColor)
                $("#field-ad_etabli_acte_g").css("background",erroColor)  
            }

            if($(this).attr("date_etab_h_format") == "1")
            {
                $("#field-jd_etabli_acte_h").css("background",erroColor) 
                $("#field-md_etabli_acte_h").css("background",erroColor)
                $("#field-ad_etabli_acte_h").css("background",erroColor)  
            }
            // -----------------------------------------------------------            

            // Critère sur la date de naissance
            if($(this).attr("jn_g_s") == "1")
            {
                $("#field-jour_g").css("background",erroColor)  
            }            

            if($(this).attr("mn_g_s") == "1")
            {
                $("#field-mois_g").css("background",erroColor) 
            }

            if($(this).attr("an_g_s") == "1")
            {
                $("#field-annee_g").css("background",erroColor) 
            }

            if($(this).attr("jn_h_s") == "1")
            {
                $("#field-jour_h").css("background",erroColor)
            }

            if($(this).attr("mn_h_s") == "1")
            {
                $("#field-mois_h").css("background",erroColor)   
            }

            if($(this).attr("an_h_s") == "1")
            {
                $("#field-annee_h").css("background",erroColor)  
            }

            if($(this).attr("date_naiss_g_format") == "1")
            {
                $("#field-jour_g").css("background",erroColor) 
                $("#field-mois_g").css("background",erroColor) 
                $("#field-annee_g").css("background",erroColor) 
            }

            if($(this).attr("date_naiss_h_format") == "1")
            {
                $("#field-jour_h").css("background",erroColor)
                $("#field-mois_h").css("background",erroColor)
                $("#field-annee_h").css("background",erroColor)  
            }
            // -------------------------------------------------
                
            if($(this).attr("prenom_nofound") == "1")
            {
                $("#field-PrenomFr").css("background",erroColor)
                $("#field-PrenomAr").css("background",erroColor)
                $("#field-Genre").css("background",erroColor)
            }

            if($(this).attr("prenom_pere_nofound") == "1")
            {
                $("#form-group4>div:eq(0)").css("background",erroColor);
            }

            if($(this).attr("prenom_mere_nofound") == "1")
            {
                $("#form-group5>div:eq(0)").css("background",erroColor);
            }
            // ---------------------------------------------------
            
            
            // Critère Date de Décès
            if($(this).attr("is_deces") == "1")
            {                
                $("#form-group7").css("display","inherit");
            }   

            if($(this).attr("ad_an_h_error1") == "1" || $(this).attr("ad_ae_h_error1") == "1" 
             || $(this).attr("ad_an_h_error2") == "1" || $(this).attr("ad_ae_h_error2") == "1")
            {
                $("#field-ad_deces_h").css("background",erroColor)  
            }        

            if($(this).attr("ad_an_g_error1") == "1" || $(this).attr("ad_ae_g_error1") == "1" 
             || $(this).attr("ad_an_g_error2") == "1" || $(this).attr("ad_ae_g_error2") == "1")
            {
                $("#field-ad_deces_g").css("background",erroColor)  
            }            

            $.post(HostLink+'/proccess/ajax/action_auto/recup_acte_identite.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                                                                       
                    if(result[0] == "success")
                    {                    
                        // Remplissage des champs du formulaire
                        $("#btnTabActive").trigger("click");
                        $("#field-IdLot").val(result[1].id_lot);                                                                                                                      
                        $("#field-IdActe").val(result[1].id_acte);                                                                                                                      
                        $("#field-PrenomFr").val(result[1].prenom_fr);                                                                                                                      
                        $("#field-PrenomAr").val(result[1].prenom_ar);                                                                                                                      
                        $("#field-NomFr").val(result[1].nom_fr);                                                                                                                      
                        $("#field-NomAr").val(result[1].nom_ar);                                                                                                                      
                        $("#field-PrenomMargeFr").val(result[1].prenom_marge_fr);                                                                                                                      
                        $("#field-PrenomMargeAr").val(result[1].prenom_marge_ar);                                                                                                                          
                        $("#field-NomMargeFr").val(result[1].nom_marge_fr);                                                                                                                          
                        $("#field-NomMargeAr").val(result[1].nom_marge_ar);                                                                                                                 
                        $("#field-PrenomPereFr").val(result[1].prenom_pere_fr);                                                                                                                      
                        $("#field-PrenomPereAr").val(result[1].prenom_pere_ar);                                                                                                                          
                        $("#field-PrenomMereFr").val(result[1].prenom_mere_fr);                                                                                                                          
                        $("#field-PrenomMereAr").val(result[1].prenom_mere_ar);                                                                                                                  
                        $("#field-AscPereFr").val(result[1].ascendant_pere_fr);                                                                                                                      
                        $("#field-AscPereAr").val(result[1].ascendant_pere_ar);                                                                                                                          
                        $("#field-AscMereFr").val(result[1].ascendant_mere_fr);                                                                                                                          
                        $("#field-AscMereAr").val(result[1].ascendant_mere_ar); 
                        $("#field-Genre").val(result[1].sexe);                                                                                                                          
                        $("#field-jour_g").val(result[1].jd_naissance_g);                                                                                                                          
                        $("#field-mois_g").val(result[1].md_naissance_g);                   
                        $("#field-annee_g").val(result[1].ad_naissance_g);                   
                        $("#field-jour_h").val(result[1].jd_naissance_h);                   
                        $("#field-mois_h").val(result[1].md_naissance_h);                   
                        $("#field-annee_h").val(result[1].ad_naissance_h);                                                                                                                   
                        $("#field-jd_etabli_acte_g").val(result[1].jd_etabli_acte_g);                                                                                                                          
                        $("#field-md_etabli_acte_g").val(result[1].md_etabli_acte_g);                   
                        $("#field-ad_etabli_acte_g").val(result[1].ad_etabli_acte_g);                   
                        $("#field-jd_etabli_acte_h").val(result[1].jd_etabli_acte_h);                   
                        $("#field-md_etabli_acte_h").val(result[1].md_etabli_acte_h);                   
                        $("#field-ad_etabli_acte_h").val(result[1].ad_etabli_acte_h);
                        $("#field-Id_user_saisi").val(result[1].id_saisi_user);                                                                                                        
                        $("#field-jd_deces_h").val(result[1].jd_deces_h);                                                                                                                          
                        $("#field-md_deces_h").val(result[1].md_deces_h);                   
                        $("#field-ad_deces_h").val(result[1].ad_deces_h);                   
                        $("#field-jd_deces_g").val(result[1].jd_deces_g);                   
                        $("#field-md_deces_g").val(result[1].md_deces_g);                   
                        $("#field-ad_deces_g").val(result[1].ad_deces_g);
                        $("#field-Id_lot").text(result[1].id_lot);
                        $("#field-NbMention").val(result[1].mention);
                        $("#field-NbMentionCorr").val("");
                        acteInfo = result[1];   

                        if(result[4].length > 0)
                        {
                            $("#mention>div.row").html("")
                            $("#btnTabMention").css("display","inherit");
                            
                            let htmlMention = "";
                            result[4].forEach((m) => 
                            {
                               htmlMention += '<div class="form-group col-md-12 mb-5"> \n';
                               htmlMention += '<label for="field-mention-'+m.id_mention+'"> textmention_'+ m.id_mention +' </label> \n'; 
                               htmlMention += '<textarea id_mention="'+m.id_mention+'" id=field-mention-'+m.id_mention+' rows="4" class="form-control val-mention" style="background:'+ (m.txtmention == null || m.txtmention.trim().length == 0 ? erroColor : 'white')+';"> '+m.txtmention+' </textarea> \n';
                               htmlMention += '</div> \n';
                            })
                            $("#mention>div.row").html(htmlMention);
                        }                                    

                        if(result[2] == "yes")
                        {
                            $ImageTab = result[1].imagepath.split(";;");

                            if(result[1].imagepath.includes(";;"))
                            {
                                var i = 1;
                                var htmlContentImg = "";
                                $ImageTab.forEach((e) => {
                                    if(e.trim() != "")
                                    {
                                        htmlContentImg += "<img id='image"+ i + "' class='img-fluid img-thumbnail' style='margin-left:60px;height:600px;width:auto;"+ ((i == 2) ? "display:none;" : "") +"' src='"+ result[3] +"\\" + result[1].id_acte + "_" +  e + "' alt='"+  e +"'/>";                 
                                    }  
                                    i++;
                                });
                                $("#img-block").html(htmlContentImg);
                                $(".block-img-change").html("<a class='img-switch img-switch1' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                            +"<a class='img-switch img-switch2' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>"
                                                            +"<a class='ml-2 img-zoom-reset' href='#' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");
                            }
                            else
                            {
                                $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_acte + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
                                $(".block-img-change").html("<a class='img-switch img-switch1' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                           +"<a class='ml-2 img-zoom-reset' href='#' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");                                                           
                            }

                            // initialisation du plugin de zoom                                                 
                            const element = document.getElementById('img-block')
                            const resetButton = document.getElementsByClassName('img-zoom-reset');
                            const panzoom = Panzoom(element, 
                            {
                                // options here                                
                            });
                            // enable mouse wheel
                            const parent = element.parentElement
                            parent.addEventListener('wheel', panzoom.zoomWithWheel);                        
                            resetButton[0].addEventListener('click', (e) => e.preventDefault());
                            resetButton[0].addEventListener('click', panzoom.reset);
                            resetButton[1].addEventListener('click', (e) => e.preventDefault());
                            resetButton[1].addEventListener('click', panzoom.reset);
                        }           
                        else
                        {
                            $("#img-block").html("");
                            $(".block-img-change").html("");
                        }

                        startSwitchImage();
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    } 
                    
                    $("#form-acte-loader").css("display","none");
                }
            ).fail(function()
            { 
                $("#form-acte-field").css("display","inherit");
                $("#form-acte-field").css("display","none");
                alert("erreur lors de l'execution");
            }); 
        });
    }

    var initDataTable = function(dataTable) 
    {
        dataTable.DataTable({
            "language": {
                "sProcessing": "Traitement en cours ...",
                "sLengthMenu": "Afficher _MENU_ lignes",
                "sZeroRecords": "Aucun résultat trouvé",
                "sEmptyTable": "Aucune donnée disponible",
                "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                "sInfoEmpty": "Aucune ligne affichée",
                "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                "sInfoPostFix": "",
                "sSearch": "Chercher:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Chargement...",
                "oPaginate": {
                "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                },
                "oAria": {
                "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                }
            }
        });
    };

    var get_id_lots = function()
    {
        // Récupération de la source 
        $.get(HostLink+'/proccess/ajax/livraison/get_id_lot.php'
        ,function(data,status,jqXHR)
        {
            var result = JSON.parse(data);                               
            
            if(result[0] == "success")
            {
                // success callback
                result[1].forEach(function(e)
                {
                    $("#text-list-lot").val($("#text-list-lot").val() + e.id_lot + '\n');                    
                    txtControleNotif.css("color","#20c9a6");
                    txtNbLot.css("borderColor","#20c9a6");
                    txtNbLot.css("color","#20c9a6");
                    txtNbLot.text(countNbLot($("#text-list-lot").val()));
                });                
            }
            else
            {
                console.log('message error : ' + result);
                console.log(result);
            }
        })
        .fail(function(res){
            console.log("fail");
            console.log(res);
        });  
    };

    $("#show_all").on("click",function(){

        if($(this)[0].checked == true)
        {
            textListLot.val("");
            textListLot.attr("disabled","true");
            
            // Recherche des actes concernés            
            get_id_lots(); 
        }
        else
        {
            textListLot.removeAttr("disabled");
        }
    })
    $("#show_all")[0].checked = false;

    // Ajout Prenom Personne
    $("#btn-add-champs-prenom-genre").on("click",function() 
    {                
        // Récupération de l'Id du click
        var data1 = {
            id_user:$("#field-Id_user").val(),
            prenom_fr:$("#field-PrenomFr").val().trim(),
            prenom_ar:$("#field-PrenomAr").val().trim(),           
            genre_prenom:$("#field-Genre").val().trim(),
        }    

        // add identite bd 
        $.post(HostLink+'/proccess/ajax/saisi/add_identite_bd.php',
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // display result
                    alert (" informations ajoutés ")    
                }
                else if(result[0] == "exist")
                {
                    alert (" ces informations existent déjà ")    
                }                
                else
                {
                    alert(" une erreur s'est produite");
                    console.log(' message error : ' + result);
                    console.log(result);
                }
            }
        ); 
    });

    // Ajout Prenom Père
    $("#btn-add-champs-prenom-pere").on("click",function() 
    {                
        // Récupération de l'Id du click
        var data1 = {
            id_user:$("#field-Id_user").val(),
            prenom_fr:$("#field-PrenomPereFr").val().trim(),
            prenom_ar:$("#field-PrenomPereAr").val().trim(),           
            genre_prenom:"M",
        }    

        // add identite bd 
        $.post(HostLink+'/proccess/ajax/saisi/add_identite_bd.php',
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // display result
                    alert (" informations ajoutés ")    
                }
                else if(result[0] == "exist")
                {
                    alert (" ces informations existent déjà ")    
                }                
                else
                {
                    alert(" une erreur s'est produite");
                    console.log(' message error : ' + result);
                    console.log(result);
                }
            }
        ); 
    });

    // Ajout Prenom Mère
    $("#btn-add-champs-prenom-mere").on("click",function() 
    {                
        // Récupération de l'Id du click
        var data1 = {
            id_user:$("#field-Id_user").val(),
            prenom_fr:$("#field-PrenomMereFr").val().trim(),
            prenom_ar:$("#field-PrenomMereAr").val().trim(),           
            genre_prenom:"F",
        }    

        // add identite bd 
        $.post(HostLink+'/proccess/ajax/saisi/add_identite_bd.php',
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {                
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // display result
                    alert (" informations ajoutés ")    
                }
                else if(result[0] == "exist")
                {
                    alert (" ces informations existent déjà ")    
                }                
                else
                {
                    alert(" une erreur s'est produite");
                    console.log(' message error : ' + result);
                    console.log(result);
                }
            }
        ); 
    });


    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot(textListLot.val());
        listLotError = "";
        $("#text-list-lot-errone").val("");
        console.log(nbLot);
        if(nbLot == 0)
        {
            txtControleNotif.css("color","red");
            txtControleNotif.text("aucun lot renseigné.");
        }
        else
        {
            // hide previous result
            $("#liste-indic li").fadeOut("slow");
            indicTermine.fadeOut("slow");
            formLoader.css("display","inherit");
            notifResultat.fadeOut("fast"); 
            ResultatData.fadeOut("fast");    

            // traitement des lots             
            var data1 = {
                id_lot: textListLot.val().trim().replace(/[\n\r]/g,', '),
                mode_ech:$("#mode_ech")[0].checked,
                ech_value:$("#ech_value").val()
            }              

            // Traitement Image Vide 
            $.post(HostLink+'/proccess/ajax/saisi/controle_identitaire.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            // display result    
                            $("#liste-indic li:eq(0)").html("Vérification des identités : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                            $("#liste-indic li:eq(0)").fadeIn(1000);
                            console.log('success : '  + result[1].length);

                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");
                            $("#notif-Resultat-1").text(result[1].length);

                            console.log(result[1]);
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => {   
                                
                                htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>"
                                            +"<td class='text-center'> <a href='#' class='btn-edit' idActe='"+ e.id_acte +"' style='color:gray;' data-toggle='modal' data-target='#ActeModal' type-error='"
                                            + e.marge_erreur +"' " 
                                            +"nom_mg_fr_s='"+e.nom_mg_fr_s+"' "
                                            +"prenom_mg_fr_s='"+e.prenom_mg_fr_s+"' "
                                            +"nom_mg_ar_s='"+e.nom_mg_ar_s+"'"
                                            +"prenom_mg_ar_s='"+e.prenom_mg_ar_s+"' "
                                            +"jn_g_s='"+e.jn_g_s+"' "
                                            +"mn_g_s='"+e.mn_g_s+"' "
                                            +"an_g_s='"+e.an_g_s+"' "
                                            +"jn_h_s='"+e.jn_h_s+"' "
                                            +"mn_h_s='"+e.mn_h_s+"' "
                                            +"an_h_s='"+e.an_h_s+"' "
                                            +"prenom_nofound='"+e.prenom_nofound+"' "
                                            +"prenom_mere_nofound='"+e.prenom_mere_nofound+"' "
                                            +"prenom_pere_nofound='"+e.prenom_pere_nofound+"' "
                                            +"mention_vide='"+e.mention_vide+"' "
                                            +"prenom_with_i='"+e.prenom_with_i+"' "
                                            +"nom_with_i='"+e.nom_with_i+"' "
                                            +"nom_ar_with_error='"+e.nom_ar_with_error+"' "
                                            +"prenom_with_a='"+e.prenom_with_a+"' "
                                            +"asc_prenom_mere='"+e.asc_prenom_mere+"' "
                                            +"crt_pren_1='"+e.crt_pren_1+"' "
                                            +"ctr_pren_2='"+e.ctr_pren_2+"' "
                                            +"ctr_pren_3='"+e.ctr_pren_3+"' "
                                            +"ctr_pren_4='"+e.ctr_pren_4+"' "
                                            +"ctr_pren_5='"+e.ctr_pren_5+"' "
                                            +"ctr_pren_6='"+e.ctr_pren_6+"' "
                                            +"ctr_pren_7='"+e.ctr_pren_7+"' "
                                            +"ctr_pren_8='"+e.ctr_pren_8+"' "
                                            +"ctr_pren_9='"+e.ctr_pren_9+"' "
                                            +"ctr_pren_10='"+e.ctr_pren_10+"' "
                                            +"ctr_pren_11='"+e.ctr_pren_11+"' "
                                            +"date_naiss_g_format='"+e.date_naiss_g_format+"' "
                                            +"date_naiss_h_format='"+e.date_naiss_h_format+"' "
                                            +"date_etab_g_format='"+e.date_etab_g_format+"' "
                                            +"date_etab_h_format='"+e.date_etab_h_format+"' "
                                            +"je_g_s='"+e.je_g_s+"' "
                                            +"me_g_s='"+e.me_g_s+"' "
                                            +"ae_g_s='"+e.ae_g_s+"' "
                                            +"je_h_s='"+e.je_h_s+"' "
                                            +"me_h_s='"+e.me_h_s+"' "
                                            +"ae_h_s='"+e.ae_h_s+"' "                                            
                                            +"is_deces='"+e.is_deces+"' "
                                            +"ad_an_h_error1='"+e.ad_an_h_error1+"' "
                                            +"ad_ae_h_error1='"+e.ad_ae_h_error1+"' "
                                            +"ad_an_h_error2='"+e.ad_an_h_error2+"' "
                                            +"ad_ae_h_error2='"+e.ad_ae_h_error2+"' "                                            
                                            +"ad_an_g_error1='"+e.ad_an_g_error1+"' "
                                            +"ad_ae_g_error1='"+e.ad_ae_g_error1+"' "
                                            +"ad_an_g_error2='"+e.ad_an_g_error2+"' "
                                            +"ad_ae_g_error2='"+e.ad_ae_g_error2+"' "
                                            +">"                                             
                                            +"<i class='fas fa-highlighter'></i></a> </td>"
                                            +"<td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.mention +  "</td><td>" + e.nom_fr 
                                            +"</td><td>" + e.nom_ar + "</td><td>" + e.nom_marge_fr + "</td><td>" + e.nom_marge_ar + "</td><td>" + e.prenom_fr + "</td>"
                                            +"</td><td>" + e.prenom_ar + "</td><td>" + e.prenom_marge_fr + "</td><td>" + e.prenom_marge_ar 
                                            +"</td><td>" + e.prenom_pere_fr + "</td>" + "</td><td>" + e.prenom_pere_ar + "</td>" + "</td><td>" + e.prenom_mere_fr + "</td>" + "</td><td>" + e.prenom_mere_ar + "</td>"
                                            +"<td>" + e.ascendant_pere_fr + "</td>" + "<td>" + e.ascendant_pere_ar + "</td>" + "<td>" + e.ascendant_mere_fr + "</td>" + "<td>" + e.ascendant_mere_ar 
                                            +"</td><td>" + e.sexe + "</td>"
                                            +"<td>" + e.jd_naissance_g + "</td><td>" + e.md_naissance_g + "</td><td>" + e.ad_naissance_g + "</td>"
                                            +"<td>" + e.jd_naissance_h + "</td><td>" + e.md_naissance_h + "</td><td>" + e.ad_naissance_h + "</td>"
                                            +"<td>" + e.jd_etabli_acte_g + "</td><td>" + e.md_etabli_acte_g + "</td><td>" + e.ad_etabli_acte_g + "</td>"
                                            +"<td>" + e.jd_etabli_acte_h + "</td><td>" + e.md_etabli_acte_h + "</td><td>" + e.ad_etabli_acte_h + "</td>"
                                            +"</td></tr>";
                                
                                // Ajout de l'IdLot dans les erronés
                                if(!listLotError.includes(e.id_lot))
                                {
                                    listLotError += e.id_lot + "\n"; 
                                }
                            });
                            
                            $("#dataTableVerifImage").dataTable().fnDestroy()
                            $("#TableVerifImage").html(htmlDataTable);
                            initDataTable($('#dataTableVerifImage'));                                              
                                                        
                            // Terminé le Lancement
                            $("#text-list-lot-errone").val(listLotError);
                            $("#indic-lot-error").text(countNbLot(listLotError));
                            formLoader.fadeOut("slow");                        
                            indicTermine.fadeIn(3000);                               
                        }
                        else
                        {
                            console.log('message error : ' + result);
                            console.log(result);
                        }
                    }
                );             
        }
        e.preventDefault();        
    });
});