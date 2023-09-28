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

    protected function renderTemplate($templateName, $data)
    {
        global $OUTPUT;
        return $OUTPUT->render_from_template($templateName, $data);
    }

    // Otros métodos comunes, como logs, notificaciones, etc.
}
