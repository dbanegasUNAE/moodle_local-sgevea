<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External api functions for local_sgevea
 *
 * @package   local_sgevea
 * @copyright 2023 Entornos Virtuales Unae
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Juan Carlos Ulloa
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_sgevea_get_live_status' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'get_live_status',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Get live status of server',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ],
    'local_sgevea_get_last_24_hours_usage' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'get_last_24_hours_usage',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Get live status of server',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ],
    'local_sgevea_get_plugins_update' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'get_plugins_update',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Get updates of edwiser plugins or other plugins based on parameter',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ],
    'local_sgevea_send_contactus_email' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'send_contactus_email',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Send contact us email',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ],
    'local_sgevea_cursos' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'cursos',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Prueba',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ],
    'local_sgevea_informaciongeneral' => [
        'classname' => 'local_sgevea\externallib',
        'methodname' => 'informaciongeneral',
        'classpath' => 'local/sgevea/externallib.php',
        'description' => 'Informacion general de las instancias',
        'type' => 'read',
        'loginrequired' => true,
        'ajax' => true,
    ]
];
