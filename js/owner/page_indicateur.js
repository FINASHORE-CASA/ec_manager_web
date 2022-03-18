$(document).ready(function() {    
    $(".linkSideBar").each(function(i){
        $(this).removeClass("active");
    });

    $("#" + $("#page-top").attr("idpage")).addClass("active");
});
