<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $punkRequestDataArray = array();

    private $punkapiUrl = '';

    public function show(Request $request)
    {

        $searchName = $request->name;

        $searchAbv  = intval($request->abv);

        $this->punkapiUrl = 'https://api.punkapi.com/v2/beers';

        if ( ! empty($searchName) && empty($searchAbv)) {
            $this->punkapiUrl = 'https://api.punkapi.com/v2/beers?beer_name=' . $searchName;
        }
        if (empty($searchName) && ! empty($searchAbv)) {
            $searchAbvMax     = $searchAbv + 1;
            $this->punkapiUrl = 'https://api.punkapi.com/v2/beers?abv_gt=' . $searchAbv . '&abv_lt=' . $searchAbvMax;
        }
        if ( ! empty($searchName) && ! empty($searchAbv)) {
            if ($searchAbv == 0) {
                $searchAbvMax     = $searchAbv + 1;
                $this->punkapiUrl = 'https://api.punkapi.com/v2/beers?beer_name=' . $searchName . '&abv_lt=' . $searchAbvMax;
            } else {
                $searchAbvMax     = $searchAbv + 1;
                $this->punkapiUrl = 'https://api.punkapi.com/v2/beers?beer_name=' . $searchName . '&abv_gt=' . $searchAbv . '&abv_lt=' . $searchAbvMax;
            }

        }
        $this->recursive($this->punkapiUrl); //check pagination page

        $filteredData = array();
        $orderData    = array();
        if ( ! empty($this->punkRequestDataArray) && is_array($this->punkRequestDataArray)) {
            foreach ($this->punkRequestDataArray as $oneData) {
                $filteredData[]     = array(
                    'id'             => $oneData['id'],
                    'name'           => $oneData['name'],
                    'tagline'        => $oneData['tagline'],
                    'image_url'      => $oneData['image_url'],
                    'image_url_html' => "<img src='" . $oneData['image_url'] . "' style='height:30px;'>",
                    'abv'            => $oneData['abv']
                );
                $orderData['abv'][] = $oneData['abv'];
            }
        }

        if ( ! empty($orderData)) {
            array_multisort($orderData['abv'], constant('SORT_ASC'), $filteredData);
        }

        return response()->json(array('Suggestions' => $filteredData));

    }

    function CURL($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data     = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpcode >= 200 && $httpcode < 300) ? $data : false;

    }

    function recursive($url, $level = 1)
    {

        if ($level > 1) {
            $url = $this->punkapiUrl . '&page=' . $level;
        }

        $punkRequest = $this->CURL($url);
		if($punkRequest !== false){
			$punkRequestArray = json_decode($punkRequest, true);
			if (count($punkRequestArray) > 0) {
				foreach ($punkRequestArray as $oneRequest) {
					$this->punkRequestDataArray[] = $oneRequest;
				}
				$this->recursive($this->punkapiUrl, $level + 1);
			}
		}

    }

}
