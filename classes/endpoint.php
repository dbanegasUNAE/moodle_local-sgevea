<?php

namespace local_sgevea;

class endpoint
{

    private $api;

    public function __construct($api)
    {
        $this->api = $api;
    }

    public function getAllSurveys()
    {
        return $this->api->request('surveys');
    }
    public function getOne()
    {
        return $this->api->request('surveys/getone');
    }

    public function getSurveyById($id)
    {
        return $this->api->request('surveys/' . $id);
    }

    public function getSurveyFormById($id)
    {
        //echo __CLASS__.'\\'.__FUNCTION__.'<br>';
        return $this->api->request('surveys/' . $id . '/form');
    }

    // Puedes agregar más métodos para consumir otros endpoints según lo necesites
}
