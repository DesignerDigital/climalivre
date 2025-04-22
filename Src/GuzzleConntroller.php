<?php

namespace Climalivre;

use GuzzleHttp\Client;

class GuzzleConntroller extends Controller{
    protected $client;
    protected $test;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function requisition(string $url, $query, $isProduction = true)
    {
        $verify = $isProduction;
        $this->test = $this->client->get($url,[
            'query' => $query,
            'verify' => $verify
        ]);
        return $this;
    }

    public function getBody()
    {
        return json_decode($this->test->getBody());
    }


}