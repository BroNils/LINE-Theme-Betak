<?php
/*

*MAKE SURE THAT YOU DO AT YOUR OWN RISK

| COPYRIGHT (©) MILIK
|  -Zishe
|  -GoogleX
|  -W3layouts
| JANGAN DIHAPUS, DOSA !
| SPECIAL THANKS TO StackOverFlow Forum

*/
set_time_limit(0);
error_reporting(0);
$version = "2.0"; //DON'T TOUCH THIS !
$norand = rand();

function ambilKata($param, $kata1, $kata2){
    if(strpos($param, $kata1) === FALSE) return FALSE;
    if(strpos($param, $kata2) === FALSE) return FALSE;
    $start = strpos($param, $kata1) + strlen($kata1);
    $end = strpos($param, $kata2, $start);
    $return = substr($param, $start, $end - $start);
    return $return;
}
function getsource($url,$post=null) {
		$ch = curl_init($url);
		if($post != null) {
	 	 	curl_setopt($ch, CURLOPT_POST, true);
		  	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
		  	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		  	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		  	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		  	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		return curl_exec($ch);
		  	curl_close($ch);
}
function check_url($url){
	$data = ambilKata($url, 'http://', '/');
	$data2 = ambilKata($url, 'https://', '/');
	$datstr = strpos($url, 'http');
	if($datstr!==false){}else{die("Please use http or https !<br><br>~GoogleX");}
	if($data!=null && $data!="store.line.me"){
		$res = false;
		return $res;
	} elseif($data2!=null && $data2!="store.line.me"){
		$res = false;
		return $res;
	} else {
		$res = true;
		return $res;
	}
}
function get_theme_info($d){
	$cek = check_url($d);
	if($cek===FALSE){
		die("Mohon pastikan url yang anda masukan sudah benar !<br><br>~GoogleX");
	}
	$doc = getsource($d);
	$classname = array(
	"mdCMN08Desc",
	"mdCMN08Price",
	"mdCMN08Copy",
	"mdCMN08Ttl"
	);
	$domdocument = new DOMDocument();
    $domdocument->loadHTML($doc);
    $a = new DOMXPath($domdocument);
	foreach($classname as $cls){
        $spans = $a->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $cls ')]");

        for ($i = $spans->length - 1; $i > -1; $i--) {
            $infotema[] = $spans->item($i)->firstChild->nodeValue;
			//array_push($inf, $infotema);
        }
	}
	return $infotema;
}
function get_theme_url($d){
	$sdl = "http://dl.shop.line.naver.jp/themeshop/v1/products/";
	$sdl2 = "/1/ANDROID/theme.zip";
	$code = ambilKata($d, '/themeshop/product/', '/');
	$pecah_code = explode("-", $code); //5 Array, => (666-666-666-666-666)
	$code2 = $pecah_code[0];
	$split = str_split($code2); //8 Array, => (6-6-6-6-6-6-6-6)
	/* Susun Kode */
	$kode_1 = $split[0].$split[1];
	$kode_2 = $split[2].$split[3];
	$kode_3 = $split[4].$split[5];
	/* END */
	$dl_link = $sdl.$kode_1."/".$kode_2."/".$kode_3."/".$code.$sdl2; //URL Download
	return $dl_link;
}
function savezip($d,$t){
    $ch = curl_init();
    $source = $d;
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec ($ch);
    curl_close ($ch);
    $destination = $t.".zip";
    $file = fopen($destination, "w+");
    fputs($file, $data);
    fclose($file);
	chmod($destination,0755);
	$savedfile = file_put_contents("savedlist.txt", $destination."\n".PHP_EOL , FILE_APPEND | LOCK_EX);
}
function savefile($d,$t){
    $ch = curl_init();
    $source = $d;
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec ($ch);
    curl_close ($ch);
    $destination = $t;
    $file = fopen($destination, "w+");
    fputs($file, $data);
    fclose($file);
	chmod($destination,0755);
	$savedfile = file_put_contents("savedlist.txt", $destination."\n".PHP_EOL , FILE_APPEND | LOCK_EX);
}

if($_GET['url']){
	$dl_link = get_theme_url($_GET['url']);
	$theme_info = get_theme_info($_GET['url']);
	if($_GET['dl']=="1"){
		$save = savefile($dl_link, $theme_info[3]);
		@ob_clean();
        $file = $theme_info[3];
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
	} else if($_GET['dl']=="2"){
		$save = savezip($dl_link, $theme_info[3]);
		@ob_clean();
        $file = $theme_info[3].".zip";
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
	}
	//file_put_contents("theme.zip", fopen($dl_link, 'r'));
}
?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html><head>
<meta name="googlebot" content="index,follow" /> 
 <meta name="robots" content="all" /> 
  <meta name="robots schedule" content="auto" /> 
  <meta name="distribution" content="global" /> 
<meta name="description" content="LINE Theme Betak v.1">
<title><?if(!$theme_info[3]){echo "LINE SuX";}else{echo $theme_info[3];}?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="Smart Profile template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login sign up Responsive web template, SmartPhone Compatible web template, free web designs for Nokia, Samsung, LG, SonyEricsson, Motorola web design">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all">
<!-- //Custom Theme files -->
<!-- js -->
<script src="js/jquery-2.2.3.min.js"></script> 
<!-- //js -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Kurale" rel="stylesheet">
<!-- //web font -->
<!-- pop-up-box --> 
<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		$('.popup-top-anim').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
		});																							
	}); 
