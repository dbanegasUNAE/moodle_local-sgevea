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

$PAGE->navbar->add(get_string('pluginname', 'local_sgevea'), new moodle_url('/admin/category.php', array('category' => 'sgevea')));
$PAGE->navbar->add(get_string('dashboard', 'local_sgevea'), new moodle_url('/admin/category.php', array('category' => 'sgevea_dashboard')));
$PAGE->navbar->add($pageTitle);

$PAGE->requires->jquery();//Used for datatable.js
$PAGE->requires->css(new moodle_url('/local/sgevea/css/custom.css'));
$PAGE->requires->css(new moodle_url('/local/sgevea/libraries/datatables.js/cdn.datatables.net_v_bs4_dt-1.13.6_datatables.min.css'));
$PAGE->requires->js(new moodle_url('/local/sgevea/js/custom.js'));
$PAGE->requires->js(new moodle_url('/local/sgevea/libraries/datatables.js/cdn.datatables.net_v_bs4_dt-1.13.6_datatables.min.js'), true);

echo $OUTPUT->header();

$dashboardTeachers = new \local_sgevea\DashboardTeachers();
echo $dashboardTeachers->render();

echo $OUTPUT->footer();
