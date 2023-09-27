<?php

// Evita el acceso directo a este script
defined('MOODLE_INTERNAL') || die();

/**
 * Renderer para el dashboard
 */
class local_sgevea_renderer extends plugin_renderer_base
{

    /**
     * Genera el HTML y el JavaScript para el gráfico del dashboard
     *
     * @param array $data Datos para el gráfico
     * @return string HTML y JavaScript para el gráfico
     */
    public function render_dashboard1($data)
    {
        // Codifica los datos en formato JSON para usarlos en JavaScript
        $hours = json_encode(array_keys($data));
        $user_counts = json_encode(array_values($data));

        // Utiliza el buffering de salida para capturar el HTML y el JavaScript
        ob_start();
        ?>
        <h2>Dashboard: Usuarios que han ingresado en el último mes por hora</h2>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Código JavaScript para generar el gráfico
            const ctx = document.getElementById('myChart').getContext('2d');
            const hours = <?php echo $hours; ?>;
            const userCounts = <?php echo $user_counts; ?>;

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Número de Usuarios',
                        data: userCounts,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                    }]
                }
            });
        </script>
        <?php

        // Devuelve el HTML y el JavaScript capturados
        return ob_get_clean();
    }

    public function render_dashboard_lastaccess($data)
    {
        $days = json_encode(array_keys($data));
        $user_counts_json  = json_encode(array_values($data));
        // Decodificar el JSON a un array de PHP
        $user_counts_array = json_decode($user_counts_json , true);
        // Extraer los valores de user_count y convertirlos a cadena
        $user_counts_user_count = array_column($user_counts_array, 'user_count');
        $user_counts = json_encode($user_counts_user_count);        

        ob_start();
        ?>
        <h2>Dashboard: Último acceso de usuarios en el último mes</h2>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            const days = <?php echo $days; ?>;
            const userCounts = <?php echo $user_counts; ?>;

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Número de Usuarios',
                        data: userCounts,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                    }]
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function render_dashboard($data) {
        $hours = json_encode(array_keys($data));
        $user_counts_json  = json_encode(array_values($data));
        // Decodificar el JSON a un array de PHP
        $user_counts_array = json_decode($user_counts_json , true);
        // Extraer los valores de user_count y convertirlos a cadena
        $user_counts_user_count = array_column($user_counts_array, 'user_count');
        $user_counts = json_encode($user_counts_user_count);   
        
        ob_start();
        ?>
        <h2>Dashboard: Número de Usuarios que han iniciado sesión en el último mes</h2>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            const hours = <?php echo $hours; ?>;
            const userCounts = <?php echo $user_counts; ?>;
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Número de Usuarios',
                        data: userCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Hora'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Número de Usuarios'
                            }
                        }
                    }
                }
            });
        </script>
        <?php
        
        // Devuelve el HTML y el JavaScript capturados
        return ob_get_clean();
    }

}