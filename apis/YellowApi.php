<?php
/*
 * YellowAPI PHP API Library
 * Requires: get_file_contents/CURL Version
 * Version: 1.0
 */

/**
 * YellowAPI class - used to make calls to the YellowAPI.
 */
class YellowAPI {

	/** Production API url */
	public static $PROD_URL = "http://api.yellowapi.com";

	/** Sandbox API url */
	public static $TEST_URL = "http://api.sandbox.yellowapi.com";

	/** 
	 * Api Key field 
	 * @access private
	 * @var string
	 */
	private $api_key;

	/** 
	 * Url used to perform calls 
	 * @access private
	 * @var string
	 */
	private $url;

	/** 
	 * The format of the response 
	 * @access private
	 * @var string
	 */
	private $format;
	

	function __construct($api_key, $test_mode=False, $format='XML') {
		$this->api_key = $api_key;
		$this->url = ($test_mode) ? self::$TEST_URL : self::$PROD_URL;
		$this->format = $format;
	}


	/**
	 * Find a business 
	 *
	 * @param string $what
	 *		the keyword or business name to search for
	 * @param string $where
	 *		the location to search
	 * @param string $uid
	 *		a unique identifier for the user of the application
	 * @param integer $page
	 *		the page of results to return
	 * @param integer $page_len
	 *		the number of results per page
	 * @param string sflag
	 *		the search flag to filter results
	 *
	 * @return string contents of the response as XML of JSON
	 */
	function find_business($what, $where, $uid, $page=NULL, $page_len=NULL,
			$sflag=NULL, $lang=NULL) {
		$url = $this->build_url('FindBusiness', array('what' => $what, 
				'where' => $where, 'UID' => $uid, 'pg' => $page,
				'pgLen' => $page_len, 'sflag' => $sflag,
				'lang' => $lang));
		return file_get_contents($url);
	}

	/**
	 * Get details about a business
	 *
	 * @param string $prov
	 *		the province of the business
	 * @param string $business_name
	 *		the name of the business
	 * @param integer $listing_id
	 *		the listing id of the business
	 * @param stirng $uid
	 *		a unique identifier of the user of the application
	 * @param string $city
	 *		the city of the business
	 * @param string $lang
	 *		the language of the response
	 *
	 * @return string contents of the response as XML or JSON
	 */
	function get_business_details($prov, $business_name, $listing_id, $uid,
			$city=NULL, $lang=NULL) {
		$url = $this->build_url('GetBusinessDetails', array('prov' => $prov,
				'bus-name' => $business_name, 'listingId' => $listing_id,
				'UID' => $uid, 'city' => $city, 'lang' => $lang));
            $result = file_get_contents($url);
		return $result;
	}

	/**
	 * Find dealers (sub business) of the parent business.
	 *
	 * @param integer $pid
	 * 		the listing id of the parent compant
	 * @param string $uid
	 * 		a unique identifier of the user of the application
	 * @param integer $page
	 *		the page of results to return
	 * @param integer $page_len
	 *		the number of results per page
	 * @param string $lang
	 *		the language of the response
	 *
	 * @return string the contents of the response as XML or JSON
	 */
	function find_dealer($pid, $uid, $page=NULL, $page_len=NULL, $lang=NULL) {
		$url = $this->build_url('FindDealer', array('pid' => $pid,
				'UID' => $uid, 'pg' => $page, 'pgLen' => $page_len,
				'lang' => $lang));
            $result = file_get_contents($url);
		return $result;
	}


	/**
	 * Build a url from the parameter list.
	 *
	 * @param string $method
	 *		the API method name
	 * @param array $params
	 *		an associative array of param names to values
	 *
	 * @return string the fully constructed url
	 */
	private function build_url($method, $params) {
		$param_array = array();
		while (list($key, $value) = each($params)) {
			if (!isset($value)) {
				continue;
			}
			array_push($param_array, sprintf("%s=%s", $key, $value));
		}

		return sprintf("%s/%s/?%s&apikey=%s&fmt=%s", $this->url, $method,
				join("&", $param_array), $this->api_key, $this->format);
	}
}
?>