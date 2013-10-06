<?php

require 'vendor/autoload.php';
use Aws\DynamoDb\Enum\Type;

use Aws\DynamoDb\DynamoDbClient;

// Instantiate the client with your AWS access keys
$aws = Aws\Common\Aws::factory('../config.php');
$client = $aws->get('dynamodb');

function get_song_id($q){
        $resp = file_get_contents("http://ws.spotify.com/search/1/track.json?q=$q");
        $resp = json_decode($resp);
        return $resp->{'tracks'}[0]->{'href'};
}

$q = $_GET['q'];

$song_id = get_song_id(q);

$item = $client->getItem(array(
    'TableName' => 'songs',
    'Key' => array( 'song_id' => $song_id ) 
);

var_dump($item);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>SongSource Results</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="jquery.js"></script>

    <!-- Custom styles for this template -->
    <link href="grid.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/assets/js/html5shiv.js"></script>
      <script src="bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
    <form class="form-inline" role="form" action="results.html">
      <div class="form-group">
        <label class="sr-only" for="exampleInputEmail2">Email address</label>
        <input type="text" class="form-control" name="songname" placeholder="Song name">
      </div>
      <div class="form-group">
        <label class="sr-only" for="exampleInputPassword2">Password</label>
        <input type="text" class="form-control" name="lyrics" placeholder="Lyric Exerpt">
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
    </form>


      <div class="page-header">
        <h1><a href="index.html"><img src="logo.png"></img></a>SongSource Results</h1>
        <p id="user-content" class="lead">Media found using the song "<?= $q ?>"</p>
        <div id="songname"><?= $song_id ?></div>
        <div id="lyrics"></div>
      </div>

      <table class="table table-striped">
        <tr>
          <td><h4>Title</h4></td>
          <td><h4>Director</h4></td>
          <td><h4>Media Type</h4></td>
        </tr>
        <tr>
          <td>Inception</td>
          <td>Some Guy</td>
          <td>Film</td>
        </tr>
        <tr>
          <td>Some Show</td>
          <td>Bob Joe</td>
          <td>TV Show</td>
        </tr>
      </table>

    </div> <!-- /container -->

    <script type="text/javascript">
      var getParams = (function(a) {
          if (a == "") return {};
          var b = {};
          for (var i = 0; i < a.length; ++i)
          {
              var p=a[i].split('=');
              if (p.length != 2) continue;
              b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
          }
          return b;
      })(window.location.search.substr(1).split('&'));

      $(document).ready(function() {
        var theParams = getParams;
        if(theParams["songname"]) {
          document.getElementById("user-content").innerHTML += theParams["songname"];
          document.getElementById("user-content").innerHTML += "&quot"
        }
        if(theParams["lyrics"]) {
          document.getElementById("user-content").innerHTML += " from lyrics ";
          document.getElementById("user-content").innerHTML += theParams["lyrics"];
          var arr = $($("h2")[3]).text().split("-");
          var artist = arr[0];
          var title = arr[1];
          document.getElementById("user-content").innerHTML += " title: " + title + " artist: " + artist;
        }
        //$("#songname").html(theParams["songname"]);
        //$("#lyrics").html(theParams["lyrics"]);
      });
    </script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
