
// ************** Lightboxes ******************** //

// Session
contador = 0;
$(document).on('click', '.btn-session', function(e){
    e.preventDefault();
    
    // check speaker lightbox
    if($('.speaker-pop').is(':visible')){
        setTimeout(function() {
            $('.speaker-pop .close').trigger('click');
        }, 1000);
    }
    
    var datePicker = $('.date-picker').position();
    $('.session-info').css({
        'height':($(document).height())
    }).fadeIn();
    $('.session-info .lightbox').css({
        'top': $(window).scrollTop() + 102
    })
    $.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_session',
            'data-id': $(this).attr('data-id'),
        },
        success: function(data) {
            var speakers = '';
            var tracks = '';
            var post_edit_link = '';
            var end_time = '';
            
            if(data.post_edit_link && data.post_edit_link.length > 0)
                post_edit_link = '<a href="' + data.post_edit_link + '" class="edit_link">' + fudge_script_vars.editlink + '</a>';
            
            if(data.speakers)
                $.each(data.speakers, function(index, speaker) {
                    speakers += '<div class="session-speaker"> \
                                        ' + speaker.post_image + ' \
                                        <span data-id="' + speaker.post_id + '">' + speaker.post_title  +'</span> \
                                </div>';
                });
            if(data.tracks)
                $.each(data.tracks, function(index, track) {
                    tracks += '<a class="btn main-bkg-color tag" title="' + track.name + '" href="#" style="background-color:' + track.color + '!important;">' + track.name + '</a>';
                });
				

            if(data.end_time && data.end_time.length > 0)
                end_time = ' - ' + data.end_time;
            
            $(".session-info .lightbox").html(
                '<a title="' + fudge_script_vars.closewindow + '" class="close"></a> \
                <h1>' + data.post_title + '</h1> \
                ' + post_edit_link + ' \
                <p>' + data.post_content + '</p> \
                ' + tracks + '\
                <div class="session-details">\
                    <p><span>' + fudge_script_vars.location + '</span>' + data.location + '<p>\
                    <p><span>' + fudge_script_vars.date + '</span>' + data.date + '<p>\
                    <p><span>' + fudge_script_vars.time + '</span>' + data.time + end_time + '<p>\
                </div>\
                ' + speakers
                );
        }
    });
});

// Speaker

$(document).on('click', '#speakers .post, .session-info [data-id]', function(e){
    e.preventDefault();
    
    // check session lightbox
    if($('.session-info .lightbox').is(':visible')){
        setTimeout(function() {
            $('.session-info .lightbox .close').trigger('click');
        }, 1000);
    }
    
    var speaker = $('#speakers').position();
    $('.speaker-pop').css({
        'height':($(document).height())
    }).fadeIn();
    $('.speaker-pop .lightbox').css({
        'top': $(window).scrollTop() + 102
    })
    $.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_speaker',
            'data-id': $(this).attr('data-id')
        },
        success: function(data) {
            var sessions = '';
            var post_edit_link = '';
            
            if(data.post_edit_link && data.post_edit_link.length > 0)
                post_edit_link = '<a href="' + data.post_edit_link + '" class="edit_link">' + fudge_script_vars.editlink + '</a>';
            if(data.sessions)
                $.each(data.sessions, function(index, session) {
                    sessions += '<div class="session-speaker"> \
                                        <p class="date">' + session.date + '</p> \
                                        <p><span class="btn-session" data-id="' + session.post_id + '">' + session.post_title  +'</span></p> \
                                    </div>';
                });
            $(".speaker-pop .lightbox").html(
                '<a title="' + fudge_script_vars.closewindow + '" class="close"></a> \
                ' + data.post_image + ' \
                <div class="speaker-details"> \
                    <h1>' + data.post_title + '</h1> \
                    ' + post_edit_link + ' \
                    <p>' + data.post_content + '</p> \
                    <div class="details"> \
                        <p><span>' + fudge_script_vars.company + '</span>' + data.company + '<p> \
                        <p><span>' + fudge_script_vars.shortbio + '</span>' + data.short_bio + '<p> \
                        <p><span>' + fudge_script_vars.website + '</span><a href="http://' + data.website_url + '">' + data.website_url + '</a><p> \
                        <p><span>' + fudge_script_vars.twitter + '</span>' + data.twitter_username + '<p> \
                    </div> \
                </div> \
                <h2>' + fudge_script_vars.sessions + '</h2> \
                ' + sessions
                );
        }
    });
});

