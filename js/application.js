/**
 * Application Javascript for TakeoutRoulette
 */


// Refresh an individual grid item
function LoadLocation(gridnum){
    $.ajax({
        type: "GET",
        url: "ajax/getlisting.php",
        dataType: "json",
        success: function(data){
            UpdateGridItem(gridnum,data);
        }
    });
}

// Callback for ajax request for new grid item data
function UpdateGridItem(num,data){
    var parent = $("#grid" + num);
    $("h2 a",parent).attr("href",data['url']);
    $("h2 .text",parent).html(data['name']);
    if(data['image_url']){
        $(".biz-img",parent).attr("src",data['image_url']);
    }else{
        $(".biz-img",parent).attr("src","img/restaurant_food.png");
    }
    $(".biz-text",parent).html(data['snippet_text']);
    $(".stars", parent).attr("src",data['rating_img_url_large']);

    $(".call", parent).attr("onclick","tConnect('" + data['name'] + "', '" + data['id'] + "', '" + data['phone'] + "')");
    $(".more-reviews",parent).attr("onclick","moreReviews('" + data['id'] + "','" + data['name'] + "')");
    $(".reviewcount",parent).html(data['review_count'] + " reviews");
}

// Refresh all grid items
function refreshAll(){
    for(i=0;i<4;i++){
        LoadLocation(i);
    }
}
var currbiz = "";
var currbizname = "";
function moreReviews(biz,title){
    currbiz = biz;
    currbizname = title;
    $.fancybox({
        type: 'ajax',
        href: 'ajax/listingmore.php?business=' + biz,
        title: title,
        titlePosition: 'over'
    });
}

function tConnect(business,bizid,phone){
    currbiz = bizid;
    currbizname = business;
    Twilio.Device.connect({ "business": business, "phone": phone});
}

$(document).ready(function(){
    
    refreshAll();

    Twilio.Device.ready(function() {
        // Could be called multiple times if network drops and comes back.
        // When the TOKEN allows incoming connections, this is called when
        // the incoming channel is open.
    });
 
    Twilio.Device.offline(function() {
        // Called on network connection lost.
    });
 
    Twilio.Device.connect(function (conn) {
        // Called for all new connections
        $.fancybox({ href: 'ajax/incall.php?business=' + currbiz });
        $("#disconnect").css("display","block");
    });
 
    Twilio.Device.disconnect(function (conn) {
        // Called for all disconnections
        $.fancybox({ content: "<p style='text-align:center;'>Your call to " + currbizname + "<br />has ended</p>" });
        $("#disconnect").css("display", "none");
    });
 
    Twilio.Device.error(function (e) {
        alert(e.message + " for " + e.connection);
    });

    /*$.fancybox({ content: "Hang tight, we've hit a snag!<br /><br />\
                        Our business listings are pending approval for a higher rate on the Yelp API. <br />\
                        In the meantime the app cannot be expected to function as normal, unfortunately. <br />\
                        Thanks for your interest, ya'll come back now, y'hear!<br /><br />\
                        TakeoutRoulette (<a href='http://www.twitter.com/stephenyoungdev'>@stephenyoungdev</a>)"})*/

});