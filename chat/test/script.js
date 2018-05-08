/**var status = "VOIR LA SUITE";

function toggleText()
{
    
    if (status == "VOIR LA SUITE") {
        $(this).text("MOINS");
        $(".followingContent").css({
            "display" : "inline", 
            "height" : "auto"
        });
        $(".infoEmbeddedAdd").css({
            "height" : "auto"
        });
        status = "MOINS";
    } else if (status == "MOINS") {
        $(this).text("VOIR LA SUITE");
        $(".followingContent").css({
            "display" : "none", 
            "height" : "0px"
        });
        $(".infoEmbeddedAdd").css({
            "height" : "130px"
        });
        status = "VOIR LA SUITE"
    }
} **/

$( document ).ready(function() {
    $(document).on('click','.toggleButton', function(){
        var status = $(this).text();
        var parent = $(this).parents().eq(5).attr("id");
        if (status == "VOIR LA SUITE") {
            $(this).text("MOINS");
            $("#"+parent).find(".followingContent").css({
                "display" : "inline", 
                "height" : "auto"
            });
            $("#"+parent).find(".infoEmbeddedAdd").css({
                "height" : "auto"
            });
        } else if (status == "MOINS") {
            $(this).text("VOIR LA SUITE");
            $("#"+parent).find(".followingContent").css({
                "display" : "none", 
                "height" : "0px"
            });
            $("#"+parent).find(".infoEmbeddedAdd").css({
                "height" : "130px"
            });
        }
    });

    $(document).on('click','.infoEmbeddedAdd', function(e){
        if($(e.target).is('.toggleButton')){
            e.preventDefault();
            return;
        }
        window.open($(this).attr("data-url"), '_blank');
    });

});
