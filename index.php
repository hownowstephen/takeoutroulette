<?php

require_once "apis/Quova.php";
require_once "apis/twilio/Twilio/Capability.php";

function CapabilityToken(){
	$account_sid = "";	
	$auth_token = "";
	$application_sid = "";
	 
	$capability = new Services_Twilio_Capability($account_sid, $auth_token);
    $capability->allowClientOutgoing($application_sid);
	return $capability->generateToken();
}

session_start();

#print_r($_SESSION);

if(!$_SESSION['lat'] || !$_SESSION['lng'] || !$_SESSION['city'] || !$_SESSION['state']){
	$ip = getRealIP();
	if($ip == "127.0.1.1"){ $ip = "64.15.69.132"; }

	$xml = simplexml_load_string(GeolocateIP($ip));
	$city = $xml->Location->CityData->city;
	$state = $xml->Location->StateData->state_code;
	$lat = $xml->Location->latitude;
	$lng = $xml->Location->longitude;

	$_SESSION['lat'] = (string)$lat;
	$_SESSION['lng'] = (string)$lng;
	$_SESSION['city'] = (string)$city;
	$_SESSION['state'] = (string)$state;
	$_SESSION['page'] = 1;
	$_SESSION['status'] = 'available';

}else{
	$lat = $_SESSION['lat'];
	$lng = $_SESSION['lng'];
	$city = $_SESSION['city'];
	$state = $_SESSION['state'];
}

#print_r($_SESSION);

?>


<!DOCTYPE html>
<html lang="en">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title>TakeoutRoulette</title>
		<!-- Styles and fonts -->
		<link href='http://fonts.googleapis.com/css?family=Federo' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css">
		<!-- Scripts and libraries -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="https://static.twilio.com/libs/twiliojs/1.0/twilio.min.js"></script>
		<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>


		<script type="text/javascript">
			var lat = "<?php echo $lat; ?>";
			var lng = "<?php echo $lng; ?>";
			var city = "<?php echo $city; ?>";

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-24995930-1']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

			Twilio.Device.setup("<?php echo CapabilityToken(); ?>");

		</script>

		<script type="text/javascript" src="js/application.js"></script>

	</head>
	<body>

		<div id="header">
			<div class="yelpheader">
				<span>Local listings and reviews<br />powered by</span><br />
				<a href="http://www.yelp.com"><img class="yelp" src="img/yelp_large.png"></a>
			</div>
			<div class="content">
				<h1>
					Takeout Roulette
					<img src="img/view-refresh.png" class="refresh" style="width:36px; height:auto;" onclick="refreshAll();">
				</h1>
				<div id="disconnect">
					<a onclick="Twilio.Device.disconnectAll()">
					<img src="img/phone2dc.png" class="disconnect" onclick="Twilio.Device.disconnectAll()"><br />
					</a>
				</div>
				
				<div class="region">
					<div style="margin-bottom:5px;">
					Your Location: 
					<strong>
						<?php 
							echo ucwords($city); 
							if($state) echo ", ".strtoupper($state);
						?>
					</strong>
					</div>
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="StephenYoungDev">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					<iframe src="http://www.facebook.com/plugins/like.php?app_id=218827634831731&amp;href=http%3A%2F%2Fwww.tryllo.com%2Ftakeoutroulette&amp;send=false&amp;layout=standard&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:25px;float:right;margin-bottom:0px;" allowTransparency="true"></iframe>
				</div>
			</div>
		</div>

		<div id="container">

			<!--MAIN CONTENT-->


			<div id="grid">

				<?php for($i=0;$i<4;$i++): ?>

					<div class="griditem" id="grid<?php echo $i; ?>">
						<h2>
							<img class="stars" src="http://media2.px.yelpcdn.com/static/201012162752244354/i/ico/stars/stars_large_0.png">
							<div class="reviewcount">0 reviews</div>
							<a href="" target="_blank">
								<span class="text"><?php echo 'Loading...'; ?></span>
								<span class="quotes">&raquo;</span>
							</a>
						</h2>
						
						<div class="details">

							<img class="biz-img" src="img/restaurant_food.png">
							<div class="text-cont">
								<div class="biz-text"></div>
								<a class="more-reviews" onclick="">
									View More Reviews
								</a>
							</div>

						</div>

						<div class="actions">
							<img class="call" src="img/phone2.png" onclick='' style="cursor:pointer;">
						</div>

						<div class="refresh-single">
							<img class="refresh" src="img/view-refresh.png" onclick="LoadLocation(<?php echo $i; ?>);">
						</div>

					</div>

				<?php endfor; ?>

			</div>


			<!--FOOTER-->

			<div id="footer">
				<div class="copyright">
					<a href="http://www.getmelisted.net"><img src="img/gml.png"></a>
					&copy; 2011 <a href="http://twitter.com/stephenyoungdev">@stephenyoungdev</a>
				</div>
				<div class="links">
					<a href="http://www.twilio.com"><img src="img/twilio.png"></a>
				</div>
			</div>
		</div>
	</body>

</html>



