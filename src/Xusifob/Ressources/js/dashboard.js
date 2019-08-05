var place;


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

$(document).ready(function () {
    loadMatches();
    loadGold();
    loadProfile();

});


function loadProfile() {
    $.getJSON('api/profile',function (data) {

        let img = "https://maps.googleapis.com/maps/api/staticmap?center=" + data.data.user.pos.lat + "," + data.data.user.pos.lon + "&markers=" + data.data.user.pos.lat + "," + data.data.user.pos.lon +"&zoom=9&size=600x300&maptype=roadmap&key=" + google_api_key;

        $('.geoloc-img').attr('src',img);

        $('.profile').json_viewer(data,{
            collapsed: true,
        })

    })
}


/**
 *
 */
function loadMatches() {
    $.getJSON('api/matches',function (data) {

        displayMatches('matches',data);

    });
}


/**
 *
 */
function loadGold() {
    $.getJSON('api/golds',function (data) {

        displayMatches('golds',data);

    });
}


function displayMatches(id,data)
{
    let wrapper = $('#' + id);

    let template = $('#'+ id +'-template');

    wrapper.html('');

    $('#'+ id +'-nb').html(data.data.results.length);

    $.each(data.data.results,function (k, v) {
        let elem = $(template.html());

        let date = new Date(v.user.birth_date);

        elem.find('.user').attr('data-id',v.user._id);
        elem.find('.user').attr('data-s_number',v.s_number);

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

            img_elem.attr('src',p.processedFiles[0].url);

            elem.find('.owl-carousel').append(img_elem);
        });

        wrapper.append(elem);
    });

    createCarousel(id);
}

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
            displayAlert(action);


            elem.parent().remove();

            if($('#matches').find('.user').length === 0 ) {
                loadMatches();
            }

        });

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