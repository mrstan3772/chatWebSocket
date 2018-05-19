var newMessageCount = 0;
var UserID = null;
var popAvailability =
    [
        {
            "position": 75,
            "available" : true,
            "targetPopUp" : null
        },
        {
            "position" : 387,
            "available" : true,
            "targetPopUp" : null
        },
        {
            "position" : 699,
            "available" : true,
            "targetPopUp" : null
        },
        {
            "position" : 1011,
            "available" : true,
            "targetPopUp" : null
        }
    ];
var window_focus;
$(window).focus(function() {
    window_focus = true;
    pageTitleNotification.off();
}).blur(function() {
    window_focus = false;
});
window.scTop = function(){
        $(".chatWindow .chatbox .msgs").animate({
            scrollTop: $(".chatWindow .chatbox .msgs")[0].scrollHeight
        });
};
window.scTopPopup = function(popUp){
        popUp.animate({
            scrollTop: popUp[0].scrollHeight
        });
};
window.availabilityPopup = function(target){
    var positionPopup;
    for(var i=0; i<popAvailability.length; i++) {
        if(popAvailability[i].available === true){
            positionPopup = popAvailability[i].position;
            popAvailability[i].available = false;
            popAvailability[i].targetPopUp = target;
            return positionPopup;
        }
    }
    positionPopup = null;
    return positionPopup;
};
window.availabilityPopupRemove = function(target){
    var currRemove =  0;
    for(var i=0; i<popAvailability.length; i++) {
        if(popAvailability[i].targetPopUp == target){
            popAvailability[i].available = true;
            currRemove = popAvailability[i].position;
        }
        if (popAvailability[i].available === false && popAvailability[i].position > currRemove && currRemove != 0) {
            $('[data-id-receiver-popup=' + popAvailability[i].targetPopUp + ']').animate({
                right: popAvailability[i-1].position
            }, 'slow');
            popAvailability[i-1].targetPopUp = popAvailability[i].targetPopUp;
            popAvailability[i-1].available = false;
            popAvailability[i].targetPopUp = null;
            popAvailability[i].available = true;
        }
    }
};
window.verifyPopAvailability = function(){
    tab = new Array();
    verify = popAvailability.map(function (pop) {
        if (pop.available === false ) {
            tab.push(true);
        }else{
            tab.push(false);
        }
    });
    return tab;
};
window.infiniteScroll = function(idPopup, firstIdMsg, idU){
    idPopup.on("scroll.infiniteScrollFunction", function() {
        var pos = idPopup.scrollTop();
        if (pos == 0) {
            //firstIdMsg.find('.popup-messages').css({'box-shadow':'0px 0px 0px 0px #ffffffe0 inset'});
            firstIdMsg.find('.popup-messages').animate({ boxShadow: '0px 40px 40px -5px'}, 250, function() {
                firstIdMsg.find('.popup-messages').clearQueue().finish().stop().animate({ boxShadow: '0px 0px 0px 0px #ffffffe0 inset'}, 250);
            });
            firstIdMsg.find('.direct-chat-messages').animate({ marginTop: '+85px'}, 250, function() {
                firstIdMsg.find('.direct-chat-messages').clearQueue().finish().stop().animate({ marginTop: '0px'}, 250);
            });
            setTimeout(function() {
                if(!firstIdMsg.find('[data-msg-id]').first().is('#firstPreviousMsg')){
                    firstIdMsg.find('[data-msg-id]').removeAttr('id', 'firstPreviousMsg');
                    firstIdMsg.find('[data-msg-id]').first().attr('id', 'firstPreviousMsg');
                    ws.send("fetchPrivatePrevious", {"firstMsg": firstIdMsg.find('[data-msg-id]').first().attr('data-msg-id'), "idReceiver":idU});
                    setTimeout(function () {
                        idPopup.animate({
                            scrollTop: firstIdMsg.find('#firstPreviousMsg').position().top
                        });
                    }, 100)
                }
            }, 350)
        }
    });
    $(document).delegate('#removeClass', 'click', function (e) {
        var idUser = $(e.target ).closest('.popup-box-on').attr('data-id-receiver-popup');
        if(idUser == idU) {
            idPopup.off('.infiniteScrollFunction');
        }
    });
};
window.guid = function() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
};
window.bytesToSize = function(bytes) {
    var sizes = ['octets', 'KO', 'MO', 'GO', 'TO'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};
window.uploadFile = function(target) {
    var fileUpload = $('[data-id-receiver-popup='+target+']').find('.fileupload').attr('id');
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.githuzzzb.io' ?
        '//jquery-file-upload.appspot.com/' : 'inc/UploadFiles/';
    $('#'+fileUpload).fileupload({
        url: url,
        dataType: 'json',
        add: function (e, data) {
            $('[data-id-receiver-popup='+target+']').find('.buttonFileUpload').attr('disabled', 'disabled');
            $.each(data.files, function (index, file) {
                var uploadErrors = [];
                var acceptFileTypes = /(\.|\/)(gif|jpe?g|png|pdf|msword|vnd.openxmlformats-officedocument.wordprocessingml.document|vnd.openxmlformats-officedocument.presentationml.presentation|vnd.ms-powerpoint|vnd.openxmlformats-officedocument.spreadsheetml.sheet|vnd.ms-excel|vnd.oasis.opendocument.graphics|vnd.oasis.opendocument.presentation|vnd.oasis.opendocument.spreadsheet|mpeg|x-ms-wma|vnd.rn-realaudi|x-matroska|x-wav|ogg|xml|zip|x-rar-compressed|x-7z-compressed|x-gzip|x-compressed|x-zip-compressed|x-zip|mpeg|mp4|quicktime|x-ms-wmv|x-msvideo|x-flv|webm|x-troff-|avi|x-sgi-movie|x-mpeg|x-sgi-movie|x-matroska)$/i;
                if (file['type'].length && !acceptFileTypes.test(file['type'])) {
                    uploadErrors.push('Ce type de fichier n\'est pas accepté !');
                }
                if (file['size'] > 100000000) {
                    var fileName = file['name'];
                    if (fileName.length > 50) {
                        uploadErrors.push(fileName.substring(0, 47) + '... : La taille du fichier dépasse la limite autorisé !');
                    } else {
                        uploadErrors.push(fileName + ' : La taille du fichier dépasse la limite autorisé !');
                    }
                }
                if (uploadErrors.length > 0) {
                    $.alert({
                        title: 'Une erreur est survenue !',
                        content: uploadErrors.join("\n"),
                        type: 'red',
                        typeAnimated: true,
                        closeIcon: true,
                        animationBounce: 2,
                        backgroundDismiss: true,
                        draggable: true,
                        icon: 'fas fa-exclamation-triangle',
                        theme: 'modern',
                        columnClass: 'col-xs-10'
                    });
                    $('[data-id-receiver-popup='+target+']').find('.buttonFileUpload').removeAttr('disabled');
                } else {
                    $('[data-id-receiver-popup='+target+']').find('#progress').fadeIn(1000);
                    $('[data-id-receiver-popup=' + target + ']').find('.statusUploadFile span').text('Téléchargement en cours...').fadeIn(1000);
                    data.submit();
                }
            });
        },
        done: function (e, data) {
            $.each(data.files, function (index, file) {
                //$('<p/>').text(file.name).appendTo('#files');
                var name = data.result.files[index].name;
                var size = data.result.files[index].size;
                var type = data.result.files[index].type;
                var originalName = file.name;
                ws.send('sendToUser', {'msg': 'Transfert de fichier', 'msgFile': '<div class="donwnloadFileContainer"> <h1>Transfert de fichier :</h1> <div class="titleFileUpload"> <a href="inc/UploadFiles/files/'+name+'" download><strong>'+originalName+'</strong></a> </div> <div class="sizeFileUpload"> <span>'+bytesToSize(size)+'</span> </div> <div class="logoDonwload"> <i class="far fa-arrow-alt-circle-down"></i> </div> </div>', 'transmitter': UserID, 'receiver': target});
            });
            $('[data-id-receiver-popup='+target+']').find('.statusUploadFile span').addClass('text-success');
            $('[data-id-receiver-popup='+target+']').find('.statusUploadFile span').text('Téléchargement terminé !').fadeOut(5000);
            $('[data-id-receiver-popup='+target+']').find('#progress').fadeOut(5000);
            setTimeout(function() {
                $('[data-id-receiver-popup='+target+']').find('.statusUploadFile span').removeClass('text-success');
                $('#progress .progress-bar').css('width',0);
                $('[data-id-receiver-popup='+target+']').find('.buttonFileUpload').removeAttr('disabled');
            }, 5100);
        },
        fail : function (e, data) {
            $.alert({
                title: 'Une erreur inconnue est survenue !',
                content: 'Essayer de nouveau !',
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                animationBounce: 2,
                backgroundDismiss: true,
                draggable: true,
                icon: 'fas fa-exclamation-triangle',
                theme: 'modern',
                columnClass: 'col-xs-10'
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('[data-id-receiver-popup='+target+']').find('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
}
window.connect = function(){
	window.ws = $.websocket("ws://<ip>:<port>", {
		open: function(e) {
			$(".chatWindow .chatbox .infoConnection .status").text("En ligne");
			$(".statusButton").css({"background-color" : "#28a745"});
			ws.send("fetch");
		},
		close: function() {
			$(".chatWindow .chatbox .infoConnection .status").text("Hors ligne");
            $(".statusButton").css({"background-color" : "#dc3545"});
		},
		events: {
			fetch: function(e) {
                //$(".chatWindow .chat .msgs").html('');
				$.each(e.data, function(i, elem){
					$(".chatWindow .chat .msgs").append("<div class='msg row' title='"+ elem.posted +"'><div class='containerAvatar col-md-3'><span class='avatar'><img src='uploads/"+elem.avatar+"' alt='avatar'></span></div><div class='containerMsgInfo col-md-9'><span class='name'>"+ elem.name +"</span> <b>: </b><span class='msgc'>"+ elem.msg +"</span></div></div>");
				});
                $(".chatWindow .chat .msgs svg").hide();
                scTop();
			},
			onliners: function(e){
				var count = 0;
				$(".chatWindow .users table tbody").html('');
				$.each(e.data, function(i, elem){
                    count++;
                    $(".chatWindow .users table tbody").append("<tr class='infoUserOnline' data-my-id='"+UserID+"' data-user-id='"+elem.id+"'> <th scope='row' class='align-middle'>"+count+"</th><td><img src='uploads/"+elem.avatar+"' alt='avatar'></td> <td class='align-middle'>"+elem.name+"</td> <td class='align-middle'>"+elem.id+"</td></tr>");
				});
                $(".chatWindow .online").html("<div class='userOnline col-md-12'> Utilisateur en ligne : "+ count +"</div>");
			},
			single: function(e){
				var elem = e.data;
				$(".chatWindow .chat .msgs").append("<div class='msg row' title='"+ elem.posted +"'><div class='containerAvatar col-md-3'><span class='avatar'><img src='uploads/"+elem.avatar+"' alt='avatar'></span></div><div class='containerMsgInfo col-md-9'><span class='name'>"+ elem.name +"</span> <b>: </b><span class='msgc'>"+ elem.msg +"</span></div></div>");
                $('.sk-fading-circle').css({'display':'none'});
                $('.buttonSendChat').show();
				var last = $(".msgs").children().eq(-1);
                var beforlast = $(".msgs").children().eq(-2);
                var screenHeight = $(".chatWindow .chatbox .msgs")[0].scrollHeight;
                var elementScroll = $(".chatWindow .chatbox .msgs").scrollTop() + $(".chatWindow .chatbox .msgs").innerHeight();
                var elementIsNotView = screenHeight-last.height()-beforlast.height();
                if((elementScroll  <= elementIsNotView) && (elem.id != UserID)) {
                    if(elem.id != 999999999999999999999999999999999999) {
                        if ($("#lastMessage").length) {

                        } else {
                            last.attr("id", "lastMessage");
                        }

                        if ($(".chatWindow .chat .notification").is(":hidden")) {
                            $(".chatWindow .chat .notification").slideToggle(2000);
                        }
                        newMessageCount++;
                        $(".chatWindow .chat .countNewMessages").html(newMessageCount);
                        $(document).on('click', '.notification', function () {
                            goToByScroll("lastMessage");
                            $("#lastMessage").removeAttr("id");
                            $(".chatWindow .chat .notification").fadeOut(2000);
                            newMessageCount = 0;
                        });
                    }
                }else{
                    scTop();
                }
			},
            infoUser: function(e){
                var elem = e.data;
				UserID = elem.id;
                $(".chatWindow .chatbox .infoConnection .nameUser").append(elem.name);
            },
            createPopup: function(e) {
                $.each(e.data, function (i, elem) {
                    if ($('[data-id-transmitter-popup=' + UserID + '][data-id-receiver-popup=' + elem.id + ']').length === 0) {
                        $('body')
                            .append('<div class="popup-box chat-popup" id="qnimate" data-id-transmitter-popup="' + UserID + '" data-id-receiver-popup="' + elem.id + '">\n' +
                                '            <div class="popup-head">\n' +
                                '                <div class="popup-head-left float-left"><img src="uploads/' + elem.avatar + '" alt="image de profil" class="popup-profil-img"> <span class="nameUserPopup">' + elem.name + '<span></div>\n' +
                                '                <div class="popup-head-right float-right">\n' +
                                '                    <div class="btn-group">\n' +
                                '                        <button class="chat-header-button" data-toggle="dropdown" type="button" aria-expanded="false">\n' +
                                '                            <i class="fas fa-cog"></i></button>\n' +
                                '                        <ul role="menu" class="dropdown-menu float-right">\n' +
                                '                            <li><a href="#">Media</a></li>\n' +
                                '                            <li><a href="#">Block</a></li>\n' +
                                '                            <li><a href="#">Clear Chat</a></li>\n' +
                                '                            <li><a href="#">Email Chat</a></li>\n' +
                                '                        </ul>\n' +
                                '                    </div>\n' +
                                '                    <button data-widget="remove" id="removeClass" class="chat-header-button float-right" type="button"><i class="fas fa-times"></i></button>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '            <div class="notifPopup">Nouveau message</div>\n' +
                                '            <div class="popup-messages">\n' +
                                '                <div class="direct-chat-messages"></div>\n' +
                                '            </div>\n' +
                                '            <div class="popup-messages-footer">\n' +
                                '                <form id="msgToUser" class="msgToUser">\n' +
                                '                    <input type="text" id="status_message" class="field_messages_user_'+elem.id+'" placeholder="Saisir un message..." name="message" autocomplete="off"/>\n' +
                                '                </form>\n' +
                                '                <div class="btn-footer d-flex">\n' +
                                '                   <div class="containerload" style=" width: 85%; ">\n' +
                                '                    <div class="statusUploadFile float-left" style="font-size: 0.7em;">\n' +
                                '                           <span></span>\n' +
                                '                       </div>\n' +
                                '                       <div id="progress" class="progress float-left" style="width:100%;display: none">\n' +
                                '                           <div class="progress-bar progress-bar-success"></div>\n' +
                                '                       </div>\n' +
                                '                    </div>\n' +
                                '                    <button class="bg_none float-right buttonFileUpload"><i class="fas fa-paperclip"></i></button>\n' +
                                '                    <input style="display: none;" class="fileupload" id="'+guid()+'" type="file" name="files[]" multiple>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '        </div>'
                            );
                        var pressed = false;
                        $('.field_messages_user_' + elem.id).emojioneArea({
			    filtersPosition: "bottom",
                            events: {
                                keyup: function (editor, event) {
                                    if (event.which == 13) {
                                        var valInput = $('.field_messages_user_' + elem.id).data("emojioneArea").getText();
                                        if( pressed === true && valInput !== "") {
                                            $.alert({
                                                title: 'À bas le spam !',
                                                content: 'Pas de flood ! Merci de votre compréhension.',
                                                type: 'red',
                                                typeAnimated: true,
                                                closeIcon: true,
                                                animationBounce: 2,
                                                backgroundDismiss: true,
                                                draggable: true,
                                                icon: 'fas fa-exclamation-triangle',
                                                theme: 'modern',
                                                columnClass: 'col-xs-10'
                                            });
                                            return false;
                                        }

                                        pressed = true;
                                        setTimeout(function() { pressed = false }, 500);
                                        $('.field_messages_user_' + elem.id).val(valInput);
                                        $('.msgToUser').submit();
                                        $('.field_messages_user_' + elem.id).data("emojioneArea").setText("");
                                    }
                                }
                            }
                        });
                    }
                });
                $(".msgToUser").on("submit", function(e){
                    e.preventDefault();
                    var form = $(this);
                    var val	 = $(this).find("input[type=text]").val();
                    var idUser = $(this).parents().eq(1).attr('data-id-receiver-popup');

                    if(val != "" && typeof val !== "undefined"){
                        ws.send("sendToUser", {"msg": val, "transmitter": UserID, "receiver": idUser});
                        form[0].reset();
                    }
                });
            },
            removePopup: function(e) {
                var elem = e.data;
                function closePopup() {
                    if($('[data-user-id=' + elem + ']').length === 0) {
                        availabilityPopupRemove(elem);
                        $('[data-id-receiver-popup=' + elem + ']').remove();
                    }
                }
                setTimeout(closePopup, 20000);

                function alertUser() {
                    function addZero(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        return i;
                    }

                    function getHour() {
                        var d = new Date();
                        var h = addZero(d.getHours());
                        var m = addZero(d.getMinutes());
                        var s = addZero(d.getSeconds());
                        var hour  = h + ":" + m + ":" + s;
                        return hour;
                    }
                    if($('[data-user-id=' + elem + ']').length === 0) {
                        $('[data-id-receiver-popup=' + elem + ']').find('.direct-chat-messages')
                            .append('<div class="direct-chat-msg doted-border">\n' +
                                '                        <div class="direct-chat-info clearfix">\n' +
                                '                            <span class="direct-chat-name float-left">M.X</span>\n' +
                                '                        </div>\n' +
                                '                        <!-- /.direct-chat-info -->\n' +
                                '                        <img alt="message user image" src="uploads/bot_logo.png" class="direct-chat-img"><!-- /.direct-chat-img -->\n' +
                                '' +
                                '                        <div class="direct-chat-text">L\'utilisateur vient de se déconnecter, si il ne se reconnecte pas dans les 10 prochaines secondes alors cette fenêtre se fermera</div>\n' +
                                '                        <div class="direct-chat-info clearfix">\n' +
                                '                            <span class="direct-chat-timestamp float-right">' + getHour() + '</span>\n' +
                                '                        </div>\n' +
                                '                        <div class="direct-chat-info clearfix">\n' +
                                '\t\t\t\t\t\t<span class="direct-chat-img-reply-small float-left">\n' +
                                '\n' +
                                '\t\t\t\t\t\t</span>\n' +
                                '                            <span class="direct-chat-reply-name">BOT</span>\n' +
                                '                        </div>\n' +
                                '                        <!-- /.direct-chat-text -->\n' +
                                '                    </div>'
                            );
                    }
                }
                setTimeout(alertUser, 10000);
            },
            fetchPrivate: function(e){
                        $.each(e.data, function (i, elem) {
                            $('[data-id-receiver-popup=' + elem.idUser + '][data-id-transmitter-popup=' + elem.receiver + '],[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + elem.idUser + ']').find('.direct-chat-messages')
                                .prepend(
                                    '<div class="chat-box-single-line" data-msg-id="'+elem[0]+'">\n' +
                                        '<abbr class="timestamp">' + elem.posted + '</abbr>\n' +
                                    '</div>\n'+
                                    '<div class="direct-chat-msg doted-border" data-user-id-msg="'+elem.idUser+'">\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '                            <span class="direct-chat-name float-left">' + elem.name + '</span>\n' +
                                    '                        </div>\n' +
                                    '                        <!-- /.direct-chat-info -->\n' +
                                    '                        <img alt="message user image" src="uploads/' + elem.avatar + '" class="direct-chat-img"><!-- /.direct-chat-img -->\n' +
                                    '                        <div class="direct-chat-text">\n'
                                    + elem.msg +
                                    '                        </div>\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '                            <span class="direct-chat-timestamp float-right">'+ elem.postedHour +'</span>\n' +
                                    '                        </div>\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '\t\t\t\t\t\t<span class="direct-chat-img-reply-small float-left">\n' +
                                    '\n' +
                                    '\t\t\t\t\t\t</span>\n' +
                                    '                            <span class="direct-chat-reply-name">' + elem.categoryName + '</span>\n' +
                                    '                        </div>\n' +
                                    '                        <!-- /.direct-chat-text -->\n' +
                                    '                    </div>'
                                );
                            if($('[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + UserID + ']').length){
                                var Popup = $('[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + UserID + ']').find('[data-user-id-msg="'+UserID+'"]');
                                Popup.find('.direct-chat-name, .direct-chat-reply-name').addClass('myPersonnalMsg');
                                Popup.find('.direct-chat-text').addClass('myPersonnalMsgBg');
                            }
                        });
            },
            fetchPrevious: function(e){
                        $.each(e.data, function (i, elem) {
                            $('[data-id-receiver-popup=' + elem.idUser + '][data-id-transmitter-popup=' + UserID + '],[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + UserID + ']').find('.direct-chat-messages')
                                .prepend('<div class="chat-box-single-line" data-msg-id="'+elem[0]+'">\n' +
                                    '<abbr class="timestamp">' + elem.posted + '</abbr>\n' +
                                    '</div>\n'+
                                    '<div class="direct-chat-msg doted-border" data-user-id-msg="'+elem.idUser+'">\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '                            <span class="direct-chat-name float-left">' + elem.name + '</span>\n' +
                                    '                        </div>\n' +
                                    '                        <!-- /.direct-chat-info -->\n' +
                                    '                        <img alt="message user image" src="uploads/' + elem.avatar + '" class="direct-chat-img"><!-- /.direct-chat-img -->\n' +
                                    '                        <div class="direct-chat-text">\n'
                                    + elem.msg +
                                    '                        </div>\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '                            <span class="direct-chat-timestamp float-right">'+ elem.postedHour +'</span>\n' +
                                    '                        </div>\n' +
                                    '                        <div class="direct-chat-info clearfix">\n' +
                                    '\t\t\t\t\t\t<span class="direct-chat-img-reply-small float-left">\n' +
                                    '\n' +
                                    '\t\t\t\t\t\t</span>\n' +
                                    '                            <span class="direct-chat-reply-name">' + elem.categoryName + '</span>\n' +
                                    '                        </div>\n' +
                                    '                        <!-- /.direct-chat-text -->\n' +
                                    '                    </div>'
                                );
                            if($('[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + UserID + ']').length){
                                var Popup = $('[data-id-receiver-popup=' + elem.receiver + '][data-id-transmitter-popup=' + UserID + ']').find('[data-user-id-msg="'+UserID+'"]');
                                Popup.find('.direct-chat-name, .direct-chat-reply-name').addClass('myPersonnalMsg');
                                Popup.find('.direct-chat-text').addClass('myPersonnalMsgBg');
                            }
                        });
            },
			sendTo: function(e) {
                var elem = e.data;
                $('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup=' + elem.idReceiver + '],[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + elem.idTransmitter + ']').find('.direct-chat-messages')
						.append('<div class="chat-box-single-line" data-msg-time="'+elem.currTime+'">\n' +
							'<abbr class="timestamp">' + elem.posted + '</abbr>\n' +
							'</div>'
						)
						.append('<div class="direct-chat-msg doted-border" data-user-id-msg="'+elem.idTransmitter+'">\n' +
							'                        <div class="direct-chat-info clearfix">\n' +
							'                            <span class="direct-chat-name float-left">' + elem.name + '</span>\n' +
							'                        </div>\n' +
							'                        <!-- /.direct-chat-info -->\n' +
							'                        <img alt="message user image" src="uploads/' + elem.avatar + '" class="direct-chat-img"><!-- /.direct-chat-img -->\n' +
							'                        <div class="direct-chat-text">\n'
							+ elem.msg +
							'                        </div>\n' +
							'                        <div class="direct-chat-info clearfix">\n' +
							'                            <span class="direct-chat-timestamp float-right">'+ elem.postedHour +'</span>\n' +
							'                        </div>\n' +
							'                        <div class="direct-chat-info clearfix">\n' +
							'\t\t\t\t\t\t<span class="direct-chat-img-reply-small float-left">\n' +
							'\n' +
							'\t\t\t\t\t\t</span>\n' +
							'                            <span class="direct-chat-reply-name">'+elem.categoryName+'</span>\n' +
							'                        </div>\n' +
							'                        <!-- /.direct-chat-text -->\n' +
							'                    </div>'
						);
                if($('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup=' + elem.idReceiver + '],[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + elem.idTransmitter + ']').find('.direct-chat-messages').length){
                    var PopUpContainer = $('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup=' + elem.idReceiver + '],[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + elem.idTransmitter + ']').find('.popup-messages');
                    var PopUp =  $('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup=' + elem.idReceiver + '],[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + elem.idTransmitter + ']').find('.direct-chat-messages');
                    var last = $(PopUp).children().eq(-1);
                    var beforeLast = $(PopUp).children().eq(-2);
                    var beforeBeforeLast = $(PopUp).children().eq(-3);
                    var screenHeight = $(PopUpContainer)[0].scrollHeight;
                    var elementScroll = $(PopUpContainer).scrollTop() + $(PopUpContainer).innerHeight();
                    var elementIsNotView = screenHeight-last.height()-beforeLast.height()-beforeBeforeLast.height();
                    if((elementScroll  <= elementIsNotView) && (elem.idTransmitter != UserID)) {
                        if ($("#"+elem.idReceiver+"lastMessagePopup").length) {

                        } else {
                            last.attr("id", elem.idReceiver+"lastMessagePopup");
                        }

                        if($(PopUpContainer).siblings(".notifPopup").is(":hidden")) {
                            $(PopUpContainer).siblings(".notifPopup").slideToggle(2000);
                        }
                        //newMessageCount++;
                        $(".chatWindow .chat .countNewMessages").html(newMessageCount);
                        if(!$(PopUp.find('.textNewMessage')).length){
                            $("<p class='textNewMessage text-danger text-center'>- Nouveau message -</p>").insertBefore("#" + elem.idReceiver + "lastMessagePopup");
                        }
                        $(PopUpContainer).siblings(".notifPopup").click(function(){
                            goToByScrollPopup(elem.idTransmitter, elem.idReceiver, elem.idReceiver+"lastMessagePopup");
                            $("#"+elem.idReceiver+"lastMessagePopup").removeAttr("id");
                            $(PopUpContainer).siblings(".notifPopup").fadeOut(2000);
                            $(PopUpContainer).find(".textNewMessage").fadeOut(7000);
                            //newMessageCount = 0;
                        });

                        $(PopUpContainer).on('scroll', function() {
                            if ($("#"+elem.idReceiver+"lastMessagePopup").length) {
                                //console.log($(this).scrollTop() + $(this).innerHeight());
                                //console.log($("#"+elem.idReceiver+"lastMessagePopup").offset().top + $(this).scrollTop() - $(".popup-head").offset().top);
                                if ($(this).scrollTop() + $(this).innerHeight() >= $("#"+elem.idReceiver+"lastMessagePopup").offset().top + $(this).scrollTop() - $(".popup-head").offset().top) {
                                    $("#"+elem.idReceiver+"lastMessagePopup").removeAttr("id");
                                    $(PopUpContainer).siblings(".notifPopup").fadeOut(2000);
                                    $(PopUpContainer).find(".textNewMessage").fadeOut(7000);
                                    //newMessageCount = 0;
                                }
                            }
                        });
                    }else{
                        scTopPopup($('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup=' + elem.idReceiver + '],[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + elem.idTransmitter + ']').find('.popup-messages'));
                    }
				}
				if(UserID === elem.idReceiver){
                	if(!$('[data-id-receiver-popup=' + elem.idTransmitter + '][data-id-transmitter-popup='+ elem.idReceiver+']').hasClass("popup-box-on")) {
                        var previewMessage = '';
                        if (elem.msg.length >= 50) {
                            previewMessage = elem.msg.substring(0, 47) + '...';
                        } else {
                            previewMessage = elem.msg;
                        }
                        $.notify('<span class="notify-transmitter" style="display: none">' + elem.idTransmitter + '</span><span class="notify-msg-posted" style="display: none">' + elem.currTime + '</span></span></span><p>Vous avez reçus un nouveau message de <strong>' + elem.name + '</strong></p><p>' + previewMessage + '</p>');
                    }
                    if(window_focus === false){
                        pageTitleNotification.on("Nouveau message !", 1000);
                    }
				}
                if($('[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + UserID + ']').length){
                    var Popup = $('[data-id-receiver-popup=' + elem.idReceiver + '][data-id-transmitter-popup=' + UserID + ']').find('[data-user-id-msg="'+UserID+'"]');
                    Popup.find('.direct-chat-name, .direct-chat-reply-name').addClass('myPersonnalMsg');
                    Popup.find('.direct-chat-text').addClass('myPersonnalMsgBg');
                }
			}
		}
	});
};
$(document).ready(function(){
    $('.msgs').on('scroll', function() {
        if ($("#lastMessage").length) {
            if ($(this).scrollTop() + $(this).innerHeight() >= $("#lastMessage").offset().top + $(this).scrollTop() - $(".chatWindow .chat").offset().top) {
            	console.log($("#lastMessage").offset().top + $(".chatWindow .chat .msgs").scrollTop() - $(".chatWindow .chat").offset().top);
                $("#lastMessage").removeAttr("id");
                $(".chatWindow .chat .notification").fadeOut(2000);
                newMessageCount = 0;
            }
        }
    });
	$(".chatWindow .chat #msgForm").on("submit", function(e){
        $('.sk-fading-circle').css({'display':'inline-block'});
        $('.buttonSendChat').hide();
		e.preventDefault();
		var form = $(this);
		var val	 = $(this).find("input[type=text]").val();
		if(val != "" && typeof val !== "undefined"){
			ws.send("send", {"msg": val});
			form[0].reset();
		}
	});

	$(".chatWindow .chatbox .infoConnection .status").on("click", function(){
		if($(this).text() == "Hors ligne"){
			connect();
		}
	});

	setInterval(function(){
		ws.send("onliners");
	}, 4000);
	connect();

    $(document).delegate('.infoUserOnline','click',function () {
		var idUser = $(this).attr('data-user-id');
		if(UserID != idUser) {
		    if(!$('[data-id-receiver-popup=' + idUser + ']').hasClass('popup-box-on')) {
                if (verifyPopAvailability().includes(false)) {
                    $('[data-id-receiver-popup=' + idUser + ']').addClass('popup-box-on');
                    $('[data-id-receiver-popup=' + idUser + ']').animate({
                        opacity: 1,
                        bottom: '0px',
                        right: availabilityPopup(idUser)
                    }, 'slow');
                    if(!$('[data-id-receiver-popup=' + idUser + ']').hasClass('popup-box-already-on')) {
                        var firstMsg = $('[data-id-receiver-popup=' + idUser + ']').find('.chat-box-single-line').first().attr('data-msg-time');
                        ws.send("fetchPrivate", {"idReceiver": idUser, "firstMsg": firstMsg});
                        setTimeout(function(){scTopPopup($('[data-id-receiver-popup=' + idUser + ']').find('.popup-messages'))}, 1000);
                    }
                    $('[data-id-receiver-popup=' + idUser + ']').addClass('popup-box-already-on');
                    //('.field_messages_user_'+idUser).bind("input",  myEmoji); Appel une fonction pour parser du texte en emoji, moins chargé que le plugin emojionearea
                    var targetPopup = $('[data-id-receiver-popup=' + idUser + ']').find('.popup-messages');
                    var firstMessage = $('[data-id-receiver-popup=' + idUser + ']');
                    infiniteScroll(targetPopup, firstMessage, idUser);
                }else{
                    $.alert({
                        title: 'Le nombre maximum de Pop-Up ouvert est atteint !',
                        content: 'Veuillez fermer un Pop-Up !',
                        type: 'red',
                        typeAnimated: true,
                        closeIcon: true,
                        animationBounce: 2,
                        backgroundDismiss: true,
                        draggable: true,
                        icon: 'fas fa-exclamation-triangle',
                        theme: 'modern',
                        columnClass: 'col-xs-10'
                    });
                }
            }
		}
	});

    $(document).delegate('[data-notify=container]','click',function (e) {
        var idUser = $(this).attr('data-notify-open');
        if (!$(e.target).is('button[data-notify=dismiss]')){
            if (verifyPopAvailability().includes(false)) {
                $('[data-id-receiver-popup=' + idUser + ']').addClass('popup-box-on');
                $('[data-id-receiver-popup=' + idUser + ']').animate({
                    opacity: 1,
                    bottom: '0px',
                    right: availabilityPopup(idUser)
                }, 'slow');
                if(!$('[data-id-receiver-popup=' + idUser + ']').hasClass('popup-box-already-on')) {
                    var firstMsg = $('[data-id-receiver-popup=' + idUser + ']').find('.chat-box-single-line').first().attr('data-msg-time');
                    ws.send("fetchPrivate", {"idReceiver": idUser, "firstMsg": firstMsg});
                }
                $('[data-id-receiver-popup=' + idUser + ']').addClass('popup-box-already-on');
                var targetMsg = $(this).attr('data-notify-posted');
                var uuid = guid();
                $('[data-id-receiver-popup=' + idUser + ']').find('[data-msg-time="'+targetMsg+'"]').attr('id', uuid);
                setTimeout(function() {
                    goToByScrollPopup(UserID, idUser, uuid);
                    $('[data-id-receiver-popup=' + idUser + ']').find('[data-msg-time="'+targetMsg+'"]').removeAttr('id');
                    },
                    1000);
                //('.field_messages_user_'+idUser).bind("input",  myEmoji); Appel une fonction pour parser du texte en emoji, moins chargé que le plugin emojionearea
                var targetPopup = $('[data-id-receiver-popup=' + idUser + ']').find('.popup-messages');
                var firstMessage = $('[data-id-receiver-popup=' + idUser + ']');
                infiniteScroll(targetPopup, firstMessage, idUser);
            }else{
                $.alert({
                    title: 'Le nombre maximum de Pop-Up ouvert est atteint !',
                    content: 'Veuillez fermer un Pop-Up !',
                    type: 'red',
                    typeAnimated: true,
                    closeIcon: true,
                    animationBounce: 2,
                    backgroundDismiss: true,
                    draggable: true,
                    icon: 'fas fa-exclamation-triangle',
                    theme: 'modern',
                    columnClass: 'col-xs-10'
                });
            }
        }
    });

	$(document).delegate('#removeClass', 'click', function (e) {
        var idUser = $(e.target ).closest('.popup-box-on').attr('data-id-receiver-popup');
        $(e.target ).closest('.popup-box-on').animate({
            opacity: 0,
            bottom: '-415px'
        }, 'slow', function(){availabilityPopupRemove(idUser); $(e.target ).closest('.popup-box-on').removeClass('popup-box-on')});
	});

    $(document).delegate('.buttonFileUpload', 'click', function(e) {
        $(this).siblings(".fileupload").trigger('click');
        /*jslint unparam: true */
        /*global window, $ */
        var target = $(e.target ).closest('#qnimate').attr('data-id-receiver-popup');
        uploadFile(target);
    });
    $('.chatWindow .chat .msgs')
        .append('<svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n' +
            '       viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">\n' +
            '       <circle fill="#007bff" stroke="none" cx="6" cy="50" r="6">\n' +
            '           <animateTransform\n' +
            '               attributeName="transform"\n' +
            '               dur="1s"\n' +
            '               type="translate"\n' +
            '               values="0 15 ; 0 -15; 0 15"\n' +
            '               repeatCount="indefinite"\n' +
            '               begin="0.1"/>\n' +
            '               </circle>\n' +
            '               <circle fill="#343a40" stroke="none" cx="30" cy="50" r="6">\n' +
            '           <animateTransform\n' +
            '               attributeName="transform"\n' +
            '               dur="1s"\n' +
            '               type="translate"\n' +
            '               values="0 10 ; 0 -10; 0 10"\n' +
            '               repeatCount="indefinite"\n' +
            '               begin="0.2"/>\n' +
            '       </circle>\n' +
            '       <circle fill="#28a745" stroke="none" cx="54" cy="50" r="6">\n' +
            '          <animateTransform\n' +
            '               attributeName="transform"\n' +
            '               dur="1s"\n' +
            '               type="translate"\n' +
            '               values="0 5 ; 0 -5; 0 5"\n' +
            '               repeatCount="indefinite"\n' +
            '               begin="0.3"/>\n' +
            '       </circle>\n' +
            '     </svg>');
});
