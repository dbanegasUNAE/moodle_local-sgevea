<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && has_capability('local/sgevea:manage', context_system::instance())) {
    //LIST OF ROLES FOR SELECT
    $roles = get_all_roles();
    $role_options = [];
    foreach ($roles as $role) {
        $role_options[$role->id] = $role->shortname;
    }

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
    $ADMIN->add('sgevea_dashboard', new admin_externalpage('sgevea_dashboard_teachers', get_string('dashboard_teachers', 'local_sgevea'), $url_users));

    // Configuraciones específicas del plugin sgevea
    $settings = new admin_settingpage('sgevea_settings', get_string('settings', 'local_sgevea'));
    $ADMIN->add('sgevea', $settings);

    // Encabezado para configuraciones generales
    $settings->add(new admin_setting_heading('generalsettings', get_string('generalsettings', 'local_sgevea'), ''));
    //CONTENT OF TOP MY
    $settings->add(new admin_setting_configcheckbox('local_sgevea/generalsettings_my_top', get_string('generalsettings_my_top', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtextarea('local_sgevea/generalsettings_my_top_cont', get_string('generalsettings_my_top_cont', 'local_sgevea'), get_string('generalsettings_my_top_desc', 'local_sgevea'), '', PARAM_RAW, 10));
    $settings->add(new admin_setting_configmultiselect('local_sgevea/generalsettings_my_top_roles', get_string('generalsettings_my_top_roles', 'local_sgevea'), '', [], $role_options));
    //CONTENT OF BOTTOM MY
    $settings->add(new admin_setting_configcheckbox('local_sgevea/generalsettings_my_bottom', get_string('generalsettings_my_bottom', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtextarea('local_sgevea/generalsettings_my_bottom_cont', get_string('generalsettings_my_bottom_cont', 'local_sgevea'), get_string('generalsettings_my_bottom_desc', 'local_sgevea'), '', PARAM_RAW, 10));
    $settings->add(new admin_setting_configmultiselect('local_sgevea/generalsettings_my_bottom_roles', get_string('generalsettings_my_bottom_roles', 'local_sgevea'), '', [], $role_options));

    // Encabezado para configuraciones de encuestas
    $settings->add(new admin_setting_heading('surveysettings', get_string('surveysettings', 'local_sgevea'), ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/survey_status', get_string('survey_status', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtext('local_sgevea/survey_token', get_string('survey_token', 'local_sgevea'), '', ''));
    $settings->add(new admin_setting_configtext('local_sgevea/survey_apiurl', get_string('survey_apiurl', 'local_sgevea'), '', 'https://sgevea.unae.edu.ec/admin/api/'));

    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarlistados', get_string('visualizarlistados', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarformulario', get_string('visualizarformulario', 'local_sgevea'), '', 1));

    // Encabezado para configuraciones de anuncios
    $settings->add(new admin_setting_heading('announcementsettings', get_string('announcementsettings', 'local_sgevea'), ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/ann_status', get_string('ann_status', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtext('local_sgevea/ann_token', get_string('ann_token', 'local_sgevea'), '', ''));
    $settings->add(new admin_setting_configtext('local_sgevea/ann_apiurl', get_string('ann_apiurl', 'local_sgevea'), '', 'https://sgevea.unae.edu.ec/admin/api/'));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizaranuncio', get_string('visualizaranuncio', 'local_sgevea'), '', 1));

    // Encabezado para configuraciones de Dashboard
    $settings->add(new admin_setting_heading('dashboardsettings', get_string('dashboardsettings', 'local_sgevea'), ''));

    // Encabezado para configuraciones de Dashboard - Profesores
    $settings->add(new admin_setting_heading('dashboardteacherssettings', get_string('dashboardteacherssettings', 'local_sgevea'), ''));

    // Configuración para mostrar el id_number de profesores
    $settings->add(new admin_setting_configcheckbox(
        'local_sgevea/dashboard_teachers_showidnumber',
        get_string('dashboard_teachers_showidnumber', 'local_sgevea'),
        get_string('dashboard_teachers_showidnumber_desc', 'local_sgevea'),
        1
    ));

    // Configuración para mostrar el resumen en el dashboard de profesores
    $settings->add(new admin_setting_configcheckbox(
        'local_sgevea/dashboard_teachers_showsummary',
        get_string('dashboard_teachers_showsummary', 'local_sgevea'),
        get_string('dashboard_teachers_showsummary_desc', 'local_sgevea'),
        0
    ));
    // Encabezado para configuraciones de Dashboard - Acceso Usuarios
    $settings->add(new admin_setting_heading('dashboarduseraccesssettings', get_string('dashboarduseraccesssettings', 'local_sgevea'), ''));

    // Configuración para mostrar el tipo de grafico
    $settings->add(new admin_setting_configtext('local_sgevea/dashboard_useraccess_viewday_graph', get_string('dashboard_useraccess_viewday_graph', 'local_sgevea'), get_string('dashboard_useraccess_viewday_graph_des', 'local_sgevea'), 'bar'));
    $settings->add(new admin_setting_configtext('local_sgevea/dashboard_useraccess_viewhour_graph', get_string('dashboard_useraccess_viewhour_graph', 'local_sgevea'), get_string('dashboard_useraccess_viewhour_graph_des', 'local_sgevea'), 'line'));
}
