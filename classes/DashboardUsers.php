<?php

namespace local_sgevea;

use \context_course;

class DashboardUsers extends Dashboard
{
    // Propiedades para las fechas
    protected $startDate;
    protected $endDate;
    protected $view;
    protected $graph;

    public function __construct($startDate = null, $endDate = null)
    {
        // Llama a la función setDates solo si se proporcionan $startDate y $endDate.
        if (isset($startDate) || isset($endDate)) {
            $this->setDates($startDate, $endDate);
        }
    }

    public function setDates($startDate = null, $endDate = null)
    {
        // Si no se proporciona una fecha de inicio, establece la fecha de inicio en la fecha actual con hora 00:00:00.
        if ($startDate === null) {
            $startDate = strtotime(date('Y-m-d 00:00:00'));
        } elseif ($startDate !== null) {
            $startDateUnix = strtotime($startDate);
            if ($startDateUnix !== false) {
                $startDate = strtotime(date('Y-m-d 00:00:00', $startDateUnix));
            } else {
                // Manejar error o lanzar una excepción si la fecha de inicio es inválida.
                // Ejemplo de manejo de error:
                //throw new \Exception('Fecha de inicio inválida.');
            }
        }

        // Si se proporciona una fecha de fin, valida y ajusta la fecha.
        if ($endDate !== null) {
            $endDateUnix = strtotime($endDate);
            if ($endDateUnix !== false) {
                // Si la fecha de fin es menor que la fecha de inicio, establece la fecha de fin igual a la fecha de inicio con hora 23:59:59.
                if ($endDateUnix < $startDate) {
                    $endDate = strtotime(date('Y-m-d 23:59:59', $startDate));
                } else {
                    // Si la fecha de fin es mayor o igual a la fecha de inicio, ajusta la hora de fin a 23:59:59.
                    $endDate = strtotime(date('Y-m-d 23:59:59', $endDateUnix));
                }
            } else {
                // Manejar error o lanzar una excepción si la fecha de fin es inválida.
                // Ejemplo de manejo de error:
                //throw new \Exception('Fecha de fin inválida.');
            }
        } else {
            // Si no se proporciona una fecha de fin, establece la fecha de fin igual a la fecha de inicio con hora 23:59:59.
            $endDate = strtotime(date('Y-m-d 23:59:59', $startDate));
        }

        // Asigna las fechas ajustadas a las propiedades de la clase.
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function get_last_month_login_data()
    {
        global $DB;
        echo __FUNCTION__ . "<br>";
        $sql = "SELECT FROM_UNIXTIME(timecreated, '%Y-%m-%d') as hour, COUNT(DISTINCT userid) as user_count
        FROM {logstore_standard_log}
        WHERE action = 'loggedin' AND timecreated BETWEEN ? AND ?
        GROUP BY hour";
        // Ejecutar la consulta SQL
        $data = $DB->get_records_sql($sql, array($this->startDate, $this->endDate));
        $formattedData = array_values($data);
        return $formattedData;
    }

    public function getAccessCountByDateRange()
    {
        global $DB;
        $sql = "SELECT FROM_UNIXTIME(timecreated, '%Y-%m-%d') as hour, COUNT(DISTINCT userid) as user_count
            FROM {logstore_standard_log}
            WHERE action = 'loggedin' AND timecreated BETWEEN ? AND ?
            GROUP BY hour";
        $data = $DB->get_records_sql($sql, array($this->startDate, $this->endDate));
        $formattedData = array_values($data);
        return $formattedData;
    }

    public function getAccessCountByHourRange()
    {
        global $DB;
        $sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(timecreated), '%Y-%m-%d %H:00') as hour, COUNT(DISTINCT userid) as user_count
            FROM {logstore_standard_log}
            WHERE action = 'loggedin' AND timecreated BETWEEN :start_date AND :end_date
            GROUP BY hour";

        $params = [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
        $data = $DB->get_records_sql($sql, $params);
        return $data;
    }


    public function setView($view)
    {
        $this->view = $view;
        if (empty($this->startDate)) {
            if ($view == 'date_range') {
                $this->graph = 'bar';
                // Configura las fechas para "Accesos por fechas."
                $this->setDates(
                    strtotime('-1 month'),  // Fecha de inicio: Fecha actual menos 1 mes, 00:00:00.
                    strtotime('now 23:59:59')       // Fecha de fin: Fecha actual, 23:59:59.
                );
            } elseif ($view == 'hour_range') {
                $this->graph = 'line';
                // Configura las fechas para "Accesos por horas."
                $this->setDates(
                    strtotime('today'),    // Fecha de inicio: Fecha actual, 00:00:00.
                    strtotime('today 23:59:59')  // Fecha de fin: Fecha actual, 23:59:59.
                );
            }
        }
    }

    /**
     * Renderiza la vista utilizando el template Mustache
     * 
     * @return string
     */
    public function render()
    {
        $data = [];
        $labels = [];
        $values = [];

        if ($this->view == 'date_range') {
            $this->graph = get_config('local_sgevea', 'dashboard_useraccess_viewday_graph');
            $data = $this->getAccessCountByDateRange();
        } elseif ($this->view == 'hour_range') {
            $this->graph = get_config('local_sgevea', 'dashboard_useraccess_viewhour_graph');
            $data = $this->getAccessCountByHourRange();
        }
        foreach ($data as $entry) {
            $labels[] = $entry->hour; // Ajusta esto según tus datos
            $values[] = $entry->user_count; // Ajusta esto según tus datos
        }

        $dateGen = date('d/m/Y H:i:s');

        $startDate = isset($this->startDate) ? date('Y-m-d', $this->startDate) : null;
        $endDate = isset($this->endDate) ? date('Y-m-d', $this->endDate) : null;
        $options = array(
            array("value" => "date_range", "name" => get_string('dashboard_users_view_dates', 'local_sgevea'), 'select' => ($this->view == 'date_range') ? 'selected' : null),
            array("value" => "hour_range", "name" => get_string('dashboard_users_view_hours', 'local_sgevea'), 'select' => ($this->view == 'hour_range') ? 'selected' : null),
        );
        $data = [
            'labels' => json_encode($labels),
            'data' => json_encode($values),
            'dateGen' => $dateGen,
            'titGen' => get_string('generated', 'local_sgevea'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'optiontit' => get_string('dashboard_users_view', 'local_sgevea'),
            'options' => $options,
            'optionstartdate' => get_string('dashboard_users_view_startdate', 'local_sgevea'),
            'optionenddate' => get_string('dashboard_users_view_enddate', 'local_sgevea'),
            'optionsshow' => get_string('dashboard_users_view_show', 'local_sgevea'),
            'graph' => $this->graph ?? 'bar'
        ];

        return $this->renderTemplate('local_sgevea/dashboard_users', $data);
    }
}
