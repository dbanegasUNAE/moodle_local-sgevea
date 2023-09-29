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
$courseid = 25;


require_login();

// Definimos nuestra página
$PAGE->set_url('/local/sgevea/view.php');
$PAGE->set_pagelayout('standard');

//titulos
$PAGE->set_title("Cursos");


//$PAGE->set_heading($course->fullname . ' / ' . $sumaryparticipant);

echo $OUTPUT->header();



$courses = get_courses('all', 'c.fullname ASC', 'c.id,c.shortname,c.fullname');
echo count($courses) . '<br>';
// foreach ($courses as $course) {
//     // Ignorar el curso del sitio.
//     if ($course->id == SITEID) {
//         continue;
//     }

//     $enrolledUsers = get_enrolled_users(context_course::instance($course->id), '', 0, 'u.id, u.firstname, u.lastname');
//     //course context
//     $courseContext = context_course::instance($course->id);

//     //actividades en los cursos



//     echo 'ID: ' . $course->id . '<br>';
//     echo 'Nombre corto: ' . $course->shortname . '<br>';
//     echo 'Nombre completo: ' . $course->fullname . '<br>';
//     echo 'Numero de estudiantes: ' . count($enrolledUsers) . '<br><br>';





//echo html_writer::tag('h2', get_string('subtitle', 'local_sgevea'));


$submitstring = get_string('download');
$url = new moodle_url('/local/sgevea/sgaxls.php', ['action' => 'print_grades', 'id' => $courseid,]);
$button = html_writer::start_tag('button', array('class' => 'btn btn-primary'));
$button .= html_writer::start_tag('a', array('href' => $url->out(false), 'style' => 'color: white;'));
$button .= $submitstring;
$button .= html_writer::end_tag('a');
$button .= html_writer::end_tag('button');
echo $button;

$parentId = 1;

// Función para obtener el estado del curso.

function tareasEntregadas($course)
{
    global $DB, $USER;
    $modinfo = get_fast_modinfo($course); //$in = reset($instance);
    $numeroEntregas = 0;
    $numeroTotalPosibles = 0;
    // Obtener el contexto del curso.
    $context = context_course::instance($course->id);
    // Obtener el rol de estudiante.
        $rolEstudiante = $DB->get_record('role', array('shortname' => 'student'));

    // Recorrer las instancias de módulos en busca de tareas entregadas.
    $assignIds = $DB->get_records_sql("SELECT a.id FROM {assign} a JOIN {course_modules} cm ON cm.instance = a.id WHERE cm.course = ? AND cm.module = (SELECT id FROM {modules} WHERE name = 'assign')", array($course->id));

    $con = count($assignIds);
    // Obtener todos los usuarios inscritos en el curso   
    $numEstudiantes = count(get_role_users($rolEstudiante->id, $context));


    // Verificar si hay entregas.
    foreach ($assignIds as $assignId) {
        $entregas = $DB->get_records('assign_submission', array('assignment' => $assignId->id, 'status' => 'submitted'));
        $numeroEntregas += count($entregas);
        $numeroTotalPosibles += $numEstudiantes; // 

    }
    // Calcular las no entregas
    $numeroNoEntregadas = $numeroTotalPosibles - $numeroEntregas;

    return array(
        'entregadas' => $numeroEntregas,
        'noEntregadas' => $numeroNoEntregadas
    );

}

function NumerodeActiviadesCurso($course)
{
    global $DB, $USER;

    // Obtener la información de módulos del curso.
    $modinfo = get_fast_modinfo($course);

    // Contador para actividades.
    $numActividades = 0;

    // Contar el número de módulos de tipo actividad en el curso.
    foreach ($modinfo->instances as $instance) {
        // Verificar si el módulo es de tipo actividad (no recurso).
        if ($instance->modname !== 'label' && $instance->modname !== 'url') {
            $numActividades++;
        }
    }

    return $numActividades;

}


function obtenerEstadoCurso($courseId)
{
    global $DB;

    // Obtener el curso de la base de datos.
    $curso = $DB->get_record('course', array('id' => $courseId), '*', MUST_EXIST);

    // Obtener la fecha actual en formato de timestamp Unix.
    $fechaActual = time();

    // Obtener las fechas de inicio y finalización del curso.
    $fechaInicioCurso = $curso->startdate;
    $fechaFinCurso = $curso->enddate;

    // Verificar si el curso tiene fechas de inicio y finalización definidas.
    if ($fechaInicioCurso && $fechaFinCurso) {
        // Verificar si el curso ha finalizado.
        if ($fechaActual > $fechaFinCurso) {
            return '<span class="badge badge-danger">FINALIZADO</span>';
        } elseif ($fechaActual < $fechaInicioCurso) {
            return '<span class="badge badge-success">NO HA EMPEZADO</span>';
        } else {
            return '<span class="badge badge-warning">EN PROGRESO</span>';
            ;
        }
    } else {
        return 'sin fechas definidas';
    }
}

