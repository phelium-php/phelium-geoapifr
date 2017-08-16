<?php

namespace Phelium\Component;

/**
 
EXAMPLES

// Commune
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->communes()
    ->fields(array('code', 'codeDepartement', 'codeRegion', 'nom'))
    ->search('nom', 'Versailles');

var_dump($datas);

// Departement
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->departements()
    ->fields(array('code', 'codeRegion', 'nom'))
    ->search('code', '2A');

var_dump($datas);

// Region
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->regions()
    ->fields(array('code', 'nom'))
    ->search('code', '94');

var_dump($datas);

*/

class GeoApiFr
{
    const BASE_URL = "https://geo.api.gouv.fr/";

    protected $URL = self::BASE_URL;

    protected $user_param = null;
    protected $user_search = null;
    protected $user_fields = [];

    protected $availableParams = [];
    protected $availableFields = [];


    /**
     * Search by commune
     */
    public function communes()
    {
        return new \Phelium\Component\GeoApiFr\Communes;
    }

    /**
     * Search by departement
     */
    public function departements()
    {
        return new \Phelium\Component\GeoApiFr\Departements;
    }

    /**
     * Search by region
     */
    public function regions()
    {
        return new \Phelium\Component\GeoApiFr\Regions;
    }


    /**
     * Add fields to return
     * 
     * @param array $fields Fields to add
     * @return object This object
     */
    public function fields(array $fields)
    {
        foreach ($fields as $field)
        {
            if (in_array($field, $this->availableFields))
            {
                $this->user_fields[] = $field;
            }
        }

        return $this;
    }


    /**
     * Search
     * 
     * @param string $key Search parameter
     * @param string $value Search value
     * @return run()
     */
    public function search($key = '', $value = '')
    {
        if (in_array($key, $this->availableParams))
        {
            $this->user_param = $key;
            $this->user_search = $value;
        }

        return $this->run();
    }


    /**
     * Run search
     * 
     * @return array Array with status_code ; status_msg ; url ; datas
     */
    private function run()
    {
        $url = $this->URL.'?'.$this->user_param.'='.$this->user_search;

        if (count($this->user_fields) > 0)
            $url .= '&fields='.implode(',', $this->user_fields);

        $queryResponse = $this->_doRequest($url);

        $status_code = null;
        $status_msg = null;
        $datas = null;

        if ($queryResponse !== false)
        {
            $status_code = 200;
            $status_msg = 'OK';
            $datas = $queryResponse;
        }
        else
        {
            $status_code = 400;
            $status_msg = 'Bad request';
        }

        $datas = [
            'status_code'   => $status_code,
            'status_msg'    => $status_msg,
            'url'           => $url,
            'datas'         => $datas,
        ];

        return $datas;
    }


    /**
     * Do a simple cURL request
     * 
     * @param string $url URL to query
     * @return void bool|string
     */
    private function _doRequest($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url,
            CURLOPT_CONNECTTIMEOUT  => 10,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_TIMEOUT         => 60,
            CURLOPT_ENCODING        => 1,
            CURLOPT_HTTPHEADER      => array('Accept-Encoding: gzip,deflate'),
        ));
        $response = curl_exec($curl);

        if (curl_errno($curl) || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200)
        {
            return false;
        }
        else
        {
            return json_decode($response, true);
        }
    }

}