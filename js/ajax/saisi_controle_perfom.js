$(document).ready(function() {

    var btnClean = $("#btn-clean")
        ,btnCleanBlock = $("#clean-btn-block")   
        ,CleanBlockForm = $("#block-form-clean")
        ,btnCleanCancel = $("#btn-clean-cancel")   
        ,btnCleanCancel = $("#btn-clean-cancel")
        ,btnCleanOk = $("#btn-clean-ok")
        ,imgLoader = $("#img-loader")
        ,boxNotif = $("#box-notif");   
    

    btnClean.on('click',function(){
        CleanBlockForm.fadeIn(2000);
        btnCleanBlock.css('display','none');
    });

    btnCleanCancel.on('click',function(){
        CleanBlockForm.css('display','none');
        btnCleanBlock.fadeIn(2000);
    });

    btnCleanOk.on('click',function()
    {
        var data1 = {
            dateDebut: $("#dateDebut").val(),
            dateFin:$("#dateFin").val(),
            checkActe: $("#checkActe")[0].checked,
            checkMention:$("#checkMention")[0].checked,
            checkControle1:$("#checkControle1")[0].checked,
            checkControle2:$("#checkControle2")[0].checked
        }

        CleanBlockForm.css('display','none');
        
        if(imgLoader.css('display') == 'none')
        {
            imgLoader.fadeIn(2000);  
        }

        // Traitement Image Vide 

        $.post('../../proccess/ajax/clean_db.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                if(imgLoader.css('display') != 'none')
                {
                    imgLoader.css('display','none');  
                }
                btnCleanBlock.fadeIn(2000);

                var result = JSON.parse(data);                 
                
                if(result[0] == "success")
                {
                    // success callback
                    boxNotif.fadeIn(1000);

                    setTimeout(function(){
                        boxNotif.fadeOut(1000);
                    },3000);
                    console.log('message : ' + result);
                }
                else
                {
                    console.log('message error : ' + result);
                }
            }
        );

    });
});