<?php

namespace local_sgevea;

use \context_course;

class DashboardCourses extends Dashboard
{
    protected $categoryId;

    public function __construct($categoryId = 0) {
        $this->categoryId = $categoryId;
    }

    function getCoursesDetails()
    {
        global $DB;

        if ($this->categoryId) {
            // Si se ha proporcionado un ID de categoría, sólo obtenemos los cursos de esa categoría.
            $courses = get_courses($this->categoryId);
        } else {
            // Si no se proporciona un ID de categoría, obtenemos todos los cursos.
            $courses = get_courses();
        }

        //$courses = get_courses();
        $coursesDetails = [];

        foreach ($courses as $course) {
            // Ignorar el curso del sitio
            if ($course->id == 1) {
                continue;
            }
            // Obteniendo profesores del curso
            $teacherNames = $this->getTeacherNames($course);
            // Obtener la categoría del curso.
            $categoryName = $this->getCourseCategory($course);
            // Obteniendo el número de estudiantes
            $numStudents = $this->getStudentCountInCourse($course);
            $activities = $this->getCourseActivities($course);
            // URLs de acceso
            $urls = $this->getCourseURLs($course->id, $course->category);
            // Para obtener el número de tareas entregadas y no entregadas
            $statusAssign = $this->getAssignmentsStatus($course);
            //Estado de Inicializacion del curso
            $initializationStatus = $this->getCourseInitializationStatus($course);
            $coursesDetails[] = array_merge([
                'courseId' => $course->id,
                'courseName' => $course->fullname,
                'categoryName' => $categoryName,
                'teacher' => $teacherNames,
                'status' => $course->visible ? 'Visible' : 'Oculto',
                'initializationStatus' => $initializationStatus,
                'numStudents' => $numStudents,
            ], $activities, $urls, $statusAssign);
        }
        return $coursesDetails;
    }
    public function getHierarchicalCategories($parentid = 0)
    {
        global $DB;

        $categories = $DB->get_records('course_categories', ['parent' => $parentid], 'sortorder ASC');
        $hierarchicalCategories = [];

        foreach ($categories as $category) {
            $category->subcategories = $this->getHierarchicalCategories($category->id);
            $hierarchicalCategories[] = $category;
        }

        return $hierarchicalCategories;
    }
    private function getTeacherNames($course)
    {
        $context = context_course::instance($course->id);
        // Obtener profesores del curso
        $teachers = get_enrolled_users($context, 'moodle/course:manageactivities');
        $teacherNames = [];
        foreach ($teachers as $teacher) {
            $teacherNames[] = fullname($teacher);
        }
        return $teacherNames;
    }
    private function getCourseCategory($course)
    {
        global $DB;
        // Obtener la categoría del curso usando el ID de la categoría en el objeto del curso.
        $category = $DB->get_record('course_categories', ['id' => $course->category]);
        if ($category) {
            return $category->name;
        } else {
            // Retornar un valor por defecto o lanzar un error si la categoría no se encuentra.
            return "Categoría no encontrada";
        }
    }
    private function getCourseURLs($courseId, $categoryId)
    {
        $courseURL = new \moodle_url('/course/view.php', ['id' => $courseId]);
        $categoryURL = new \moodle_url('/course/index.php', ['categoryid' => $categoryId]);
        return [
            'courseURL' => $courseURL->out(),
            'categoryURL' => $categoryURL->out(),
        ];
    }
    private function getCourseActivities($course)
    {
        $modinfo = get_fast_modinfo($course);

        $activities = [
            'assignments' => 0,
            'quizzes' => 0,
            'forums' => 0,
            'resources' => 0,
            'urls' => 0,
            'pages' => 0,
            'countActivities' => 0,
            'countResources' => 0
        ];

        foreach ($modinfo->get_cms() as $cm) {
            switch ($cm->modname) {
                case 'assign':
                    $activities['assignments']++;
                    $activities['countActivities']++;
                    break;
                case 'quiz':
                    $activities['quizzes']++;
                    $activities['countActivities']++;
                    break;
                case 'forum':
                    $activities['forums']++;
                    $activities['countActivities']++;
                    break;
                case 'resource':
                    $activities['resources']++;
                    $activities['countResources']++;
                    break;
                case 'url':
                    $activities['urls']++;
                    $activities['countResources']++;
                    break;
                case 'page':
                    $activities['pages']++;
                    $activities['countResources']++;
                    break;
            }
        }

        return $activities;
    }
    private function getStudentCountInCourse($course)
    {
        global $DB;

        // Obtener el contexto del curso.
        $context = context_course::instance($course->id);

        // Obtener el rol de estudiante.
        $rolEstudiante = $DB->get_record('role', array('shortname' => 'student'));

        // Obtener todos los usuarios inscritos en el curso con rol de estudiante   
        $numEstudiantes = count(get_role_users($rolEstudiante->id, $context));

        return $numEstudiantes;
    }

