<?php

require_once("../apis/api_keys.php");
require_once("../apis/Yelp.php");

session_start();

$yelp = new YelpApi($yelp_consumer_key,$yelp_consumer_secret,$yelp_token,$yelp_token_secret);

function LoadListings($lat,$lng){
    global $yelp;
    
    $response = $yelp->Search($lat,$lng,'delivery',$_SESSION['page']);
    #echo $response;
    $data = json_decode($response);

    $_SESSION['total'] = (int)$data->total;
    
    $b = $data->businesses;
    shuffle($b);
    $_SESSION['listings'] = $b;

    return true;

}


if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {

    if(!@$_SESSION['listings'] || empty($_SESSION['listings']) || @$_SESSION['status'] == 'updated'){
        // Paginate
        if($_SESSION['status'] == 'available'){
            $_SESSION['page'] ++;
            if((($_SESSION['page'] - 1) * 20) > $_SESSION['total']){
                $_SESSION['page'] = 1;
            }
        }
        if(!$_SESSION['page']) $_SESSION['page'] = 1;
        LoadListings($_SESSION['lat'], $_SESSION['lng']);
    }

    echo json_encode(array_pop($_SESSION['listings']));

}

?>