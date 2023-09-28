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
 * Muestra el contenido de la pagina.
 *
 * @package     local_sgevea
 * @copyright   2023 Juan Carlos Ulloa (Unae)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_analytics\course;

require_once('../../../config.php');

global $DB, $OUTPUT, $PAGE;
$COURSE;


// Verifique todas las variables requeridas.
//$courseid = required_param('id', PARAM_INT);

// Comprobación de acceso y otras medidas de seguridad
require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

// Definimos nuestra página
$PAGE->set_url('/local/sgevea/view.php');
$PAGE->set_pagelayout('standard');

//titulos
$PAGE->set_title("Usuarios");


//$PAGE->set_heading($course->fullname . ' / ' . $sumaryparticipant);

$renderer = $PAGE->get_renderer('local_sgevea');
// Obtener los datos
//require_once('../classes/dashboard_usuarios.php');
require_once($CFG->dirroot . '/local/sgevea/classes/dashboard_usuarios.php');

$data = dashboard_data::get_last_month_login_data($DB);
//print_r o var_dump para inspeccionar 
//var_dump($data);
// Generar el dashboard
$dashboard = $renderer->render_dashboard($data);





echo $OUTPUT->header();


$courses = get_courses('all', 'c.fullname ASC', 'c.id,c.shortname,c.fullname');
echo count($courses) . '<br>';

// Obtén una instancia del manejador de usuarios
$users = get_users(true, '', true, null, 'firstname ASC');

// Filtra los usuarios para obtener solo los estudiantes
$students = array_filter($users, function($user) {
    $roles = get_user_roles(context_system::instance(), $user->id);
    foreach ($roles as $role) {
        if ($role->shortname === 'student') {
            return true;
        }
    }
    return false;
});

echo count($users).'<br>';
echo count($students);

echo $dashboard;


echo $OUTPUT->footer();