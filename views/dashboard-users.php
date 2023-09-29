<?php

require_once('../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/local/sgevea/classes/DashboardUsers.php');

global $OUTPUT, $PAGE;

require_login();

// PAGE DEFINITION
$PAGE->set_url('/local/sgevea/views/dashboard-users.php');
$PAGE->set_pagelayout('standard');
$pageTitle = get_string('pluginname', 'local_sgevea') . ' - ' . get_string('dashboard_users', 'local_sgevea');
$PAGE->set_title($pageTitle);
$PAGE->set_heading($pageTitle);

$PAGE->navbar->add(get_string('pluginname', 'local_sgevea'), new moodle_url('/admin/category.php', array('category' => 'sgevea')));
$PAGE->navbar->add(get_string('dashboard', 'local_sgevea'), new moodle_url('/admin/category.php', array('category' => 'sgevea_dashboard')));
$PAGE->navbar->add($pageTitle);

$PAGE->requires->css(new moodle_url('/local/sgevea/css/custom.css'));
$PAGE->requires->js(new moodle_url('/local/sgevea/js/custom.js'));

echo $OUTPUT->header();

$dashboardUsers = new \local_sgevea\DashboardUsers();
echo $dashboardUsers->render();

echo $OUTPUT->footer();
