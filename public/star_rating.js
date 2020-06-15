//
//  star_rating.js - the search results star rating system
//      Author: Sean
//

var movie_id = 0;

// function takes integer (1-5)
//  and will fill the stars up to
//  the one which was clicked
function rate(x) {

    var stars = document.getElementsByClassName("star");

    // get the clicked element, so we can update the star 
    //  rating from this method
    var e = $("[value=" + movie_id + "]");
    var currentText = e.parent().html();
    // update star ratingon UI
    e.parent().html(x + currentText.substring(1));

    // insert rating into DB
    insert_rating(x, movie_id);

    for (i = 0; i < x; i++) {
        stars[i].classList.add("checked");
    }

    for (i = x; i < 5; i++) {
        stars[i].classList.remove("checked");
    }
}

// insert_rating will POST variables to php file,
//  and from this file the database is updated
function insert_rating(stars, movie_id) {
    var id = document.getElementById("member_id").innerHTML.trim();
    $.ajax({
        url: 'rate.php',
        type: 'POST',
        data: {
            'stars':stars, 'movie_id':movie_id, 's_id':id
        },
        success: function(data) {
            console.log(data);
        }
    });
}

$(window).on('resize', function() {
    $("#star-rating").fadeOut(50);
});

$(document).ready(function() {

    $("body").delegate('.rater', 'click', function(event) {
        var pos = $(this).offset();
        var w   = $('#star-rating').outerWidth();
        var h   = $('#star-rating').outerHeight();
        // amt of stars 
        var amt = $(this).parent().text()[0];

        if (amt != 1 && amt != 2 && amt != 3 && amt != 4 && amt != 5) {
            console.log(amt);
            amt = 0;
        }

        var stars = document.getElementsByClassName("star");

        try {
            for (i = 0; i < amt; i++) {
                stars[i].classList.add("checked");
            }
            for (i = amt; i < 5; i++) {
                stars[i].classList.remove("checked");
            }
        } catch (err) {
            amt = 0;
        }
        


        movie_id = $(this).attr("value");

        $("#star-rating").show();
        $("#star-rating").css({
            top: (pos.top - (h/4.5)) + "px",
            left: (pos.left - w - 20) + "px"
        }); 

    });

    $("body").on('click', function(e) {
        console.log($(e.target));
        if (!$(e.target).is('span')) {
            $("#star-rating").fadeOut();
        }
    });

    $('#star-rating').click(function() {
        $("#star-rating").delay(250).fadeOut();
    });
});