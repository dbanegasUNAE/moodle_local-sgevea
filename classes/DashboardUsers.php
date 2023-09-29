<?php

namespace local_sgevea;

use \context_course;

class DashboardUsers extends Dashboard
{

    /**
     * Obtiene los datos de los profesores y los cursos que enseñan
     * 
     * @return array
     */
    public function get_last_month_login_data()
    {
        global $DB;

        $sql = "SELECT FROM_UNIXTIME(timecreated, '%Y-%m-%d') as hour, COUNT(DISTINCT userid) as user_count
        FROM {logstore_standard_log}
        WHERE action = 'loggedin' AND timecreated > ?
        GROUP BY hour";

        // Obtener el tiempo Unix del último mes
        $last_month_time = strtotime('-1 month');

        // Ejecutar la consulta SQL
        $data = $DB->get_records_sql($sql, array($last_month_time));
        $formattedData = array_values($data);
        //print_r($formattedData);
        return $formattedData;
    }


    /**
     * Renderiza la vista utilizando el template Mustache
     * 
     * @return string
     */
    public function render()
    {
        $dataAcc = array_values($this->get_last_month_login_data());

        // Preparando datos para Chart.js
        $labels = [];
        $values = [];
        foreach ($dataAcc as $entry) {
            $labels[] = $entry->hour;
            $values[] = $entry->user_count;
        }

        $dateGen = date('d/m/Y H:i:s');  // Esto te dará la fecha y hora en el formato: "dd/mm
        $data = [
            'labels' => json_encode($labels),
            'data' => json_encode($values),
            'dateGen' => $dateGen,
            'titGen' => get_string('generated', 'local_sgevea')
        ];
        return $this->renderTemplate('local_sgevea/dashboard_users', $data);
    }
}
