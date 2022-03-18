$(document).ready(function()
{
    var btnControle = $("#btn-controle");
    var btnResetControle = $("#btn-reset-controle");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-controle-notif");

    textListLot.on("keyup",function(e) {
        countNbLot();
    });       

    btnResetControle.on("click",function(e) {
        txtNbLot.css("color","gray");
        txtNbLot.css("borderColor","gray");
        txtControleNotif.css("color","gray");
        txtNbLot.text("00");
        txtControleNotif.text("Nombre de lot à Valider ");
    });


    var countNbLot = function() {
        var txt = textListLot.val();
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});

        console.log(txtArray.length);
        txtNbLot.text((txtArray.length < 10) ? "0" + txtArray.length : txtArray.length);

        if(txtArray.length > 0)
        {
            txtNbLot.css("color","#20c9a6");
            txtNbLot.css("borderColor","#20c9a6");
            txtControleNotif.css("color","#20c9a6");
            txtControleNotif.text("Nombre de lot à Valider ");
        }
        else
        {
            txtNbLot.css("color","gray");
            txtNbLot.css("borderColor","gray");
            txtControleNotif.css("color","gray");
            txtControleNotif.text("Nombre de lot à Valider ");   
        }
    };            
});