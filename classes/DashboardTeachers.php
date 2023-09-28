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
                        'courses' => []
                    ];
                }

                $courseSummary = $this->configurations->showSummary ? strip_tags(format_text($course->summary)) : null;
                $processed_teachers[$teacher->id]['courses'][] = [
                    'courseDetail' => $course->fullname,
                    'courseSummary' => $courseSummary
                ];
            }
        }

        // Convertimos el array asociativo a una lista simple
        return array_values($processed_teachers);
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


        $data = [
            'header' => get_string('pluginname','local_sgevea').' - '.get_string('dashboard_teachers','local_sgevea'),
            'headTableName' => get_string('dashboard_teachers_tablename','local_sgevea'),
            'headTableData' => get_string('dashboard_teachers_tabledata','local_sgevea'),
            'teachers' => $teacherList,
            'showIDNumber' => $this->configurations->showIDNumber,
            'showSummary' => $this->configurations->showSummary
        ];
        return $this->renderTemplate('local_sgevea/dashboard_teachers', $data);
    }
}
