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
$PAGE->requires->js(new moodle_url('/local/sgevea/libraries/chart.js/cdn.jsdelivr.net_npm_chart.js'), true);


echo $OUTPUT->header();
// Obtiene los parámetros del formulario.
$view = optional_param('view', 'date_range', PARAM_TEXT);
$start_date = optional_param('start_date', null, PARAM_TEXT);
$end_date = optional_param('end_date', null, PARAM_TEXT);

// Verifica si los parámetros están configurados y proporciona valores predeterminados si no lo están.
if (is_null($start_date)) {
    $start_date = strtotime(date('Y-m-d 00:00:00'));
}
if (is_null($end_date)) {
    $end_date = strtotime(date('Y-m-d 23:59:59'));
}

// Crea una instancia de la clase DashboardUsers con los parámetros.
$dashboardUsers = new \local_sgevea\DashboardUsers($start_date, $end_date);

// Luego, dependiendo del valor de $view, puedes configurar la vista en la clase DashboardUsers.
$dashboardUsers->setView($view);

echo $dashboardUsers->render();


echo $OUTPUT->footer();