// Media

$('#event-media .filter [data-id]').click(function(e){
    $('#event-media .filter a:first').html($(this).html());
    $('#event-media .filter a:first').attr('data-id', $(this).attr('data-id'));
});

$('#event-media .media-category .btn, #event-media .filter a:not([data-ignore-click])').click(function(e){
    $('#event-media .media-category .btn').removeClass('active');
    $(this).addClass('active');
    $('#all-media').empty();
    
/*$('#all-media').fadeOut(1000, function(){
    });*/
});

$(document).on('click', '#event-media .btn-less', function(e){
    e.preventDefault();
    var curPage = $('#cur_media_page').val();
    var fromIndex = (curPage - 1) * media_limit;
    $('#event-media #all-media a.post:gt(' + (fromIndex - 1) + ')').remove();
    $('#cur_media_page').val(curPage - 1);
    if(curPage > 1)
        $('#event-media .btn-more').show();
    if(curPage - 1 < 2)
        $(this).hide();
   
    var last = $('#all-media a.post').last();
    window.scroll(last.offset().left, last.offset().top - 102);
});

$('#event-media .media-category .btn, #event-media .filter a[data-id]:not([data-ignore-click]), #event-media .btn-more').click(function(e){
    e.preventDefault(); 
    var req_page = 1;
    var data_id = 0;
    var data_limit = media_limit;
	var gif = '<div id="load-gif"></div>';

	$('#event-media .btn-more').append(gif);
	if($(this).hasClass('btn-more'))
        req_page = parseInt($('#cur_media_page').val()) + 1;
    if($(window).width() <= mobile_width)
        data_id = $('#event-media .filter a:first').attr('data-id');
    else
        data_id = $('#event-media .media-category .active').attr('data-id');
    
    if($(window).width() <= mobile_width)
        data_limit = 3;
    
    $.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_media',
            'data-id': data_id,
            'data-page': req_page,
            'data-limit': data_limit
        },
        success: function(data) {
            var medias = '';
            if(data.media)
                $.each(data.media, function(index, media) {
                    var img = media.post_image;
                    var detail = '<span class="view"></span>';
                    if(media.post_video != ''){
                        img = media.post_video;
                        detail = '';
                    }
                    medias += '<a class="post" href="' + media.post_image_big_url + '"> \
                                    ' + img + ' \
                                    ' + detail + ' \
                                    <h3>' + media.post_title + '</h3> \
                                    ' + media.post_content + ' \
                                </a>';
                });

            $('#all-media').append(medias);
			$('#load-gif').remove();
            jQuery('#cur_media_page').val(data.page);
            if(data.page > 1)
                $('#event-media .btn-less').show();
            else
                $('#event-media .btn-less').hide();
            if(data.more == 1)
                $('#event-media .btn-more').show();
            else
                $('#event-media .btn-more').hide();
            
        //$('#all-media').fadeIn();
        }
    });
});

// Schedule

$('#schedule .date-picker-mobile [data-timestamp]').click(function(){
    $('#schedule .date-picker-mobile a:first').html($(this).html());
    $('#schedule .date-picker-mobile a:first').attr('data-timestamp', $(this).attr('data-timestamp'));
});

$('#schedule .date-picker a').click(function(e){
    $('#schedule .date-picker a').removeClass('active');
    $('.filters .track a, .filters .location a').removeClass('active');
    $(this).addClass('active');
});

$('.filters .track a, .filters .location a, .date-picker a, .date-picker-mobile a:not([data-ignore-click])').click(function(e){
    $('.filters .track a, .filters .location a').removeClass('active');
    $(this).addClass('active');
    $('#schedule-sessions .session').remove();
});

$(document).on('click', '#schedule .btn-less', function(e){
    e.preventDefault();
    var curPage = $('#cur_page').val();
    var fromIndex = (curPage - 1) * schedule_limit;
    $('#schedule .session:gt(' + (fromIndex - 1) + ')').remove();
    $('#cur_page').val(curPage - 1);
    if(curPage > 1)
        $('#schedule .btn-more').show();
    if(curPage - 1 < 2)
        $(this).hide();
    var last = $('#schedule .session').last();
    window.scroll(last.offset().left, last.offset().top - 102);
});