//Función  para obtener todos los cursos de las categorías hijos.
function obtenerCursosDeCategorias($parentId)
{
    global $DB;

    $cursos = array();

    // Obtener los cursos de la categoría actual.
    $sql = "SELECT * 
            FROM {course} 
            WHERE category = :category_id";
    $params = array('category_id' => $parentId);
    $cursosActuales = $DB->get_records_sql($sql, $params);

    if (!empty($cursosActuales)) {
        // Agregar los cursos de la categoría actual al arreglo de cursos.
        $cursos = array_merge($cursos, $cursosActuales);
    }

    // Obtener las subcategorías de la categoría actual.
    $sql = "SELECT id 
            FROM {course_categories} 
            WHERE parent = :parent_id";
    $params = array('parent_id' => $parentId);
    $subcategorias = $DB->get_records_sql($sql, $params);

    if (!empty($subcategorias)) {
        foreach ($subcategorias as $subcategoria) {
            // Llamar a la función de manera recursiva para obtener los cursos de las subcategorías.
            $cursosSubcategoria = obtenerCursosDeCategorias($subcategoria->id);
            // Agregar los cursos de las subcategorías al arreglo de cursos.
            $cursos = array_merge($cursos, $cursosSubcategoria);
        }
    }

    return $cursos;
}

try {

    // Crea una tabla y define las cabeceras de las columnas
    $table = new html_table();
    $table->head = ['ID', 'Nombre del Curso', 'Profesor', 'Estado', 'Estudiantes', 'Tareas',
    'Actividades', 'Tareas entregadas', 'Tareas no entregadas','Cuestionarios','Foros','Archivos','Enlaces','Paginas'];
    $table->size = ['5%', '30%', '20%', '10%', '5%', '5%', '5%','5%','5%','5%','5%'];

    // Agregar clase personalizada a la tabla
    //$table->attributes['class'] = 'custom-table';
    $table->attributes['style'] = 'border-collapse: collapse; width: 100%; border: 1px solid #ccc;;'; // Personaliza el tamaño de la tabla

    // echo '<style>
    //     .vertical-text {
    //         transform: rotate(270deg);
    //         white-space: nowrap;
    //     }
    //   </style>';


    $fechaActual = time();

    // Obtener todos los cursos de las categorías desde la categoría padre.
    $cursosTotales = obtenerCursosDeCategorias($parentId);

    // Imprimir los cursos.
    foreach ($cursosTotales as $curso) {
        // Obtener el contexto del curso.
        $context = context_course::instance($curso->id);
        // Carga el curso y la información de finalización del curso
        $info = new completion_info($curso);

        // Obtener el número de tareas completadas del curso.
        $tareasEntregadas = tareasEntregadas($curso);

        // Obtener el número total de tareas en el curso
        $totalTareas = $DB->count_records('assign', array('course' => $curso->id));

        // Obtener el número total de totalcuestionario en el curso
        $totalcuestionario = $DB->count_records('quiz', array('course' => $curso->id));
        //foros
        $totalforos = $DB->count_records('forum', array('course' => $curso->id));
        //archivos
        $totalarchivos = $DB->count_records('resource', array('course' => $curso->id));
        //enlaces
        $totalenlaces = $DB->count_records('url', array('course' => $curso->id));
        //paginas
        $totalpaginas = $DB->count_records('page', array('course' => $curso->id));

        // Obtener el estado del curso.
        $estadoCurso = obtenerEstadoCurso($curso->id);

        // Obtener el rol de estudiante.
        $rolEstudiante = $DB->get_record('role', array('shortname' => 'student'));
        $profesorRole = $DB->get_record('role', array('shortname' => 'editingteacher'));

        // Obtener la cantidad de usuarios con perfil de estudiante matriculados en el curso.
        $numEstudiantes = count(get_role_users($rolEstudiante->id, $context));
        //Obtener docentes del curso 
        $docentes = get_role_users($profesorRole->id, $context);
        $nombresDocentes = array();
        foreach ($docentes as $docente) {
            $nombresDocentes[] = $docente->firstname . ' ' . $docente->lastname;
        }

        // Contar el número total de módulos (actividades y recursos) en el curso.
        $numActividades = NumerodeActiviadesCurso($curso);


        $table->data[] = [
            $curso->id,
            '<a href="' . $CFG->wwwroot . '/course/view.php?id=' . $curso->id . '">' . $curso->fullname . '.</a>',
            implode(", <br>", $nombresDocentes),
            $estadoCurso,
            $numEstudiantes,
            $totalTareas,
            $numActividades,
            $tareasEntregadas['entregadas'],
            $tareasEntregadas['noEntregadas'],
            $totalcuestionario,
            $totalforos,
            $totalarchivos,
            $totalenlaces,
            $totalpaginas


        ];
    }

    // Imprime la tabla
    echo html_writer::table($table);

} catch (Exception $e) {
    // Display the error message if something goes wrong
    echo "Error: " . $e->getMessage();
}

echo $OUTPUT->footer();