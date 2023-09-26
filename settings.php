<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && has_capability('local/sgevea:manage', context_system::instance())) {
    $settings = new admin_settingpage('local_sgevea', get_string('pluginname', 'local_sgevea'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_sgevea/token', get_string('token', 'local_sgevea'), '', ''));
    $settings->add(new admin_setting_configcheckbox('local_sgevea/status', get_string('status', 'local_sgevea'), '', 0));
    $settings->add(new admin_setting_configtext('local_sgevea/apiurl', get_string('apiurl', 'local_sgevea'), '', 'https://sgevea.unae.edu.ec/admin/api/v1/index.php'));
}
