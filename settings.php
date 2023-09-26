<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && has_capability('local/sgevea:manage', context_system::instance())) {
    $settings = new admin_settingpage('local_sgevea', get_string('pluginname', 'local_sgevea'));
    $ADMIN->add('localplugins', $settings);

    // Encabezado para configuraciones generales
    $settings->add(new admin_setting_heading('generalsettings', get_string('generalsettings', 'local_sgevea'), ''));

    $settings->add(new admin_setting_configtext('local_sgevea/token', get_string('token', 'local_sgevea'), '', ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/status', get_string('status', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configtext('local_sgevea/apiurl', get_string('apiurl', 'local_sgevea'), '', 'https://sgevea.unae.edu.ec/admin/api/'));

    // Encabezado para configuraciones de encuestas
    $settings->add(new admin_setting_heading('surveysettings', get_string('surveysettings', 'local_sgevea'), ''));
    // Configurations for Surveys 
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarlistados', get_string('visualizarlistados', 'local_sgevea'), '', 1));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizarformulario', get_string('visualizarformulario', 'local_sgevea'), '', 1));

    // Encabezado para configuraciones de anuncios
    $settings->add(new admin_setting_heading('announcementsettings', get_string('announcementsettings', 'local_sgevea'), ''));
    // Configurations for Announce
    $settings->add(new admin_setting_configcheckbox('local_sgevea/visualizaranuncio', get_string('visualizaranuncio', 'local_sgevea'), '', 1));
}
