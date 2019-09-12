<?php
define("APP", realpath("."));

use PHPMailer\PHPMailer\PHPMailer;

require "../vendor/autoload.php";

$Router = new AltoRouter();

$Router->map('GET','/', 'home.html');
$Router->map('GET','/about/', 'about.md', 'About us');
$Router->map('GET','/services/', 'services.html', 'Services');
$Router->map('GET','/blog/[:slug]?', 'BlogController#Entry', 'Blog Entry');
$Router->map('GET','/blog/', 'BlogController#Entry', 'Blog');
$Router->map('GET','/support/', 'support.md', 'Support');
$Router->map('GET','/privacy/', 'privacy.md', 'Privacy');
$Router->map('GET','/terms/', 'terms.md', 'Terms & Conditions');
// $Router->map('GET|POST','/account/[a:method]?/[i:id]?/[*:rest]','AccountController#index','Manage account');

$path = realpath("../pages");

$match = $Router->match();
if ($match) {
	$fn = $match["target"];
	$page_title = $match["name"];
	require_once './header.php';
	if (strpos($fn,"#")!==false) { // static micro controller
		$ar = explode("#", $fn);
		require "../controllers/{$ar[0]}.php";
		call_user_func_array($ar, $match["params"]);
	} else if (strpos($fn,".md")!==false) { // markdown file
		$Parser = new ParsedownExtra();
		$name = preg_replace("/[^a-z]/",'-',strtolower($fn));
		echo '<div class="uk-section {$name}"><div class="uk-container">', $Parser->text(file_get_contents("{$path}/{$fn}")), '</div></div>';
	} else { // regular old html file
		include "{$path}/{$fn}";
	}
	require_once './footer.php';
} else {
  header("HTTP/1.0 404 Not Found");
}

// count the number of files changed in a directory in the given time
function badge($fold, $ago = "-1 week") {
	$path = realpath("./assets") . $fold;
	$week = strtotime($ago);
	$count = 0;
	foreach (new DirectoryIterator($path) as $value) {
		if ($value->isFile() && $value->getExtension()==="md") {
            $name = substr($value->getFilename(), 0, -3); // file name without .md extension
            $date = strtotime($name); // "2017-05-24 00:00:00.md" => 1495584000
            if ($date >= $week) {
				// } && $value->getMTime() >= $week) {
				$count++;
			}
		}
	}
	if ($count>0) {
		echo "<span class='uk-badge' title='New items in last 7 days'>{$count}</span>";
	}
}

?>