<?php

namespace local_sgevea;

class surveyManager
{
    private $endpoint;

    public function __construct()
    {
        try {
            $api = new Api();
            $this->endpoint = new Endpoint($api);
        } catch (ExceptionService $e) {
            throw new ExceptionPlugin('Plugin Error. ' . $e->getMessage());
        }
    }

    public function getAllSurveys()
    {
        try {
            return $this->endpoint->getAllSurveys();
        } catch (ExceptionService $e) {
            throw new ExceptionPlugin('Plugin Error. ' . $e->getMessage());
        }
    }
    public function getOne()
    {
        try {
            return $this->endpoint->getOne();
        } catch (ExceptionService $e) {
            throw new ExceptionPlugin('Plugin Error. ' . $e->getMessage());
        }
    }

    // Aquí puedes agregar otros métodos según lo necesites.

    public function getSurveyById($id)
    {
        try {
            return $this->endpoint->getSurveyById($id);
        } catch (ExceptionService $e) {
            throw new ExceptionPlugin('Plugin Error. ' . $e->getMessage());
        }
    }
    //Formulario
    public function getSurveyFormById($id)
    {
        try {
            return $this->endpoint->getSurveyFormById($id);
        } catch (ExceptionService $e) {
            throw new ExceptionPlugin('Plugin Error. ' . $e->getMessage());
        }
    }
}
