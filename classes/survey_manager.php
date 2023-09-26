<?php

namespace local_sgevea;

class survey_manager
{
    private $endpoint;

    public function __construct()
    {
        $api = new api();
        $this->endpoint = new endpoint($api);
    }

    public function getAllSurveys()
    {
        //echo "Function. ".__FUNCTION__."<br>";
        return $this->endpoint->getAllSurveys();
    }
    public function getOne()
    {
        //echo "Function. ".__FUNCTION__."<br>";
        return $this->endpoint->getOne();
    }

    // Aquí puedes agregar otros métodos según lo necesites.

    public function getSurveyById($id)
    {
        return $this->endpoint->getSurveyById($id);
    }
    //Formulario
    public function getSurveyFormById($id)
    {
        //echo __CLASS__.'\\'.__FUNCTION__.'<br>';
        return $this->endpoint->getSurveyFormById($id);
    }
}
