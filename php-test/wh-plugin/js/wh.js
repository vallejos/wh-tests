var WHP_MIN_LENGTH = 3;

jQuery(document).ready(function() {
    jQuery('#whp-search').keyup(function() {
        var text = jQuery('#whp-search').val();
        if (text.length >= WHP_MIN_LENGTH) {
            console.log('text=',text);
            jQuery('#whp-search').addClass('whp-loading');

            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : whp_search_ajax.ajax_url,
                data : {action: "whp_search_ajax", search : text}
            })
            .done(function( data ) {
                jQuery('#whp-results').html('');
                var results = data;
                jQuery(results).each(function(key, value) {
                    jQuery('#whp-results').append(
                        '<div class="item" slug="' + value.slug + '">' + value.location + '</div>'
                    );
                });

                jQuery('.item').click(function() {
                    var item = jQuery(this).html();
                    var slug = jQuery(this).attr('slug');

                    jQuery('#whp-search').val(item);
                    jQuery('#whp-search').attr('slug', slug);
                    jQuery('#whp-search').select();
                });

            });
        } else {
            jQuery('#whp-search').removeClass('whp-loading');
            jQuery('#whp-results').html('');
        }
    });

    jQuery('#whp-search').blur(function() {
            jQuery('#whp-results').fadeOut(450);
            jQuery('#whp-search').removeClass('whp-loading');
        })
        .focus(function() {
            if (jQuery('#whp-search').val().length >= WHP_MIN_LENGTH) {
                jQuery('#whp-search').addClass('whp-loading');
            }
            jQuery('#whp-results').show();
        })
        .keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var w = window.location;
                var url = w.protocol+'//'+w.hostname+(w.port ? ':'+w.port: '');
                var slug = jQuery('#whp-search').attr('slug');
                if (slug) {
                    window.open(url+'/'+slug, '_self');
                }
            }
            event.stopPropagation();
        });
});
