<?php

require_once('../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/local/sgevea/classes/DashboardTeachers.php');

global $OUTPUT, $PAGE;

require_login();

// Definimos nuestra página
$PAGE->set_url('/local/sgevea/views/dashboard-teachers.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname','local_sgevea').' - '.get_string('dashboard_teachers','local_sgevea'));

echo $OUTPUT->header();

$dashboardTeachers = new \local_sgevea\DashboardTeachers();
echo $dashboardTeachers->render();

echo $OUTPUT->footer();
