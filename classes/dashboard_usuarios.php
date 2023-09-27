<?php

// Evita el acceso directo a este script
defined('MOODLE_INTERNAL') || die();

/**
 * Clase para manejar los datos del dashboard
 */
class dashboard_data
{

    /**
     * Método para obtener el número de usuarios que han ingresado en el último mes y la hora en la que lo hicieron
     *
     * @param object $DB Instancia global de la base de datos de Moodle
     * @return array Conjunto de registros que representan los datos
     */
    public static function get_last_month_login_data($DB)
    {

        //ULTIMO ACCESO 

        // $sql = "SELECT DATE(FROM_UNIXTIME(lastaccess)) as day, COUNT(id) as user_count
        //         FROM {user}
        //         WHERE lastaccess >= UNIX_TIMESTAMP(CURRENT_DATE - INTERVAL 3 MONTH)
        //         GROUP BY day
        //         ORDER BY day ASC";

        // try {
        //     return $DB->get_records_sql($sql);
        // } catch (dml_exception $e) {
        //     debugging('SQL Error: ' . $e->getMessage(), DEBUG_DEVELOPER);
        // }


        $sql = "SELECT FROM_UNIXTIME(timecreated, '%Y-%m-%d %H:%00:00') as hour, COUNT(DISTINCT userid) as user_count
        FROM {logstore_standard_log}
        WHERE action = 'loggedin' AND timecreated > ?
        GROUP BY hour";

        // Obtener el tiempo Unix del último mes
        $last_month_time = strtotime('-1 month');

        // Ejecutar la consulta SQL
        return $DB->get_records_sql($sql, array($last_month_time));


    }
}