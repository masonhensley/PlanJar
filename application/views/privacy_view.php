<!DOCTYPE html>

<html>
    <head>
        <link rel=stylesheet href="/application/assets/css/privacy.css" type="text/css" />
    </head>

    <body>


$(function() {

    var access_token = location.hash.split('=')[1];

    if (location.hash) {


    $.ajax({
        type: "GET",
        dataType: "jsonp",
        cache: false,
        url: "https://api.instagram.com/v1/users/[userid]/media/recent/?access_token=[access_token]",
        success: function(data) {

            for (var i = 0; i < 6; i++) {
        $(".instagram").append("<div class='instagram-placeholder'>
        <a target='_blank' href='" + data.data[i].link +"'><img class='instagram-image' src='" + data.data[i].images.thumbnail.url +"' /></a></div>");   
                }     
                 


} else {
    location.href="https://instagram.com/oauth/
    authorize/?display=touch&client_id=[clientid]
    &redirect_uri=[callbackuri]/&response_type=token"; 
    
}           
        }
    });
});

    </body>
</html>