<?php

namespace Phelium\Component;

/**
 
EXAMPLE

$GeoApiFr = new \Phelium\Component;
$datas = $GeoApiFr
    ->get('nom', 'Versailles')
    ->fields(array('code', 'codeDepartement', 'codeRegion', 'nom'))
    ->search();

var_dump($datas);

*/

class GeoApiFr
{
    const URL_MAIN = "https://geo.api.gouv.fr/communes";

    /**
     * 
     */
    private $availableParams = [
        'code',
        'codeDepartement',
        'codeRegion',
        'nom',
        'lon',
        'lat',
    ];

    /**
     * 
     */
    private $availableFields = [
        'code',
        'codeDepartement',
        'codeRegion',
        'nom',
        'codesPostaux',
        'surface',
        'population',
        'centre',
        'contour',
        'departement',
        'region',
    ];

    protected $param = null;
    protected $search = null;
    protected $fields = [];



    /**
     * Add search
     * 
     * @param string $key Search parameter
     * @param string $value Search value
     * @return object This object
     */
    public function get($key, $value)
    {
        if (in_array($key, $this->availableParams))
        {
            $this->param = $key;
            $this->search = $value;
        }

        return $this;
    }


    /**
     * Add fields
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
                $this->fields[] = $field;
            }
        }

        return $this;
    }


    /**
     * Run search
     * 
     * @return array Array with status_code ; status_msg ; url ; datas
     */
    public function search()
    {
        $url = self::URL_MAIN.'?'.$this->param.'='.$this->search;

        if (count($this->fields) > 0)
            $url .= '&fields='.implode(',', $this->fields);

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
            'status_code' => $status_code,
            'status_msg' => $status_msg,
            'url' => $url,
            'datas' => $datas,
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

