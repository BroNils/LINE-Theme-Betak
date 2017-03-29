<?php
set_time_limit(0);
error_reporting(0);
$saved = file_get_contents("savedlist.txt");
$saved_pecah = explode(PHP_EOL, $saved);
foreach($saved_pecah as $file){
	unlink($file);
}
unlink("savedlist.txt");
echo "DONE";
?>