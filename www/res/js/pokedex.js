$(document).ready(function () {
    let ajaxWorking = false;

    function addPokemons(pokemons) {
        $('.pokemons').append(pokemons);
    }
    $(window).scroll(function () {
        let lastID = $('.load-more').attr('lastID');
        if (ajaxWorking) return;
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 50 && (lastID != 0)) {
            ajaxWorking = true;
            $.ajax({
                type: "POST",
                url: "request.php",
                data: {
                    type: "getUsersPokemons",
                    id:id,
                    lastid: lastID
                },
                beforeSend: function () {
                    $('.load-more').show();
                }
            }).done(function (succ) {
                if (succ !== "") {
                    addPokemons(succ);
                    $('#pokemonCount').text($('.pokemon').length);
                    $('.load-more').remove();
                    ajaxWorking = false;
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('server not responding...');
            });
        }
    });
});