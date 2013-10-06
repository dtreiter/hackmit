<?php

$DEBUG = false;

require '../vendor/autoload.php';
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\DynamoDbClient;

// Instantiate the client with your AWS access keys
$aws = Aws\Common\Aws::factory('../config.php');
$client = $aws->get('dynamodb');

function get_song_info($q){
	$link = "http://ws.spotify.com/search/1/track.json?q=$q";
//	if(true){ var_dump($link); }
        $resp = file_get_contents($link);
        $resp = json_decode($resp);
	
        return $resp->{'tracks'}[0];
}

$q = $_GET['q'];
$esc_q = str_replace(" ", "+", $q);

$song_info = get_song_info($esc_q);
$song_id = $song_info->{"href"};
$song_name = $song_info->{"name"};
$album_name = $song_info->{"album"}->{"name"};

if($song_id != null) {
	//var_dump($song_id);

	$item = $client->getItem(array(
	    'TableName' => 'songs',
	    'Key' => array( 
		'song_id' => array( 
			'S' => $song_id 
		) 
	    )
	));

} 
if($DEBUG) { var_dump($song_id); }

$movie_names = $item["Item"]["movies"]['S'];
$movie_names = str_replace("'", '"', $movie_names);
$movie_names = json_decode($movie_names);
//print_r($movie_names);
//$movie_names2 = array(1, 2);
//$movie_names = eval($movie_names);

//print_r($movie_names);
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
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>

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
    <form class="form-inline" role="form" action="results.php">
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
        <div id="songname"><a href="https://play.spotify.com/track/<?= $song_id ?>"><?= $song_name ?></a> from <?= $album_name ?></div>
        <!-- <div id="lyrics"></div> -->
      </div>

      <table class="table table-striped">
        <tr>
          <td><h4>Title</h4></td>
          <td><h4>Director</h4></td>
          <td><h4>Media Type</h4></td>
        </tr>
	<?php 
	foreach($movie_names as $m){
	?>
        <tr>
          <td><?= $m ?></td>
          <td>Bob Joe</td>
          <td>TV Show</td>
        </tr>
	<?php
	}
	?>
      </table>

  <!-- code to looking song up from lyrics below -->
  <?
    INCLUDE 'simple_html_dom.php';
    $lyrics = $_GET['lyrics'];
    $lyrics = implode("+", explode(" ", $lyrics));
    $dom = file_get_html("http://www.lyricfind.com/services/lyrics-search/try-our-search/?q={$lyrics}");
    // alternatively use str_get_html($html) if you have the html string already...
    $counter = 0;
    foreach ($dom->find("h2") as $node)
    {
        if($counter == 3) {
          $song = $node->innertext;
          break;
        }
        $counter++;
    }
    echo $song;

  ?>

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
          document.getElementById("user-content").innerHTML += theParams["songname"] + "&quot";
        }
        if(theParams["lyrics"]) {
          document.getElementById("user-content").innerHTML += " from lyrics " + theParams["lyrics"];
          // var arr = $($("h2")[3]).text().split("-");
          // var artist = arr[0];
          // var title = arr[1];
          // document.getElementById("user-content").innerHTML += " title: " + title + " artist: " + artist;
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
