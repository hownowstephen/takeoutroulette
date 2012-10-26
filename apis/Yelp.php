<?php

require_once("OAuth.php");

class YelpAPI {

    private $consumer;
    private $token;
    private $sigmethod;
    

    public function YelpApi($key,$secret,$token,$token_secret){
        $this->consumer = new OAuthConsumer($key, $secret, NULL);
        $this->token = new OAuthToken($token, $token_secret);
        $this->sigmethod = new OAuthSignatureMethod_HMAC_SHA1();
    }

    public function Search($lat,$lng,$term=false,$page=1){
        $params = array();
        $params['ll'] = "$lat,$lng";
        $params['limit'] = 20;
        $params['offset'] = 20*($page-1);
        if($params['offset'] < 0) $params['offset'] = 0;
        
        if($term) $params['term'] = $term;
        return $this->Request("http://api.yelp.com/v2/search",$params);
    }

    public function Business($id){
        return $this->Request("http://api.yelp.com/v2/business/$id",array());
    }

    private function Request($endpoint,$params){

        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, "GET", $endpoint, $params);
        $request->sign_request($this->sigmethod, $this->consumer, $this->token);;
        return $this->GET($request->to_url());

    }

    /**
     * GET
     * Adaptation of the 
     * @param String $url The base url to query
     * @param Array $params The parameters to pass to the request
     * @param boolean $usecurl Default:true, whether or not to perform the request using cUrl
     */
    private function GET($url,$usecurl=true){
        if($usecurl){
            // borrowed from Andy Langton: http://andylangton.co.uk/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.X.Y.Z Safari/525.13.');
            curl_setopt($ch , CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            //curl_setopt($ch, CURLOPT_HEADER,1);
            $result=curl_exec($ch);
            $info=curl_getinfo($ch);
            curl_close($ch);
        }else{
            $result = file_get_contents($url);
            $info['content_type'] = $responseType;
        }
        
        return $result;
    }
    

}