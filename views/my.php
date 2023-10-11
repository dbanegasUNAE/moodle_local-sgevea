<?php

use local_sgevea\ExceptionPlugin;
use local_sgevea\ErrorHandler;
use local_sgevea\ExceptionView;

include("{$CFG->dirroot}/local/sgevea/lib.php");
$activarencuestas = get_config('local_sgevea', 'survey_status');
$activarencuestas_list = get_config('local_sgevea', 'visualizarlistados');
$activarencuestas_modal = get_config('local_sgevea', 'visualizarformulario');

try {
    //ENCUESTAS VIEW
    if ($activarencuestas) { //ACTIVE ALL SURVEY PLUGIN API INTERACTION
        if ($activarencuestas_list) {
            include("{$CFG->dirroot}/local/sgevea/views/surveys.php");
        }
        if ($activarencuestas_modal) {
            include("{$CFG->dirroot}/local/sgevea/views/survey.php");
        }
    }
} catch (ExceptionPlugin $e) {
    echo ErrorHandler::showError($e->getMessage(), get_string('survey_tit', 'local_sgevea'));
}
