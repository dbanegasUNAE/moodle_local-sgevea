<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && has_capability('local/sgeveaSurvey:manage', context_system::instance())) {
    $settings = new admin_settingpage('local_sgeveasurvey', get_string('pluginname', 'local_sgeveasurvey'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_sgeveasurvey/token', get_string('token', 'local_sgeveasurvey'), '', ''));
    $settings->add(new admin_setting_configcheckbox('local_sgeveasurvey/status', get_string('status', 'local_sgeveasurvey'), '', 0));
    $settings->add(new admin_setting_configtext('local_sgeveasurvey/apiurl', get_string('apiurl', 'local_sgeveasurvey'), '', 'https://sgevea.unae.edu.ec/admin/api/v1/index.php'));
}
