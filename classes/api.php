<?php

namespace local_sgevea;

//use local_sgevea\moodleApi;
use Exception;

class api
{
    private $baseurl;
    private $token;

    public function __construct()
    {
        $this->baseurl = get_config('local_sgevea', 'apiurl');
        $this->token = get_config('local_sgevea', 'token');
    }

    public function request($endpoint, $method = 'GET', $data = [])
    {
        global $CFG;

        //WEBSERVICE INIT CONSUM
        $ch = curl_init();
        //GET USER DATA
        $userId = $this->getUserID();
        $userIdNumber = $this->getUserNumber();
        $userRoles = $this->getUserRoles();
        
        $domain = $CFG->wwwroot;
        $domain = parse_url($domain, PHP_URL_HOST);

        //SET HEADERS
        $headers = [
            'Authorization: Bearer ' . $this->token, //Token in config
            'idUser: ' . $userId, //User Data
            'idNumber: ' . $userIdNumber, //User Data
            'role: ' . $userRoles, //User Data
            'Origin: ' . $domain
        ];
        //dep($headers,$this->baseurl . $endpoint);
        //WEBSERVICE CONFIG
        curl_setopt($ch, CURLOPT_URL, $this->baseurl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //CONFIGS FOR SSL
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //OPTIONAL
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //OPTIONAL


        //WEBSERVICE METHOD
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //WEBSERVICE EXECUTE
        $response = curl_exec($ch);
        //dep($response,'response');

        // Comprobar el tipo de respuesta (JSON o no).
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        if (stripos($contentType, 'application/json') !== false) {
            // La respuesta es JSON, decodificarla.
            $decodedResponse = json_decode($response, true);
            if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
                // Error al decodificar JSON, devuelve la respuesta sin procesar.
                return $response;
            }
            // Respuesta JSON decodificada.
            return $decodedResponse;
        }
        // Respuesta no es JSON, devuelve la respuesta sin procesar.
        return $response;
    }

    // Puedes agregar otros métodos según lo necesites (PUT, DELETE, etc.)

    /* GET LOGGED USER DATA :: id */
    public function getUserID()
    {
        try {
            global $USER;
            $idUser = $USER->id; // Asegúrate de que id_number sea el campo correcto en tu Moodle.
            return $idUser;
        } catch (Exception $e) {
            return null;
        }
    }
    /* GET LOGGED USER DATA :: number */
    public function getUserNumber()
    {
        try {
            global $USER;
            $idUser = $USER->idnumber; // Asegúrate de que id_number sea el campo correcto en tu Moodle.
            return $idUser;
        } catch (Exception $e) {
            return null;
        }
    }
    /* GET LOGGED USER DATA :: roles in string format: 'student-teacher' */
    public function getUserRoles()
    {
        try {
            global $DB;
            $userid = $this->getUserID();
            // Consulta SQL para obtener los nombres de los roles del usuario
            $sql = "SELECT r.shortname 
            FROM {role} r 
            JOIN {role_assignments} ra ON ra.roleid = r.id 
            WHERE ra.userid = :userid 
            GROUP BY r.shortname";
            // Ejecutar la consulta
            $roles = $DB->get_records_sql($sql, array('userid' => $userid));
            if (!$roles) {
                throw new Exception('No Roles');
            }
            // Inicializar una variable para almacenar los nombres de los roles
            $rolenames = [];
            // Recorrer los resultados y obtener los nombres de los roles
            foreach ($roles as $role) {
                $rolenames[] = $role->shortname;
            }
            $userRolesString = implode(',', $rolenames);
            return $userRolesString;
        } catch (Exception $e) {
            return null;
        }
    }
}
