<?php
// Inicialización y obtención de encuestas.
$manager = new \local_sgevea\surveyManager();
$surveys = $manager->getAllSurveys();
// Renderizar
$renderer = new \local_sgevea\surveyRenderer($PAGE);
echo $renderer->render_surveys($surveys);
