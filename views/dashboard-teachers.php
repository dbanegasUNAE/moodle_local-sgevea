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
require_once($CFG->dirroot . '/course/lib.php');

global $DB, $OUTPUT, $PAGE;
$COURSE;


// Verifique todas las variables requeridas.
//$courseid = required_param('id', PARAM_INT);
//$courseid = 25;


require_login();

// Definimos nuestra página
$PAGE->set_url('/local/sgevea/views/dashboard-teachers.php');
$PAGE->set_pagelayout('standard');

//titulos
$PAGE->set_title("Cursos");


//$PAGE->set_heading($course->fullname . ' / ' . $sumaryparticipant);

echo $OUTPUT->header();



$allcourses = get_courses();
$processed_teachers = [];

foreach ($allcourses as $course) {
    // Obteniendo los profesores de cada curso
    $context = context_course::instance($course->id);
    $teachers = get_enrolled_users($context, 'moodle/course:manageactivities'); // Esto debería obtener a los profesores.

    foreach ($teachers as $teacher) {
        $fullname = fullname($teacher);

        if (!isset($processed_teachers[$fullname])) {
            $processed_teachers[$fullname] = [];
        }

        $processed_teachers[$fullname][] = $course->fullname;
    }
}

echo '<table class="table table-bordered">';
echo '<tr><th>PROFESORES</th><th>CURSOS</th></tr>';

foreach ($processed_teachers as $name => $courses) {
    echo '<tr>';
    echo '<td>' . $name . '</td>';
    echo '<td>';
    echo '<ul class="list-group">';
    foreach( $courses as $course){
        echo '<li class="list-group-item">'.$course.'</li>';
    }
    echo '</ul>';
    //. implode(' / ', $courses) . 
    echo '</td>';
    echo '</tr>';
}

echo '</table>';

echo "<hr>";

echo $OUTPUT->footer();