<?php 

//from https://github.com/fusonic/opengraph
require "vendor/autoload.php";
use Fusonic\OpenGraph\Consumer;

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");


$site = $_GET['url'];
$exists = site_exists($site);

if ($exists != false){
	$consumer = new Consumer();
	if($consumer->loadUrl($site)){
		$object = $consumer->loadUrl($site);
	}
}

// print("<pre>".print_r($object,true)."</pre>"); 


// echo '<h2>Stuff</h2>';

// // Basic information of the object
// echo "Title: " . $object->title . '<br>';                // Getting started with Facebook Open Graph
// echo "Site name: " . $object->siteName . '<br>';         // YouTube
// echo "Description: " . $object->description . '<br>';    // Originally recorded at the Facebook World ...
// echo "Canonical URL: " . $object->url . '<br>';          // http://www.youtube.com/watch?v=P422jZg50X4

// // Images
// if ($object->images){
// 	$image = $object->images[0];
// 	echo '<img src="' . $image->url . '">';             // https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg
// 	echo "Image[0] height: " . $image->height;       // null (May return height in pixels on other pages)
// 	echo "Image[0] width: " . $image->width;         // null (May return width in pixels on other pages)
// }

// // Videos
// if ($object->videos){
// 	$video = $object->videos[0];
// 	echo "Video URL: " . $video->url;                // http://www.youtube.com/v/P422jZg50X4?version=3&autohide=1
// 	echo "Video height: " . $video->height;          // 1080
// 	echo "Video width: " . $video->width;            // 1920
// 	echo "Video type: " . $video->type;              // application/x-shockwave-flash
// }

// echo '<h2>JSON</h2>';
if($object){
	if (!$object->title){
		$object->title = page_title($site);
	}
	if (!$object->url){
		$object->url = $site;
	}
	$json = json_encode($object);
	echo json_encode($object)."\n";
}


//consider replacing w https://github.com/mpyw/opengraph to deal with php versions

function site_exists ($site){
	$file_headers = @get_headers($site);
	if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
	    return false;
	} else {
		return true;
	}
}


// function page_title($url) {
//         $fp = file_get_contents($url);
//         if (!$fp) 
//             return null;

//         $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
//         if (!$res) 
//             return null; 

//         // Clean up title: remove EOL's and excessive whitespace.
//         $title = preg_replace('/\s+/', ' ', $title_matches[1]);
//         $title = trim($title);
//         return $title;
//     }


//from https://stackoverflow.com/a/54595358/3390935
function page_title($url) {
  $title = false;
  if ($handle = fopen($url, "r"))  {
    $string = stream_get_line($handle, 0, "</title>");
    fclose($handle);
    $string = (explode("<title", $string))[1];
    if (!empty($string)) {
      $title = trim((explode(">", $string))[1]);
    } else {
    	return $url;
    }
  }
  return $title;
}