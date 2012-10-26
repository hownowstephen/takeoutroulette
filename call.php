<?php

$phone = preg_replace('[\D]','',@$_GET['phone']);

if($phone){

    $business = @$_GET['business'] ? $_GET['business'] : "your Takeout Roulette choice.";

    echo '<?xml version="1.0" encoding="UTF-8"?>
    <Response>
        <Say>Connecting you to '.$business.'</Say>
        <Dial callerId="5146089005">'.$phone.'</Dial>
    </Response>';

}

?>