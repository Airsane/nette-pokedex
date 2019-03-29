$(document).ready(function () {
    let ajaxWorking = false;

    function addPokemons(pokemons) {
        $('.pokemons').append(pokemons);
    }
    $('select').change(function () {
        if (ajaxWorking) return;
        let val = $('select').val();
        editUrl(val);
        ajaxWorking = true;
        if (val.length > 0) {
            $.ajax({
                type: "POST",
                url: "request.php",
                data: {
                    type: "getPokemonsByType",
                    data: val
                }
            }).done(function (succ) {
                if (succ !== "") {
                    $('.pokemons').html('');
                    addPokemons(succ);
                    $('#pokemonCount').text($('.pokemon').length);
                    ajaxWorking = false;
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "request.php",
                data: {
                    type: "getPokemons",
                },
                success: function (succ) {
                    $('.pokemons').html('');
                    addPokemons(succ);
                    ajaxWorking = false;
                }
            })
        }
    })

    $(window).scroll(function () {
        let lastID = $('.load-more').attr('lastID');
        let type = $('.load-more').attr('type');
        if (ajaxWorking) return;
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 50 && (lastID != 0)) {
            ajaxWorking = true;
            if (type != "") {
                type = type.split(',');
                $.ajax({
                    type: "POST",
                    url: "request.php",
                    data: {
                        type: "getPokemonsByType",
                        data: type,
                        lastid: lastID
                    },
                    beforeSend: function () {
                        $('.load-more').show();
                    }
                }).done(function (succ) {
                    if (succ !== "") {
                        $('.load-more').remove();
                        addPokemons(succ);
                        $('#pokemonCount').text($('.pokemon').length);
                        ajaxWorking = false;
                    }
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('server not responding...');
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "request.php",
                    data: {
                        type: "getPokemons",
                        lastid: lastID
                    },
                    beforeSend: function () {
                        $('.load-more').show();
                    }
                }).done(function (succ) {
                    if (succ !== "") {
                        $('.load-more').remove();
                        addPokemons(succ);
                        $('#pokemonCount').text($('.pokemon').length);
                        
                        ajaxWorking = false;
                    }

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('server not responding...');
                });
            }
        }
    });
});