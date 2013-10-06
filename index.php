
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>SongSource</title>

    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="stylesheet.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="results.php">
        <center><img src="logoMid.png"></img></center>
        <h1 class="form-signin-heading"><center>SongSource</center></h1>
        <p><center><p style="color:#666;">Now where is that song from...</p></center></p>
        <input type="text" class="form-control" placeholder="Song Name" name="songname" autofocus>
        <input type="text" class="form-control" placeholder="Lyric Exerpt" name="lyrics">
        <!-- <label class="checkbox">
          <input type="checkbox" value="remember-me">Option
        </label> -->
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Search</button>
      </form>

    </div> <!-- /container -->
  </body>
</html>
