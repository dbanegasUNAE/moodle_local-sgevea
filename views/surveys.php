<?php
// Inicialización y obtención de encuestas.
$manager = new \local_sgeveasurvey\survey_manager();
$surveys = $manager->getAllSurveys();

// Renderizar.
$renderer = new \local_sgeveasurvey\survey_renderer($PAGE);
echo $renderer->render_surveys($surveys);