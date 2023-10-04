<?php

namespace local_sgevea;

class Dashboard
{

    protected $configurations;

    public function __construct()
    {
        $this->loadConfigurations();
    }

    protected function loadConfigurations()
    {
        // Carga las configuraciones comunes para todos los dashboards aquí
        // Por ejemplo:
        $this->configurations = (object) [
            'showSummary' => get_config('local_sgevea', 'dashboard_teachers_showsummary'),
            'showIDNumber' => get_config('local_sgevea', 'dashboard_teachers_showidnumber'),
            //... otras configuraciones comunes
        ];
    }
    public function dep($data, $tit = null)
    {
        $date = date('Y-m-d H:i:s');
        $format = print_r("<div><small>* BEG Debug [{$tit}] > $date | -> ");
        if (isset($data)) {
            $format .= print_r('<pre>');
            $format .= print_r($data);
            $format .= print_r('</pre>');
        } else
            $format .= print_r(' *null* ');
        $format = print_r("<- | END Debug ></small></div>");
        return $format;
    }
    protected function renderTemplate($templateName, $data)
    {
        global $OUTPUT;
        return $OUTPUT->render_from_template($templateName, $data);
    }

    // Otros métodos comunes, como logs, notificaciones, etc.
}