    private function getCourseInitializationStatus($course)
    {
        $currentTime = time();

        // Sin fechas definidas
        if (!$course->startdate || !$course->enddate) {
            return "<span class='badge badge-warning'>Sin fechas definidas</span>";
        }

        // Finalizado
        if ($currentTime > $course->enddate) {
            return "<span class='badge badge-primary'>Finalizado</span>";
        }

        // No ha empezado
        if ($currentTime < $course->startdate) {
            return "<span class='badge badge-info'>No ha empezado</span>";
        }

        // En progreso
        return "<span class='badge badge-success'>En progreso</span>";
    }

    private function getAssignmentsStatus($course)
    {
        global $DB;

        // Obtenemos el contexto del curso.
        $context = context_course::instance($course->id);

        // Obtenemos el rol de estudiante.
        $rolEstudiante = $DB->get_record('role', ['shortname' => 'student']);

        // Contamos el número de estudiantes en el curso usando las capacidades de Moodle.
        $numEstudiantes = count_enrolled_users($context, 'mod/assign:submit');

        // Utilizamos el API de Moodle para obtener los módulos de actividad del tipo "assign" (tareas).
        $assignments = get_coursemodules_in_course('assign', $course->id);

        $numeroEntregas = 0;
        $numeroTotalPosibles = $numEstudiantes * count($assignments);

        // Verificar las entregas de cada tarea.
        foreach ($assignments as $assignModule) {
            // Usamos el API para obtener las entregas.
            $entregas = $DB->get_records('assign_submission', ['assignment' => $assignModule->instance, 'status' => 'submitted']);
            $numeroEntregas += count($entregas);
        }

        $numeroNoEntregadas = $numeroTotalPosibles - $numeroEntregas;

        return [
            'submittedAssignments' => $numeroEntregas,
            'notSubmittedAssignments' => $numeroNoEntregadas
        ];
    }

    public function generateCategoryDropdown($categories, $indent = '')
    {
        $dropdown = '';
        foreach ($categories as $category) {
            $dropdown .= '<option value="' . $category->id . '">' . $indent . $category->name . '</option>';
            if (!empty($category->subcategories)) {
                $dropdown .= $this->generateCategoryDropdown($category->subcategories, $indent . '--');
            }
        }
        return $dropdown;
    }

    /**
     * Renderiza la vista utilizando el template Mustache
     * 
     * @return string
     */
    public function render()
    {
        $coursesDetails = $this->getCoursesDetails();
        $categories = $this->getHierarchicalCategories();
        $dateGen = date('d/m/Y H:i:s');  // Formato: "dd/mm/YYYY H:i:s"
        $data = [
            'courses' => $coursesDetails,
            'categories' => $this->generateCategoryDropdown($categories),
            'dateGen' => $dateGen,
            'titGen' => get_string('generated', 'local_sgevea')
        ];

        return $this->renderTemplate('local_sgevea/dashboard_courses', $data);
    }
}
