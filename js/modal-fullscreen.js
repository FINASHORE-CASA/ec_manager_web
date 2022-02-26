$(document).ready(function(e) {

    $("#Form-extand").on("click", () => {
    if ($("#Form-extand").attr("is_active") == "false") {
        $(".modal").addClass("modalFullscreen")
        $("#Form-extand").attr("is_active", "true")
        $("#Form-extand").html('<i class="fas fa-compress-alt"></i>')
    } else {
        $("#Form-extand").attr("is_active", "false")
        $(".modal").removeClass("modalFullscreen");
        $("#Form-extand").html('<i class="fas fa-expand"></i>')
    }
    })
})