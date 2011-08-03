<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style type="text/css">
      * {margin:0px; padding: 0px;}
      body {color: #000; font-family: "lucida grande",tahoma,verdana,arial,sans-serif; font-size: 11px; margin:10px; background-color:#ffe;}
      img {border:0px; margin: 0;}
      .container {width: 970px; margin:15px auto 15px; padding:10px;}
      .thumb {float:left; margin:25px 25px 25px 25px; padding: 10px 20px 10px 20px; background:#fff; -moz-box-shadow: 0px 1px 6px #666;
              -webkit-box-shadow: 0px 1px 6px #666;
              box-shadow: 0px 1px 6px #666;
      }
      img.mini {margin:0 4px 8px 0; vertical-align:top; float:left;}
      h1 {font-size:28px;}
      h2 {padding:20px; margin: 10px 0 0 0; font-size:24px; background-color: #443F41; padding: 20px; color:#fff; font-weight:normal;}
      .clear{clear:both;}
      .time {color:#888}
    </style>
 
  </head>
 
  <body>
 
<?php
 
$client_id = 93ccf3a9f7924a6b8e33cc5234cebc50; //your client-id here
 
$tag = food; //your tag here
 
  echo "<div class=\"container\">";
  echo "<h2>#$tag</h2>";  
 
  $cachefile = "instagram_cache/$tag.cache";
  if (file_exists($cachefile) && time()-filemtime($cachefile)<3600) {
    $contents = file_get_contents($cachefile);
  } else {
    $contents = file_get_contents("https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$client_id");
    file_put_contents($cachefile, $contents);
  }
 
  $json = json_decode($contents, true);
 
  foreach ($json["data"] as $value) {
 
    echo echoimage($value);
 
  }
 
  echo "<br class=\"clear\"/></div>";
 
function echoimage($value) {
 
  $thumb = $value["images"]["thumbnail"]["url"];
  $link = $value["link"];
  $time = date("d/m/y", $value["created_time"]);
  $nick = $value["user"]["username"];
  $avatar = $value["user"]["profile_picture"];
 
  return "<div class=\"thumb\"><img src=\"$avatar\" width=\"32\" class=\"mini\"/> $nick<br/> <span class=\"time\">$time</span><br/><a href=\"$link\"><img src=\"$thumb\"/></a></div>";
 
}
 
?>
 
  </body>
</html>