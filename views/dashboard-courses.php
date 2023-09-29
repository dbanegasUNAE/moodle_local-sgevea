<?php

require_once('../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/local/sgevea/classes/DashboardCourses.php');

global $OUTPUT, $PAGE;

require_login();

// PAGE DEFINITION
$PAGE->set_url('/local/sgevea/views/dashboard-courses.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_sgevea') . ' - ' . get_string('dashboard_courses', 'local_sgevea'));
$PAGE->requires->css(new moodle_url('/local/sgevea/css/custom.css'));
$PAGE->requires->js(new moodle_url('/local/sgevea/js/custom.js'));

echo $OUTPUT->header();

$dashboardCourses = new \local_sgevea\DashboardCourses();
echo $dashboardCourses->render();

echo $OUTPUT->footer();
