<?php

namespace local_sgevea;

use \context_course;

class DashboardTeachers extends Dashboard
{

    /**
     * Obtiene los datos de los profesores y los cursos que enseñan
     * 
     * @return array
     */
    public function getTeacherData()
    {
        global $DB;

        $allcourses = get_courses();
        $processed_teachers = [];

        foreach ($allcourses as $course) {
            // Obteniendo los profesores de cada curso
            $context = context_course::instance($course->id);
            $teachers = get_enrolled_users($context, 'moodle/course:manageactivities');

            foreach ($teachers as $teacher) {
                if (!isset($processed_teachers[$teacher->id])) {
                    $processed_teachers[$teacher->id] = [
                        'fullname' => fullname($teacher),
                        'id_number' => $this->configurations->showIDNumber ? $teacher->idnumber : null,
                        'courses' => [],
                        'countCourses' => 0
                    ];
                }

                // Obtener el tipo de enrolamiento del profesor en este curso
                $teacherEnrolType = $this->getTeacherEnrolType($teacher->id, $course->id);

                // Obtener información sobre la visibilidad del curso
                $courseVisibility = $this->isCourseVisible($course->id);

                $courseSummary = $this->configurations->showSummary ? strip_tags(format_text($course->summary)) : null;
                $courseUrl = course_get_url($course->id)->out();
                $processed_teachers[$teacher->id]['courses'][] = [
                    'courseDetail' => $course->fullname,
                    'courseSummary' => $courseSummary,
                    'courseUrl' => $courseUrl,
                    'enrolType' => $teacherEnrolType,
                    'isCourseVisible' => $courseVisibility
                ];

                // Incrementa el contador de cursos para este profesor
                $processed_teachers[$teacher->id]['countCourses']++;
            }
        }

        // Convertimos el array asociativo a una lista simple
        return array_values($processed_teachers);
    }
    private function isCourseVisible($courseId)
    {
        $course = get_course($courseId);
        return $course->visible;
    }
    private function getTeacherEnrolType($teacherId, $courseId)
    {
        global $DB;

        // Reemplaza 'manual' con el nombre del tipo de enrolamiento deseado ('sgaunaesync' en tu caso)
        if ($val = $DB->get_field('enrol', 'enrol', ['courseid' => $courseId, 'enrol' => 'sgaunaesync', 'status' => 0])) {
            $name = "SGA";
        } else if ($val = $DB->get_field('enrol', 'enrol', ['courseid' => $courseId, 'enrol' => 'manual', 'status' => 0])) {
            $name = "MM";
        } else {
            $val = "otro";
            $name = "?";
        }
        return "<span class='badge badge-light' data-toggle='tooltip' data-placement='top' title='{$val}'>{$name}</span>";
    }

    /**
     * Renderiza la vista utilizando el template Mustache
     * 
     * @return string
     */
    public function render()
    {
        $teacherData = $this->getTeacherData();

        $teacherList = array_values($teacherData);

        $dateGen = date('d/m/Y H:i:s');  // Esto te dará la fecha y hora en el formato: "dd/mm

        $data = [
            'headTableName' => get_string('dashboard_teachers_tablename', 'local_sgevea'),
            'headTableData' => get_string('dashboard_teachers_tabledata', 'local_sgevea'),
            'headTableIdNumber' => get_string('dashboard_teachers_tableid', 'local_sgevea'),
            'headTableCountCourse' => get_string('dashboard_teachers_tablecount', 'local_sgevea'),
            'teachers' => $teacherList,
            'showIDNumber' => $this->configurations->showIDNumber,
            'showSummary' => $this->configurations->showSummary,
            'extIcon' => '<span class="external-link-icon"></span>',
            'dateGen' => $dateGen,
            'titGen' => get_string('generated', 'local_sgevea')
        ];
        return $this->renderTemplate('local_sgevea/dashboard_teachers', $data);
    }
}
