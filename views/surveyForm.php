<?php
require_once('../../../config.php');
require_once($CFG->dirroot . '/my/lib.php');

// Comprueba si el usuario está autenticado.
require_login();

// Inicializa Moodle.

// Añade cualquier verificación de permisos si es necesario.

// Establece la cabecera para que el navegador reconozca el contenido como HTML.
header('Content-Type: text/html');

// Consumir el servicio y obtener el formulario HTML.

$manager = new \local_sgevea\surveyManager();
$surveys = $manager->getAllSurveys();

$manager = new \local_sgevea\surveyManager();
$surveyId = optional_param('surveyid', 0, PARAM_TEXT); // Asegúrate de ajustar el nombre del parámetro según tu necesidad.
$formHtml = $manager->getSurveyFormById($surveyId);
echo $formHtml;
