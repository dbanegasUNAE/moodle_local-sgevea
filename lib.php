<?php
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
