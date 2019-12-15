var place;
var nextSuperLike;

/**
 *
 * @param id
 */
function createCarousel(id) {

    $('#' + id).find(".owl-carousel").owlCarousel({
        items: 1,
        loop: true,
        center: true,
        nav : false,
        navText : ['<', '>']
    });
}


/**
 *
 */
function initialize() {
    var input = document.getElementById('geoloc');
    let autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {

        place = autocomplete.getPlace();

    });
}

google.maps.event.addDomListener(window, 'load', initialize);

$('#geoloc-form').on('submit',function (e) {

    e.preventDefault();

    $.post('api/profile/update',{
        pos : {
            'lat': place.geometry.location.lat(),
            'lon':  place.geometry.location.lng()
        }
    }).then(function () {

        loadProfile();
        loadMatches();

    })

});


$('#bot-form').on('submit',function(e) {

    let data = $(this).serializeArray();

    localStorage.setItem('bot',JSON.stringify(data));

    e.preventDefault();


});

$(document).ready(function () {
    loadMatches();
    loadGold();
    loadFavorites();
    loadProfile();
    loadBotInfos();
});


function loadBotInfos() {

    let bot = getLocalStorage('bot');

    $.each(bot,function (k, v) {
        let input = $('#bot-form #' + v.name);

        if(input.is(':checkbox')) {
            if(v.value == "on") {
                input.attr('checked','checked');
            }
        } else {
            input.val(v.value);
        }

    })

}


function getBotSetting(key) {

    let bot = getLocalStorage('bot');

    let value;

    $.each(bot,function (k, v) {
        if(v.name === key) {
            value = v.value;
        }
    });

    return value;
}

function loadFavorites() {

    let favorites = getLocalStorage('favorites');

    if(!Object.keys(favorites).length) {
        $('.favorite-profiles').hide();
    } else {
        $('.favorite-profiles').show();
    }

    displayMatches('favorites',{
        data : {
            results : favorites,
        }
    },true);


    let auto_like = getBotSetting('auto_super_like');

    if(auto_like) {
        // Check to auto like favorite every min
        setInterval(autoLikeFavorites,1000*60);
        autoLikeFavorites();
    }
}

function autoLikeFavorites() {

    if(nextSuperLike) {
        if(nextSuperLike > new Date()) {
            return;
        }
    }

    let user = $($('#favorites').find('.user')[0]);

    if(user) {
        user.find('[data-action="superlike"]').click();
    }
}

function loadProfile() {
    $.getJSON('api/profile',function (data) {

        let img = "https://maps.googleapis.com/maps/api/staticmap?center=" + data.data.user.pos.lat + "," + data.data.user.pos.lon + "&markers=" + data.data.user.pos.lat + "," + data.data.user.pos.lon +"&zoom=9&size=600x300&maptype=roadmap&key=" + google_api_key;

        $('.geoloc-img').attr('src',img);

        $('.profile').json_viewer(data,{
            collapsed: true,
        })

    }).fail(function (response) {
        handle401(response);
    })
}


function getLocalStorage(key) {
    let elem;

    try {
        elem = JSON.parse(localStorage.getItem(key));
    }catch (e) {
        elem = {};
    }

    if(!elem) {
        elem = {};
    }

    return elem;
}

function handle401(response) {
    if(response.status === 401) {
        location.href = 'login/cookies';
    }
}


/**
 *
 */
function loadMatches(wipe) {
    $.getJSON('api/matches',function (data) {

        displayMatches('matches',data,wipe);

        location.hash = '#matches-section';

        let start_bot = getBotSetting('auto');

        if(start_bot) {
            // Launch the bot after loading
            $('.start-bot').click();
            return;
        }

    }).fail(function (response) {
        handle401(response);
    })
}


/**
 *
 */
function loadGold() {
    $.getJSON('api/golds',function (data) {

        displayMatches('golds',data);

    }).fail(function (response) {
        handle401(response);
    })
}

$('body').on('click','.add-to-favorite',function () {

    let elem = $(this).closest('.user');

    let profile = elem.data('profile');

    let favorites = getLocalStorage('favorites');


    profile.isFavorite = true;

    favorites[profile.user._id] = profile;

    localStorage.setItem('favorites',JSON.stringify(favorites));

    elem.find('.add-to-favorite').hide();

    elem.find('.remove-from-favorite').show();

    displayAlert('add-to-favorite');

    loadFavorites();


});

$('body').on('click','.remove-from-favorite',function () {

    let elem = $(this).closest('.user');

    let profile = elem.data('profile');

    let favorites = getLocalStorage('favorites');

    profile.isFavorite = true;

    delete favorites[profile.user._id];

    localStorage.setItem('favorites',JSON.stringify(favorites));

    elem.find('.add-to-favorite').show();

    elem.find('.remove-from-favorite').hide();

    displayAlert('remove-from-favorite');

    loadFavorites();

});


/**
 *
 * @param id
 * @param data
 * @param wipe
 */
