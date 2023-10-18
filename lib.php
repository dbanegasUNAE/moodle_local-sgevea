<?php
function getUserRolesID($idUser)
{
    global $DB;
    // Consulta SQL para obtener los nombres de los roles del usuario
    $sql = "SELECT r.id, r.shortname 
        FROM {role} r 
        JOIN {role_assignments} ra ON ra.roleid = r.id 
        WHERE ra.userid = :userid 
        GROUP BY r.shortname";
    // Ejecutar la consulta
    $roles = $DB->get_records_sql($sql, array('userid' => $idUser));
    $roleids = [];
    if ($roles) {

        // Recorrer los resultados y obtener los nombres de los roles
        foreach ($roles as $role) {
            $roleids[] = $role->id;
        }
    }
    return $roleids;
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
