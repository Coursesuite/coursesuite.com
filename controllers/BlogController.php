<?php

// files are stored in "blogentries"
// file names have the pattern
// yyyy-mm-dd-hh-mm-ss,published,entry-slug.md
// hh is in 24 hour format
// published can be anything that resolves to a boolean
// entries always end in .md and are rendered with ParsedownExtra (a markdown extra parser)
// all methods are static

class BlogController {

	private static function parse_entry($filename) {
		$n = explode(',',basename($filename));
		$result = [
			"date" => date_create_from_format('Y-m-d-H-i-s', $n[0]),
			"published" => boolval($n[1]),
			"slug" => $n[2],
			"title" => "",
			"tags" => "",
			"short" => "",
			"long" => ""
		];
		$data = file_get_contents($filename);
		$ar = preg_split('/^([=-])([ ]*\1){2,}[ ]*$/m', $data);
		foreach (explode(PHP_EOL, $ar[0]) as $line) {
			if (empty($line)) continue;
			if (strpos($line,"title:")!==false) {
				$result["title"] = trim(substr($line, 6));
			} else if (strpos($line,"tags:")!==false) {
				$result["tags"] = explode(',', substr($line, 5));
			}
		}
		$result["short"] = trim($ar[1]);
		$result["long"] = trim($ar[2]);
		return (object) $result;
	}

	private static function render_list($files) {
		echo "<div class='uk-section blog-list'><div class='uk-container'><div class='uk-column-1-2 uk-column-divider'>";
	    foreach ($files as $date => $entry) {
	    	$slug = $entry['slug'];
	    	$data = file_get_contents($entry['path']);
	    	$title = "Untitled";
	    	if (preg_match('/^title:[ ]*(.*)$/m', $data, $matches)) {
	    		$title = $matches[1];
	    	}
	    	$pdate = date_create_from_format('Y-m-d-H-i-s', $date)->format('j F, Y');
	    	echo "<p>", "<a href='/blog/{$slug}'>", $title, "</a>", "<br><small>", $pdate, "</small></p>";
	    }
		echo "</div></div></div>";
	}

	private static function render_entry($entry) {
		$Parser = new ParsedownExtra();
		echo "<div class='uk-section blog-post {$entry->slug}'><div class='uk-container'>";
		echo "<h2>", $entry->title, "</h2>";
		echo "<p class='uk-text-meta'>", $entry->date->format('j F, Y'), "</p>";
		echo $Parser->text($entry->short), $Parser->text($entry->long);
		echo "</div></div>";
	}

	public static function Entry($slug = "") {
		$root = realpath("../blogentries");
		if (empty($slug)) {
		    $fold = new DirectoryIterator($root);
		    $files = [];
		    foreach ($fold as $fi) {
		        if ($fi->isDot()) continue;
		        $filename = $fi->getFilename();
		        if (strpos($filename,',')===false) continue;
		        $fn = explode(',',$filename);
		        if (boolval($fn[1])===true) { // published
		        	$files[$fn[0]] = [
		        		"slug" => substr($fn[2],0, -3),
		        		"path" => $fi->getPathname()
		        	];
		        }
		    }
		    krsort($files);
		    self::render_list($files);
		} else {
			$file = glob($root . "/" . "*,{$slug}.md");
			if (isset($file[0]) && file_exists($file[0])) {
				$data = file_get_contents($file[0]);
				$entry = self::parse_entry($file[0]);
				self::render_entry($entry);
			} else {
				header("HTTP/1.0 404 File Not Found");
			}
		}
	}
}