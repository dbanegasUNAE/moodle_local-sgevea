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

                $teacherEnrolType = $this->getTeacherEnrolType($course->id, $teacher->id);

                $courseVisibility = $this->isCourseVisible($course->id);

                $courseSummary = $this->configurations->showSummary ? $course->summary : null;
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
    /**
     * Retorna el valor si un curso esta visible
     * 
     * @return int
     */
    private function isCourseVisible($courseId)
    {
        $course = get_course($courseId);
        return $course->visible;
    }
    /**
     * Obtiene el tipo de Matriculacion de un usuario en un curso
     * 
     * @return string
     */
    private function getTeacherEnrolType($courseId, $teacherId): string
    {
        global $DB;
        $sql = "SELECT e.enrol
                FROM {enrol} e
                JOIN {user_enrolments} ue ON ue.enrolid = e.id
                WHERE e.courseid = :courseid AND ue.userid = :userid;
                ";
        $params = [
            'courseid' => $courseId,
            'userid' => $teacherId,
        ];
        $enrol = $DB->get_field_sql($sql, $params);
        switch ($enrol) {
            case "sgaunaesync":
                $name = "SGA";
                break;
            case "manual":
                $name = "MM";
                break;
            default:
                $name = "?";
                break;
        }
        return "<span class='badge badge-light' data-toggle='tooltip' data-placement='top' title='{$enrol}'>{$name}</span>";
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