function displayMatches(id,data,wipe)
{
    let wrapper = $('#' + id);

    let template = $('.'+ id +'-template');

    if(true === wipe) {
        wrapper.html('');
    }

    $('#'+ id +'-nb').html(data.data.results.length);

    $.each(data.data.results,function (k, v) {
        let elem = $(template.html());

        let date = new Date(v.user.birth_date);

        if(v.isFavorite) {
            elem.find('.add-to-favorite').hide();
        } else {
            elem.find('.remove-from-favorite').hide();
        }

        elem.find('.user').data('id',v.user._id);
        elem.find('.user').data('profile',v);
        elem.find('.user').data('s_number',v.s_number);

        elem.find('.name').html(v.user.name);
        if(v.user.bio) {
            elem.find('.bio').html(v.user.bio);
        }else {
            elem.find('.bio-wrapper').remove();
        }


        elem.find('.date').html(date.getFullYear());
        elem.find('.distance').html(v.distance_mi);

        if(v.user.jobs && v.user.jobs[0] && v.user.jobs[0].title) {
            elem.find('.job').html(v.user.jobs[0].title.name);
        } else {
            elem.find('.job-wrapper').remove();
        }
        if(v.user.schools && v.user.schools[0] && v.user.schools[0].name) {
            elem.find('.school').html(v.user.schools[0].name);
        } else {
            elem.find('.school-wrapper').remove();
        }


        elem.find('.id').html(v.user._id);

        elem.find('.owl-carousel').html('');

        $.each(v.user.photos,function(k,p) {

            let img_elem = $('<img src="" alt="" class="img-responsive">');

            img_elem.attr('src',p.processedFiles[1].url);

            elem.find('.owl-carousel').append(img_elem);
        });

        wrapper.append(elem);
    });

    createCarousel(id);
}


$('.like-all').on('click',function () {

    let buttons = $('[data-action="like"]');

    let i = 0;
    let $interval = setInterval(function () {

        if(buttons[i]) {
            $(buttons[i]).click();
        } else {
            clearInterval($interval);
        }
        i++;
    },1500);

});

$('.start-bot').on('click',function () {

    let profiles = $('#matches .user');


    profiles.each(function (k,profile) {
        doBotAction(profile);
    });

    profiles = $('#matches .to-pass');

    let i = 0;

    let $interval = setInterval(function () {

        if(profiles[i]) {
            $(profiles[i]).find('[data-action="unlike"]').click();

        } else {
            clearInterval($interval);
        }
        i++;
    },500);
});


/**
 *
 * @param profile
 */
function doBotAction(profile)
{

    let bot = getLocalStorage('bot');

    let to_pass = false;

    profile = $(profile);

    let data = profile.data('profile');

    let bio = data.user.bio;

    let rex = [];

    $.each(bot,function (k, v) {

        if(v.name === 'only_with_photo' && v.value === 'on') {
            if(data.user.photos[0].id === 'unknown') {
                to_pass = true;
                return;
            }
        }
        if(v.name === 'only_with_description' && v.value === 'on') {
            if(!bio) {
                to_pass = true;
                return;
            }
        }



        if(to_pass) {
            return;
        }

        if(v.name === "words_to_exclude") {
            let words = v.value.split("\n");


            $.each(words,function (k, word) {

                word = word.trim();

                rex.push(new RegExp(word, "igm"));
            })

        }

    });

    rex.forEach(function (regex) {

        if(bio.match(regex)) {
            to_pass = true;
            return;
        }

    });

    if(to_pass) {
        profile.addClass('to-pass');
    }

}


$('.unlike-all').on('click',function () {

    let buttons = $('[data-action="unlike"]');

    let i = 0;
    let $interval = setInterval(function () {

        if(buttons[i]) {
            $(buttons[i]).click();
        } else {
            clearInterval($interval);
        }
        i++;
    },500);

});

$('body').on('click','.action',function () {

    let elem = $(this).closest('.user');
    let action = $(this).data('action');
    let s_number = elem.data('s_number');


    $.post('api/action/' + elem.data('id'),{
        action : action,
        s_number : s_number,
    })
        .then(function (data) {
            if(data.match) {
                displayAlert('match');
            }

            if(data.limit_exceeded === true) {
                displayAlert('limit_exceeded');

                if(action == 'superlike') {
                    nextSuperLike = new Date(data.super_likes.resets_at);
                }

                return;
            } else {
                displayAlert(action);
            }


            if(action == 'superlike') {
                elem.find('.remove-from-favorite').click();
                return;
            }

            elem.parent().remove();

            if($('#matches').find('.user').length === 0 ) {
                loadMatches();
            }

        }).fail(function (response) {
        handle401(response);
    })

});

$('body').on('click','.reload',function () {
    loadMatches();
});


function displayAlert(alert) {

    $('.alert-' + alert).fadeIn(200);

    setTimeout(function () {
        $('.alert-' + alert).fadeOut(200);
    },3000);

}