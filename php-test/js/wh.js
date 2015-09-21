var MIN_LENGTH = 3;

$(document).ready(function() {
    $('#search').keyup(function() {
        var text = $('#search').val();
        if (text.length >= MIN_LENGTH) {
            $('#search').addClass('loading');
            $.post(
                'wh.php', {
                    search: text
                }
            )
            .done(function( data ) {
                $('#results').html('');
                var results = jQuery.parseJSON(data);
                $(results).each(function(key, value) {
                    $('#results').append(
                        '<div class="item" slug="' + value.slug + '">' + value.location + '</div>'
                    );
                });

                $('.item').click(function() {
                    var item = $(this).html();
                    var slug = $(this).attr('slug');

                    $('#search').val(item);
                    $('#search').attr('slug', slug);
                    $('#search').select();
                });
            });
        } else {
            $('#search').removeClass('loading');
            $('#results').html('');
        }
    });

    $('#search').blur(function() {
            $('#results').fadeOut(450);
            $('#search').removeClass('loading');
        })
        .focus(function() {
            if ($('#search').val().length >= MIN_LENGTH) {
                $('#search').addClass('loading');
            }
            $('#results').show();
        })
        .keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var w = window.location;
                var url = w.protocol+'//'+w.hostname+(w.port ? ':'+w.port: '');
                var slug = $('#search').attr('slug');
                if (slug) {
                    window.open(url+'/'+slug, '_self');
                }
            }
            event.stopPropagation();
        });
});
