function scrollEnd(){
    $('.chatWindow .chatbox .msgs').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            alert("OK");
            return true;
        }
    })
}

function scrollToEnd(){
    $('.chatWindow .chatbox .msgs').on('scroll', function() {
        if($(this).scrollTop() <= $(this)[0].scrollHeight-1452) {

        }else{
            $(".chatWindow .chatbox .msgs").animate({
                scrollTop : $(".chatWindow .chatbox .msgs")[0].scrollHeight
            });
        }
    })
}

function isScrolledIntoView(elem)
{
    var docViewTop = $(".chatWindow .chatbox .msgs").scrollTop();
    var docViewBottom = docViewTop + $(".chatWindow .chatbox .msgs").height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
function testView() {
    $(document).ready(function () {
        $("#msgForm button").click(function () {
            var x = Math.abs($(".msg:eq(2)").position().top - $(".msg:eq(2)").parent().parent().offset().top);
            alert(x);
        });
    });

}

function goToByScroll(id){
    // Scroll
    $('.chatWindow .chatbox .msgs').animate({
            scrollTop: $("#"+id).offset().top + $(".chatWindow .chat .msgs").scrollTop() - $(".chatWindow .chat").offset().top},
        'slow');
}

function goToByScrollPopup(transmitter, receiver, id){
    // Scroll
    var PopUpContainer = $('[data-id-receiver-popup=' + transmitter + '][data-id-transmitter-popup=' + receiver + '],[data-id-receiver-popup=' + receiver+ '][data-id-transmitter-popup=' + transmitter + ']').find('.popup-messages');
    $(PopUpContainer).animate({
            scrollTop: $("#"+id).offset().top + $(PopUpContainer).scrollTop() - $(PopUpContainer).offset().top},
        'slow');
}


/**
 if(($(".chatWindow .chat .msgs").scrollTop() <= $(".chatWindow .chat .msgs")[0].scrollHeight-1484) && elem.id != UserID) {
                    console.log(UserID);
                }else{
                    scTop();
                }**/

/**
 $('.msgs').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this).children().eq(-1).position().top + $(this).scrollTop() - $(this).parent().offset().top){
            $(".chatWindow .chat .notification").fadeOut(2000);
            newMessageCount = 0;
        }
    }); **/