<?php
define("ICONDIR",'/img/'.($_GET['set'] != '' ? $_GET['set'] : 'silk').'/');
define("ROOT",($_SERVER['PWD'] != '' ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT']));
$banned = array('.', '..', '.DS_Store', 'Thumbs.db');

if ($_POST['createsprite'] == 'true') {
	include('generate.php');
}

echo ICONDIR;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>Icon Sprite Generator</title>
	<link rel="shortcut icon" href="/img/favicon.ico" />
	<link rel="stylesheet" href="/css/style.css" media="screen" />
	<!--[if IE 6]>
	<link rel="stylesheet" href="/css/ie6.css" />
	<![endif]-->
	<script src="/js/jquery.min.js"></script>
	<script src="/js/common.js"></script>
	<!--[if (gte IE 5.5)&(lte IE 8)]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="/js/selectivizr.js"></script>
	<![endif]-->
</head>

<body>

<header>
    <h1>Icon Sprite Generator</h1>
    <p>You can see an example of IconSprite.com in action on our <a href="/sample.html" target="_blank">sample page</a>.</p>
    <form action="" method="get" id="set-select">
    	<label>Please select an icon set</label>
    	<select name="set">
 <?php
if ($sets = opendir(ROOT.'/img/')) {
	while (($set = readdir($sets)) !== false) {
		if (!in_array($set, $banned) && is_dir(ROOT.'/img/'.$set)) {
			echo "\t\t".'<option value="'.$set.'">'.$set.'</option>'."\r\n";
		}
	}
}
 ?>
    	</select>
    	<input type="submit" value="Go">
    </form>
    <div id="social-media">
      <a href="http://twitter.com/share?related=chris5marsh&via=chris5marsh&text=Check%20this%20out&url=http://iconsprite.com/" id="twitter">Tweet this</a>
      <a href="http://www.facebook.com/l.php?u=http://iconsprite.com/" id="facebook">Like this on Facebook</a>
      <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://iconsprite.com/" id="google">Share this on Google Plus One</a>
      <a href="http://www.linkedin.com/submit?url=http://iconsprite.com/" id="linkedin">Share this on LinkedIn</a>
      <a href="http://www.reddit.com/submit?url=http://iconsprite.com/" id="reddit">Share this on Reddit</a>
      <a href="http://www.digg.com/submit?url=http://iconsprite.com/" id="digg">Digg this</a>
      <a href="http://www.stumbleupon.com/submit?url=http://iconsprite.com/" id="stumbleupon">Share this on StumbleUpon</a>
      <a href="http://www.delicious.com/save?v=5&noui&jump=close&title=Check%20this%20out&url=http://iconsprite.com" id="delicious">Bookmark this with Delicious</a>
    </div>
</header>

<div id="content">
<?php
if (isset($error)) {
	echo '<p class="error">'.$error['message'].'</p>';
}
?>
<form action="" method="post" id="iconform">
<input type="hidden" name="createsprite" value="true" />
<fieldset>
<?php
$files = array();
$structure = '<ul>'."\r\n";
$currentGroup = '';
if ($dir = opendir(ROOT.ICONDIR)) {
	while (($filename = readdir($dir)) !== false) {
		if (!in_array($filename, $banned) && is_file(ROOT.ICONDIR.$filename)) {
			$files[] = $filename;
		}
	}
	sort($files);
	foreach($files as $i => $file) {
		$name = substr($file,0,-4);
		$thisGroup = explode('_', substr($file,0,-4), 2);
		$nextGroup = explode('_', substr($files[$i+1],0,-4), 2);
		$itemStructure = '<li>';
		$itemStructure.= '<input type="checkbox" name="'.$name.'" id="'.$name.'"';
		if ($_POST[$name] == 'on') $itemStructure.= ' checked="checked"';
		$itemStructure.= ' />';
		$itemStructure.= '<label for="'.$name.'">';
		$itemStructure.= '<img src="'.ICONDIR.$file.'" alt="'.$name.'" />';
		$itemStructure.= $file;
		$itemStructure.= '</label>';
		$itemStructure.= '</li>';
		if ($thisGroup[0] == $currentGroup) {
			$structure.= "\t\t".$itemStructure."\r\n";
			if ($thisGroup[0] != $nextGroup[0]) {
				$structure.= "\t</ul>\r\n";
			}
		} else if ($thisGroup[0] == $nextGroup[0]) {
			$structure.= "\t<li>";
			$structure.= '<label><img src="'.ICONDIR.$file.'" alt="'.$thisGroup[0].'" />'.$thisGroup[0].'</label>'."\r\n";
			$structure.= "\t<ul>\r\n";
			$structure.= "\t\t".$itemStructure."\r\n";
			$currentGroup = $thisGroup[0];
		} else {
			$structure.= "\t".$itemStructure."\r\n";
		}
	}
}
$structure.= "\t".'</li>'."\r\n";
$structure.= '</ul>'."\r\n";
$structure.= '<div class="submit">'."\r\n";
$structure.= "\t".'<input type="submit" value="Go">'."\r\n";
$structure.= '</div>'."\r\n";

echo $structure;
?>
</fieldset>
</form>
</div>

<footer>
<p>&copy; Copyright 2012.</p>
<p>iconsprite.com is a resource from <a href="http://twitter.com/chris5marsh">Chris Marsh</a> based on the <a href="http://www.famfamfam.com/lab/icons/">FamFamFam icons</a> by <a href="http://twitter.com/markjames">Mark James</a>.</p>
</footer>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29377348-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>

</html>