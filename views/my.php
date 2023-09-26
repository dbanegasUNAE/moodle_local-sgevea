<?php
include("{$CFG->dirroot}/local/sgevea/lib.php");
$activarencuestas = get_config('local_sgevea', 'activarencuestas');
$activarencuestas_list = get_config('local_sgevea', 'visualizarlistados');
$activarencuestas_modal = get_config('local_sgevea', 'visualizarformulario');

if ($activarencuestas) {

    if ($activarencuestas_list) {
        include("{$CFG->dirroot}/local/sgevea/views/surveys.php");
    }
    if ($activarencuestas_modal) {
        include("{$CFG->dirroot}/local/sgevea/views/survey.php");
    }
}
