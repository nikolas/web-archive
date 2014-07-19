<?php
require_once('config.php');
require_once('db.php');
$mysqli = db_connect();

echo '<div id="sidebar">';
$bins = db_get_all($mysqli);
foreach ($bins as $bin) {
  $url = "{$GLOBALS['site_url']}{$bin['url']}";
  $link = substr($bin['text'], 0, 32);
  echo "<a href=\"$url\">$link</a>";
  echo '<br />';
}
echo '</div>';

$id = preg_filter('/\/(.*)\/(.*)/', '\2', $_SERVER['REQUEST_URI']);
if (!empty($id)) {
  $bin = db_get($mysqli, $id);
}

if ($_POST) {
  $unique_id = uniqid();
  $content = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['content']));

	//$content = str_replace(array('\r\n', '\n', '\r'), '<br />', $content);

  $query = "INSERT INTO bins SET text='$content', url='$unique_id'";
  $mysqli->query($query) or die($mysqli->error());

  $url = "{$GLOBALS['site_url']}$unique_id";
  header("Location: $url");
  exit;
}

?>

<!doctype html><html>
<head>
  <title>Dorbandt</title>
  <link href="/css/blueprint-css/blueprint/screen.css" media="screen" rel="stylesheet" type="text/css" />
  <link href="/css/blueprint-css/blueprint/print.css" media="print" rel="stylesheet" type="text/css" /> 
  <!--[if lt IE 8]><link href="/css/blueprint-css/blueprint/ie.css" media="screen" rel="stylesheet" type="text/css" /><![endif]--> 
  <style type="text/css">
    body {
      background: url('http://www.irishviews.com/purple-sky5.jpg');
      color: white;
    }

    a {
      color: white;
      text-decoration: none;
    }

    a:hover {
      color: white;
      text-decoration: underline;
    }

    #sidebar {
      float: right;
      margin: 2em;
    }

    form * {
    display: block;
    margin: 1em;
    }

    textarea {
      background: url('http://www.irishviews.com/purple-sky4.jpg');
      color: white;
      font-family: helvetica, arial, sans-serif;
      font-weight: bold;
      font-size: 2em;
    }

    #wizard {
      cursor: pointer;
    }

    #wizard:active {
      -moz-border-radius:    1000px;
      -webkit-border-radius: 1000px;
      border-radius:         1000px;
    }
  </style>
</head>
<body>

<div height="100%" width="100%" style="background: url('http://www.google.com/images/logos/ps_logo2.png');"></div>
<form method="post">
  <textarea name="content"><?php if (isset($bin)) echo $bin['text']; ?></textarea>
  <input type="submit">
</form>

<center>
  <img id="wizard" src="http://s3.amazonaws.com/bigchan-prod/original/29-12-2010_17-33-00_wizard.png" />
</center>

</body></html>
