$(document).ready(function()
{
    var btnResetDiviser = $("#btn-reset-diviser");
    var btnResetFusion = $("#btn-reset-fusion");
    var textListLotDiviser = $("#text-list-lot-diviser");
    var textListLotFusion = $("#text-list-lot-fusion");
    var txtControleNotifDiviser = $("#txt-nb-lot-notif-diviser");
    txtControleNotifDiviser.text(txtControleNotifDiviser.attr("text-std"));
    var txtControleNotifFusion = $("#txt-nb-lot-notif-fusion");
    txtControleNotifFusion.text(txtControleNotifFusion.attr("text-std"));

    textListLotDiviser.on("keyup",function(e) 
    {
        countNbLot($(this),$("#txt-nb-lot-diviser"),txtControleNotifDiviser);
    });     

    textListLotFusion.on("keyup",function(e) 
    {
        countNbLot($(this),$("#txt-nb-lot-fusion"),txtControleNotifFusion);
    });       

    btnResetDiviser.on("click",function(e) 
    {
        $("#txt-nb-lot-diviser").css("color","gray");
        $("#txt-nb-lot-diviser").css("borderColor","gray");
        txtControleNotifDiviser.css("color","gray");
        $("#txt-nb-lot-diviser").text("00");
        txtControleNotifDiviser.text(txtControleNotifDiviser.attr("text-std"));
    });      

    btnResetFusion.on("click",function(e) 
    {
        $("#txt-nb-lot-fusion").css("color","gray");
        $("#txt-nb-lot-fusion").css("borderColor","gray");
        txtControleNotifFusion.css("color","gray");
        $("#txt-nb-lot-fusion").text("00");
        txtControleNotifFusion.text(txtControleNotifFusion.attr("text-std"));
    });


    var countNbLot = function(text,tbnblot,tbnotif) 
    {
        var txt = text.val();
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});

        console.log(txtArray.length);
        tbnblot.text((txtArray.length < 10) ? "0" + txtArray.length : txtArray.length);

        if(txtArray.length > 0)
        {
            tbnblot.css("color","#20c9a6");
            tbnblot.css("borderColor","#20c9a6");
            tbnotif.css("color","#20c9a6");
            tbnotif.text(tbnotif.attr("text-std"));
        }
        else
        {
            tbnblot.css("color","gray");
            tbnblot.css("borderColor","gray");
            tbnotif.css("color","gray");
            tbnotif.text(tbnotif.attr("text-std"));   
        }
    };            
});