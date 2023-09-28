<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && has_capability('local/sgevea:manage', context_system::instance())) {

    // Crear menú principal para el plugin
    $ADMIN->add('modules', new admin_category('sgevea', get_string('pluginname', 'local_sgevea')));

    // Crear el menú secundario "DASHBOARD"
    $ADMIN->add('sgevea', new admin_category('sgevea_dashboard', get_string('dashboard', 'local_sgevea')));

    // Agregar hijos al menú "DASHBOARD"
    $url_dashboard = new moodle_url('/local/sgevea/views/dashboard-courses.php');
    $ADMIN->add('sgevea_dashboard', new admin_externalpage('sgevea_dashboard_courses', get_string('dashboard_courses', 'local_sgevea'), $url_dashboard));

    $url_users = new moodle_url('/local/sgevea/views/dashboard-users.php');
    $ADMIN->add('sgevea_dashboard', new admin_externalpage('sgevea_dashboard_users', get_string('dashboard_users', 'local_sgevea'), $url_users));

    $url_users = new moodle_url('/local/sgevea/views/dashboard-teachers.php');
    $ADMIN->add('sgevea_dashboard', new admin_externalpage('sgevea_dashboard_users', get_string('dashboard_teachers', 'local_sgevea'), $url_users));

    // Configuraciones específicas del plugin sgevea
    $settings = new admin_settingpage('sgevea_settings', get_string('settings', 'local_sgevea'));
    $ADMIN->add('sgevea', $settings);

    // Encabezado para configuraciones generales
    $settings->add(new admin_setting_heading('generalsettings', get_string('generalsettings', 'local_sgevea'), ''));

    $settings->add(new admin_setting_configtext('local_sgevea/token', get_string('token', 'local_sgevea'), '', ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/status', get_string('status', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtext('local_sgevea/apiurl', get_string('apiurl', 'local_sgevea'), '', 'https://sgevea.unae.edu.ec/admin/api/'));

    // Encabezado para configuraciones de encuestas
    $settings->add(new admin_setting_heading('surveysettings', get_string('surveysettings', 'local_sgevea'), ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarlistados', get_string('visualizarlistados', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarformulario', get_string('visualizarformulario', 'local_sgevea'), '', 1));

    // Encabezado para configuraciones de anuncios
    $settings->add(new admin_setting_heading('announcementsettings', get_string('announcementsettings', 'local_sgevea'), ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizaranuncio', get_string('visualizaranuncio', 'local_sgevea'), '', 1));
}
