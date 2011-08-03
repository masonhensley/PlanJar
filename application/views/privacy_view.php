<!DOCTYPE html>

<html>
    <head>
        <link rel=stylesheet href="/application/assets/css/privacy.css" type="text/css" />
    </head>

    <body>

    curl -F 'client_id= 93ccf3a9f7924a6b8e33cc5234cebc50' \
     -F 'client_secret= dadc4b69b623419d9162ca7f21016710' \
     -F 'object=user' \
     -F 'aspect=media' \
     -F 'verify_token=myVerifyToken' \
     -F 'callback_url=http://YOUR-CALLBACK/URL' \
     https://api.instagram.com/v1/subscriptions/


    </body>
</html>