<?php
// Inicialización y obtención de encuestas.
$manager2 = new \local_sgevea\survey_manager();
$survey = $manager2->getOne();

//dep($survey);

$idSurvey = (isset($survey[0]['ID']) && !empty($survey[0]['ID'])) ? $survey[0]['ID'] : null;

$showModal = (isset($idSurvey) && !empty($idSurvey)) ? TRUE : FALSE;

if ($showModal) {
    // Continúa solo si $showModal es TRUE

    $baseURL = "{$CFG->wwwroot}/local/sgevea/views/surveyForm.php";
    $parametros = array('surveyid' => $idSurvey);
    $urlGenerada = construirURL($baseURL, $parametros);
    $externalSurveyUrl = $urlGenerada;

    //dep($externalSurveyUrl);

    // Define el contenido del modal
    $modalContent = html_writer::start_tag('div', array('class' => 'modal-content'));
    $modalContent .= html_writer::tag('button', '&times;', array('class' => 'close-modal', 'id' => 'closeModal'));
    $modalContent .= html_writer::tag('iframe', '', array(
        'src' => $externalSurveyUrl,
        'frameborder' => '0',
        'class' => 'modal-iframe',
    ));

    $modalContent .= html_writer::end_tag('div');
    echo html_writer::start_tag('div', array('class' => 'modal', 'id' => 'myModal'));
    echo $modalContent;
    echo html_writer::end_tag('div');

    // Incluye el valor para showModal
    echo '<input type="hidden" name="showModal" id="showModal" value="true">';
} else {
    // Si no hay encuesta, muestra un mensaje (si es necesario)
    //echo get_string('nosurveyavailable', 'local_myplugin');
    echo '<input type="hidden" name="showModal" id="showModal" value="false">';
}
?>

<!-- Scripts y estilos siempre necesarios -->
<script src="<?php echo "{$CFG->wwwroot}/local/sgevea/js/modal.js" ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo "{$CFG->wwwroot}/local/sgevea/css/modal.css" ?>" />