$(document).on('click', '#schedule a[data-timestamp]:not([data-ignore-click]), #schedule a[data-location], #schedule a[data-track], #schedule .btn-more', function(e){
    e.preventDefault();
    var req_page = 1;
    var data_timestamp = 0;
    
    if($(this).hasClass('btn-more'))
        req_page = parseInt($('#cur_page').val()) + 1;
    
    if($(window).width() <= mobile_width)
        data_timestamp = $('#schedule .date-picker-mobile a:first').attr('data-timestamp');
    else
        data_timestamp = $('#schedule .date-picker a.active').attr('data-timestamp');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_schedule',
            'data-timestamp': data_timestamp,
            'data-location' :$('#schedule .location a.active').attr('data-location'),
            'data-track' :$('#schedule .track a.active').attr('data-track'),
            'data-page': req_page
        },
        success: function(data) {
            if(data.sessions){
                var last_time = $('.session:last .time').html();
                
                $.each(data.sessions, function(index, session) {    
                    var concurrent = '';
                    var time = session.time;
                    var speakers = '';
                    
                    if(last_time == session.time){
                        concurrent = 'concurrent';
                        time = '';
                    }
                    last_time = session.time;
                    
                    if(session.speakers)
                        $.each(session.speakers, function(index, speaker) {
                            speakers += '<li>' + speaker.post_image + '</li>';
                        });
                    
                    var html = '<div class="session ' + concurrent + '"> \
                                        <div class="location">' + session.location + '</div> \
                                        <div class="time ' + concurrent + '">' + time + '</div> \
                                        <div class="info"> \
                                            <a class="btn-session main-bkg-color" title="' + session.post_title + '" href="#" data-id="' + session.id + '" style="background-color: ' + session.background_color + '!important;">' + session.post_title + '</a> \
                                            <ul> \
                                                ' + speakers + ' \
                                            </ul> \
                                        </div> \
                                    </div>';
                    if($('#schedule .session').size() > 0)
                        $(html).insertAfter('#schedule .session:last');
                    else
                        $(html).insertBefore('#cur_page');
                });
                
                jQuery('#cur_page').val(data.page);
                if(data.page > 1)
                    $('#schedule .btn-less').show();
                else
                    $('#schedule .btn-less').hide();
                if(data.more == 1)
                    $('#schedule .btn-more').show();
                else
                    $('#schedule .btn-more').hide();
            }
        }
    });
});

// Speakers

$(document).on('click', '#speakers .btn-less', function(e){
    e.preventDefault();
    var curPage = $('#cur_speakers_page').val();
    var fromIndex = (curPage - 1) * speakers_limit;
    $('#all-speakers a.post:gt(' + (fromIndex - 1) + ')').remove();
    $('#cur_speakers_page').val(curPage - 1);
    if(curPage > 1)
        $('#speakers .btn-more').show();
    if(curPage - 1 < 2)
        $(this).hide();
   
    var last = $('#speakers a.post').last();
    window.scroll(last.offset().left, last.offset().top - 102);
});

$('#speakers .btn-more').click(function(e){
    e.preventDefault(); 
    var req_page = parseInt($('#cur_speakers_page').val()) + 1;
    var data_limit = speakers_limit;
	var gif = '<div id="load-gif"></div>';

    if($(window).width() <= mobile_width)
        data_limit = 3;
    $(gif).appendTo('#speakers .btn-more');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_speakers',
            'data-page': req_page,
            'data-limit': data_limit
        },
        success: function(data) {
            var speakers = '';
            if(data.speakers)
                $.each(data.speakers, function(index, speaker) {
                    speakers += '<a data-teste="sometttt" class="post" href="///" data-id="' + speaker.post_ID + '"> \
                                            ' + speaker.post_image + ' \
                                            <h3>' + speaker.post_company + '</h3> \
                                            <p> \
                                                <b>' + speaker.post_short_bio + ' </b>\
                                                <br/> \
                                                ' + speaker.post_title + ' \
                                            </p> \
                                        </a>';
                });
            $('#all-speakers').append(speakers);
		    $('#load-gif').remove();
            jQuery('#cur_speakers_page').val(data.page);
            if(data.page > 1)
                $('#speakers .btn-less').show();
            else
                $('#speakers .btn-less').hide();
            if(data.more == 1)
                $('#speakers .btn-more').show();
            else
                $('#speakers .btn-more').hide();
        }
    });
});