</script>
<!-- //pop-up-box -->  
<style type="text/css" id="igtranslator-color"></style></head>
<body>
<script>
function submit(){
	var redir = "http://line.expan-kreasi.com/index.php?url="+document.getElementById("url").value;;
	window.location = redir;
}
</script>
	<!-- main -->
	<div class="main-agileits">
		<h1>LINE Theme Betak</h1>
		<div class="wthree-row">
			<div class="profile-w3lstop"> 
				<div class="menu w3-agile">
					<span class="menu-icon"><a href="#"><img src="images/menu-icon.png" alt=""></a></span>	
						<ul class="nav1" style="display: none;">
							<li><a href="?url=<?echo $_GET['url'];?>&dl=1">Download [File]</a></li>
							<li><a href="?url=<?echo $_GET['url'];?>&dl=2">Download [Zip]</a></li>
						</ul> 	
						<!-- script-for-menu -->
						 <script>
						   $( "span.menu-icon" ).click(function() {
							 $( "ul.nav1" ).slideToggle( 300, function() {
							 // Animation complete.
							  });
							 });
						</script>
						<!-- /script-for-menu -->
				</div>
				<div class="agile-name">
					<h2><?if(!$theme_info[3]){echo "~X~";}else{echo $theme_name;}?></h2>
					<h6>By GoogleX</h6>
					<input size="30" name="url" placeholder="Masukan URL Theme LINE Disini" id="url" type="text" height="10"><a href="#" id="beraksi" onClick="submit()">Beraksi !</a>
				</div>
			</div>
			<div class="profile-w3lsmdl">
				<div class="profile-text-left">
					<h3><?echo $theme_info[1];?></h3>
					<p>Price</p>
				</div>
				<div class="profile-text-right">
					<h3><?echo $theme_info[2];?></h3>
					<p>Copyright</p>
				</div>
				<div class="clear"> </div>
			</div>
			<div class="agileinfo-text">			
				<p><?if(!$theme_info[0]){echo "DO WITH YOUR OWN RISK !";} else {echo $theme_info[0];}?></p>
			</div>
				
		</div>	
	</div>	
	<!-- //main --> 
	<!-- copyrights -->  
	<div class="copy-rights wthree">		 	
		<p>© 2017 All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>		 	
	</div>
	<!-- //copyright -->
<div class="igtranslator-main-div" style="display: none; width: 0px; height: 0px;"><iframe src="about:blank" class="igtranslator-iframe" scrolling="no" frameborder="0"></iframe></div><div class="igtranslator-activator-icon bounceIn" style="background-image: url(&quot;resource://jid1-dgnibwqga0sibw-at-jetpack/data/icons/home.png&quot;); display: none;" title="Click to Show Translation"></div></body></html>
