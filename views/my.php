<?php

use local_sgevea\ExceptionPlugin;
use local_sgevea\ErrorHandler;
use local_sgevea\ExceptionView;

#MY Visualization
## APP SGEVEA SURVEY
include("{$CFG->dirroot}/local/sgevea/lib.php");

## VIEW TOP
try {
    $viewTop = get_config('local_sgevea', 'generalsettings_my_top');
    if ($viewTop) {
        include("{$CFG->dirroot}/local/sgevea/views/viewTop.php");
    }
} catch (Exception $e) {
    echo ErrorHandler::showError($e->getMessage(), get_string('generalsettings_my_top_cont', 'local_sgevea'));
}

try {
    $activarencuestas = get_config('local_sgevea', 'survey_status');
    $activarencuestas_list = get_config('local_sgevea', 'visualizarlistados');
    $activarencuestas_modal = get_config('local_sgevea', 'visualizarformulario');
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

## VIEW BOTTOM
try {
    $viewBottom = get_config('local_sgevea', 'generalsettings_my_bottom');
    if ($viewBottom) {
        include("{$CFG->dirroot}/local/sgevea/views/viewBottom.php");
    }
} catch (Exception $e) {
    echo ErrorHandler::showError($e->getMessage(), get_string('generalsettings_my_bottom_cont', 'local_sgevea'));
}