$('#media-grid').on('click', 'a.post', function(e){
    e.preventDefault();
    
    if($(window).width() <= mobile_width)
        return;
    
    var view = $('#event-media').position();
    var image = $(this).attr("href");
    var lb = $('<div />').addClass('lightbox-container media-lightbox').appendTo('body');
    var _lb = $('<div />').addClass('lightbox').appendTo(lb);
    var _close = $('<a />', {
        title: 'Close'
    }).addClass('close').appendTo( _lb );
    $('<img />', {
        src: image, 
        alt: ''
    }).appendTo( _lb );
    
    lb.css({
        'height':($(document).height())
    }).fadeIn();
    _lb.css({
        'top': $(window).scrollTop() + 102
    });

    _close.on('click', function(e){
        e.preventDefault();
        $(this).parents('.lightbox-container').fadeOut().remove();
    }); 
});

$(document).on('click','.lightbox .tag', function(e){
    e.preventDefault();
});

// Close it

$('.lightbox').on('click', '.close', function(e){
    e.preventDefault();
    $(this).parents('.lightbox-container').fadeOut();
});

$(document).on('click', '.lightbox-container', function(e){
    if(e.target == this){
        e.preventDefault();
        $(this).fadeOut();
    }
});

// ************** Mobile Navs ******************** //

$('header .mobile-nav-icon').click(function(e) {
    e.preventDefault();
    $('header ul').slideToggle();
});

// ************** Twitter ******************** //

!function(d,s,id){
    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
    if(!d.getElementById(id)){
        js=d.createElement(s);
        js.id=id;
        js.src=p+'://platform.twitter.com/widgets.js';
        fjs.parentNode.insertBefore(js,fjs);
    }
}(document, 'script', 'twitter-wjs');

// ************** DOC READY FUNCTIONS ******************** //

$(document).ready(function() {
    
    // Filter Drops
    
    if($(window).width() < 1024 ){
        $('.filter').click(function() {
            $(this).find('ul').toggle();
        });
    }

    // Contact Form AJAX
    
    $('form#contact-us').submit(function() {
        
        var hasError = false;
        $('form#contact-us .error').remove();
        
        $('.requiredField').each(function() {
            if($.trim($(this).val()) == '') {
                $(this).parent().append('<span class="error">' + fudge_script_vars.contact_fieldmissing + '</span>');
                $(this).addClass('inputError');
                hasError = true;
            } else if($(this).hasClass('email')) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(!emailReg.test($.trim($(this).val()))) {
                    $(this).parent().append('<span class="error">' + fudge_script_vars.contact_invalidemail + '</span>');
                    $(this).addClass('inputError');
                    hasError = true;
                }
            }
        });
        
        if(!hasError) {
            $('#contact .alert, #contact .info').remove();
            $.ajax({
                url: ajaxurl,
                data: $(this).serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if(data.sent == true)
                        $('form#contact-us').slideUp("fast", function() {
                            $('#contact-form').before('<p class="info">' + data.message + '</p>');
                        });
                    else
                        $('#contact-form').before('<p class="alert">' + data.message + '</p>');
                },
                error: function(data) {
                    $('#contact-form').before('<p class="alert">' + data.message + '</p>');
                }
            });
        }

        return false;   
    });

    /// Smooth Scrolling

    function filterPath(string) {
        return string
        .replace(/^\//,'')
        .replace(/(index|default).[a-zA-Z]{3,4}$/,'')
        .replace(/\/$/,'');
    }
    var locationPath = filterPath(location.pathname);
    var scrollElem = scrollableElement('html', 'body', 'document', 'window');
     
    $('a[href*=#]').not('#inscrevase-popup, .open-popup-link').each(function() {
        var thisPath = filterPath(this.pathname) || locationPath;
        if (  locationPath == thisPath
            && (location.hostname == this.hostname || !this.hostname)
            && this.hash.replace(/#/,'') ) {
            var $target = $(this.hash), target = this.hash;
            if (target) {
                $(this).click(function(event) {
                    var targetOffset = $target.offset().top - 102;
                    event.preventDefault();
                    $(scrollElem).animate({
                        scrollTop: targetOffset
                    }, 1800, function() {
                        });
                });
            }
        }
    });
     
    // use the first element that is "scrollable"
    function scrollableElement(els) {
        for (var i = 0, argLength = arguments.length; i <argLength; i++) {
            var el = arguments[i],
            $scrollElement = $(el);
            if ($scrollElement.scrollTop()> 0) {
                return el;
            } else {
                $scrollElement.scrollTop(1);
                var isScrollable = $scrollElement.scrollTop()> 0;
                $scrollElement.scrollTop(0);
                if (isScrollable) {
                    return el;
                }
            }
        }
        return 'body';
    } 
      
      
});