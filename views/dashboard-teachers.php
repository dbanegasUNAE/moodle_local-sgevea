<?php

require_once('../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/local/sgevea/classes/DashboardTeachers.php');

global $OUTPUT, $PAGE;

require_login();

// PAGE DEFINITION
$PAGE->set_url('/local/sgevea/views/dashboard-teachers.php');
$PAGE->set_pagelayout('standard');
$pageTitle = get_string('pluginname', 'local_sgevea') . ' - ' . get_string('dashboard_teachers', 'local_sgevea');
$PAGE->set_title($pageTitle);
$PAGE->set_heading($pageTitle);
$PAGE->requires->css(new moodle_url('/local/sgevea/css/custom.css'));
$PAGE->requires->js(new moodle_url('/local/sgevea/js/custom.js'));

echo $OUTPUT->header();

$dashboardTeachers = new \local_sgevea\DashboardTeachers();
echo $dashboardTeachers->render();

echo $OUTPUT->footer();
