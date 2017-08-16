<?php

namespace Phelium\Component\GeoApiFr;

use \Phelium\Component\GeoApiFr;

class Communes extends GeoApiFr
{
    protected $endpoint = "communes";
    protected $URL = null;

    /**
     * Available parameters for searching
     */
    protected $params = [
        'codePostal',
        'codeDepartement',
        'codeRegion',
        'nom',
        'lon',
        'lat',
    ];

    /**
     * Available fields in return
     */
    protected $fields = [
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


    public function __construct()
    {
        $this->availableParams = $this->params;
        $this->availableFields = $this->fields;

        $this->URL = parent::BASE_URL.$this->endpoint;
    }
}