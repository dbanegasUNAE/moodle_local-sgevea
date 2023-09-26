<?php
// Inicialización y obtención de encuestas.
$manager = new \local_sgevea\survey_manager();
$surveys = $manager->getAllSurveys();

// Renderizar.
$renderer = new \local_sgevea\survey_renderer($PAGE);
echo $renderer->render_surveys($surveys);