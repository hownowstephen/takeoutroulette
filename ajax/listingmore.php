<?php 

require_once("../apis/api_keys.php");
require_once("../apis/Yelp.php");

session_start();

$yelp = new YelpApi($yelp_consumer_key,$yelp_consumer_secret,$yelp_token,$yelp_token_secret);

$bizid = $_GET['business'];

$data = $yelp->Business($bizid);

$business = json_decode($data);

?>

<div class="listing_more">

    <div class="reviews">
        
        <?php foreach($business->reviews as $review): ?>
        <div class="review">
            <div class="reviewleft">
                <img src="<?php echo $review->user->image_url; ?>">
            </div>
            <div class="reviewright">
                <img src="<?php echo $review->rating_image_url; ?>"> <br />
                <?php echo $review->excerpt; ?> - <a target="_blank" class='more' href="<?php echo $business->url."#hrid:".$review->id; ?>">read more</a>
            </div>
        </div>

        <?php endforeach; ?>

</div>

