<?php

function local_sgeveasurvey_get_survey_data()
{
    global $CFG;

    require_login();

    $token = get_config('local_sgeveasurvey', 'token');
    $status = get_config('local_sgeveasurvey', 'status');
    $apiurl = get_config('local_sgeveasurvey', 'apiurl');

    if (!$status) {
        return false;
    }

    $url = $apiurl . "?auth=$token&acc=getAllSurvey&idUser=0123456789&roles=student&teacher";
    $response = file_get_contents($url);

    return json_decode($response, true);
}

function construirURL(string $baseURL, array $parametros = [])
{
    if ($baseURL) {
        // Verifica si $parametros es un array y si tiene elementos
        if (is_array($parametros) && !empty($parametros)) {
            // Agrega los parámetros a la URL base utilizando http_build_query
            $urlCompleta = $baseURL . '?' . http_build_query($parametros);
            return $urlCompleta;
        } else {
            // Si $parametros no es un array o está vacío, retorna la URL base sin cambios
            return $baseURL;
        }
    } else {
        return null;
    }
}
