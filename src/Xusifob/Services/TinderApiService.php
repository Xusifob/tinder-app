<?php

namespace Xusifob\Services;

use GuzzleHttp\Client;

/**
 * Class PaymoApiService
 */
class TinderApiService
{


    /**
     * @var \GuzzleHttp\Client
     */
    public $client;


    /**
     * PaymoApiService constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = new Client(array(
            'base_uri' => 'https://api.gotinder.com/v2/',
            'headers' => array(
                'Accept'=> 'application/json',
                'X-Auth-Token' => $config['X-Auth-Token']
            )
        ));
    }


    /**
     * @return mixed
     */
    public function getMyProfile()
    {
        return $this->get('profile?include=account%2Cboost%2Cemail_settings%2Clikes%2Cnotifications%2Cpurchase%2Csuper_likes%2Ctinder_u%2Ctravel%2Cuser&locale=fr');
    }


    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
    public function updateLocation($lat,$lng)
    {
        return $this->post('/user/ping',array(
            'lat' => $lat,
            'lon' => $lng
        ));
    }


    /**
     * @return mixed
     */
    public function getMyTinderGold()
    {
        return $this->get('fast-match/teasers?locale=fr');
    }

    /**
     * @return mixed
     */
    public function getMatchs()
    {
        return $this->get('recs/core');
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function like($user_id,$s_number)
    {
        return $this->get('https://api.gotinder.com/like/' . $user_id . '?s_number=' . $s_number);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function pass($user_id,$s_number)
    {
        return $this->get('https://api.gotinder.com/pass/' . $user_id . '?s_number=' . $s_number);
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function superLike($user_id,$s_number)
    {
        return $this->get('https://api.gotinder.com/like/' . $user_id . '/super?s_number=' . $s_number);
    }



    /**
     * @param $url
     * @return mixed
     */
    public function get($url)
    {
        return json_decode($this->client->get($url)->getBody()->getContents(),true);

    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     */
    protected function post($url,$data)
    {
        return json_decode($this->client->post($url,array(
            'json' => $data
        ))->getBody()->getContents(),true);

    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     */
    protected function put($url,$data)
    {

        return json_decode($this->client->put($url,array(
            'json' => $data
        ))->getBody()->getContents(),true);

    }

}