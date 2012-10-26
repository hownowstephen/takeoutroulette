<?php 

require_once("../apis/api_keys.php");
require_once("../apis/Yelp.php");

session_start();

$yelp = new YelpApi($yelp_consumer_key,$yelp_consumer_secret,$yelp_token,$yelp_token_secret);

$bizid = $_GET['business'];

$data = $yelp->Business($bizid);

$business = json_decode($data);

?>

<div class="in-call">

    <h3 style="border-bottom:1px solid #ccc;">Calling <?php echo $business->name; ?></h3>
    <div class="business-details">
            <img src="<?php echo $business->rating_img_url; ?>"><br />

            <?php foreach($business->location->display_address as $addrline): ?>
                <?php echo $addrline; ?><br />
            <?php endforeach; ?>

            <br />
            <?php echo $business->display_phone; ?>
            <br /><br />
            <a class="view-on-yelp" href="<?php echo $business->url; ?>" target="_blank">View on Yelp.com <span>&raquo;</span></a>
            <br /><br />
            <img src="img/phone2dc.png" class="disconnect" onclick="Twilio.Device.disconnectAll()"> 
    </div>

    <div class="listing_more">

        <div class="reviews">
            
            <?php foreach($business->reviews as $review): ?>
            <div class="review">
                <div class="reviewleft">
                    <img src="<?php echo $review->user->image_url; ?>">
                </div>
                <div class="reviewright">
                    <img src="<?php echo $review->rating_image_url; ?>"> <br />
                    <?php echo $review->excerpt; ?> - <a target="_blank" class='more' href="<?php echo $business->url; ?>">read more</a>
                </div>
            </div>

            <?php endforeach; ?>

    </div>

</div>

