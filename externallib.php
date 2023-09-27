<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local class of sgevea external api functions
 *
 * @package   local_sgevea
 * @copyright 2019 WisdmLabs <edwiser@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Yogesh Shirsath
 */

namespace local_sgevea;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/local/sgevea/classes/usage.php');

use external_function_parameters;
use external_single_structure;
use external_value;
use context_system;
use external_api;
use moodle_url;
use stdClass;

/**
 * This class implements services for block_edwiser_site_monitor
 *
 * @copyright 2019 WisdmLabs <edwiser@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class externallib extends external_api
{

    /**
     * Describes the parameters for get blocks function
     *
     * @return external_function_parameters
     */
    public static function get_live_status_parameters()
    {
        return new external_function_parameters(array());
    }

    /**
     * Get live_status of server
     *
     * @return array
     */
    public static function get_live_status()
    {
        self::validate_context(context_system::instance());
        $usage = usage::get_instance();
        return array(
            "cpu" => $usage->get_cpu_usage(),
            "memory" => $usage->get_memory_usage(),
            "storage" => $usage->get_storage_usage(),
            "liveusers" => $usage->get_live_users()
        );
    }

    /**
     * Returns description of method parameters for get live_status function
     *
     * @return external_single_structure
     */
    public static function get_live_status_returns()
    {
        return new external_single_structure([
            "cpu" => new external_value(PARAM_FLOAT, "cpu usage"),
            "memory" => new external_value(PARAM_FLOAT, "memory usage"),
            "storage" => new external_value(PARAM_FLOAT, "storage usage"),
            "liveusers" => new external_value(PARAM_INT, "number of live users")
        ]);
    }

    /**
     * Describes the parameters for get blocks function
     *
     * @return external_function_parameters
     */
    public static function get_last_24_hours_usage_parameters()
    {
        return new external_function_parameters(
            array(
                "timestamp" => new external_value(PARAM_INT, "Timestamp of day to get usage", VALUE_REQUIRED)
            )
        );
    }

    /**
     * Get live_status based on filters
     *
     * @param int $timestamp timestamp of date in integer format
     *
     * @return array
     */
    public static function get_last_24_hours_usage($timestamp)
    {
        global $DB;
        self::validate_context(context_system::instance());
        utility::edwiser_site_monitor_log_usage();

        if ($timestamp == 0) {
            $timestamp = strtotime(date('d-m-Y', time()));
        }
        $usage = $DB->get_records_sql(
            "SELECT time, cpu, memory, storage
               FROM {block_edwiser_site_monitor}
              WHERE time >= ? AND time < ?
              ORDER BY time ASC",
            array($timestamp, $timestamp + 24 * 60 * 60)
        );
        $cpu = $memory = $storage = $time = [];
        foreach ($usage as $use) {
            $time[] = date("H:i", $use->time);
            $cpu[] = $use->cpu;
            $memory[] = $use->memory;
            $storage[] = $use->storage;
        }
        return array(
            "time" => json_encode($time),
            "cpu" => json_encode($cpu),
            "memory" => json_encode($memory),
            "storage" => json_encode($storage)
        );
    }

    /**
     * Returns description of method parameters for get live_status function
     *
     * @return external_single_structure
     */
    public static function get_last_24_hours_usage_returns()
    {
        return new external_single_structure(
            [
                "time" => new external_value(PARAM_RAW, "timeline of usage"),
                "cpu" => new external_value(PARAM_RAW, "cpu usage"),
                "memory" => new external_value(PARAM_RAW, "memory usage"),
                "storage" => new external_value(PARAM_RAW, "storage usage"),
            ]
        );
    }

    /**
     * Describes the parameters for get blocks function
     *
     * @return external_function_parameters
     */
    public static function get_plugins_update_parameters()
    {
        return new external_function_parameters(array());
    }

    /**
     * Get live_status based on filters
     *
     * @return array
     */
    public static function get_plugins_update()
    {
        global $PAGE;
        self::validate_context(context_system::instance());
        $plugins = new plugins();
        $time = time();
        return array(
            'lasttimefetched' => get_string('checkforupdateslast', 'core_plugin', date('d F Y, h:i A e', $time)),
            'plugins' => $PAGE->get_renderer('block_edwiser_site_monitor')->render_from_template(
                'block_edwiser_site_monitor/plugins',
                $plugins->get_plugins()
            )
        );
    }

    /**
     * Returns description of method parameters for get live_status function
     *
     * @return external_single_structure
     */
    public static function get_plugins_update_returns()
    {
        return new external_single_structure(
            array(
                'lasttimefetched' => new external_value(PARAM_RAW, "Last time when updates is checked"),
                'plugins' => new external_value(PARAM_RAW, "Table of installed edwiser plugins or other plugins and there updates")
            )
        );
    }

    /**
     * Describes the parameters for get blocks function
     *
     * @return external_function_parameters
     */
    public static function send_contactus_email_parameters()
    {
        return new external_function_parameters(
            array(
                'firstname' => new external_value(PARAM_RAW, "firstname of user", VALUE_REQUIRED),
                'lastname' => new external_value(PARAM_RAW, "lastname of user", VALUE_REQUIRED),
                'email' => new external_value(PARAM_EMAIL, "email of user", VALUE_REQUIRED),
                'subject' => new external_value(PARAM_RAW, "subject for email", VALUE_REQUIRED),
                'message' => new external_value(PARAM_RAW, "message for email", VALUE_REQUIRED)
            )
        );
    }

    /**
     * Send email to edwiser@wisdmlabs.com with submitted data in the contact us form
     *
     * @param  string $firstname First name of user
     * @param  string $lastname  Last name of user
     * @param  string $email     Email id of user
     * @param  string $subject   Subject for email
     * @param  string $message   Message body for email
     * @return array             status, header and message
     */
    public static function send_contactus_email($firstname, $lastname, $email, $subject, $message)
    {
        self::validate_context(context_system::instance());
        $admin = get_admin();
        $admin->email = $email;
        $admin->firstname = $firstname;
        $admin->lastname = $lastname;
        $support = new stdClass;
        $support->id = -99;
        $support->email = ESM_SUPPORT_EMAIL;
        $status = utility::edwiser_site_monitor_send_email(
            $admin,
            $support,
            $subject,
            $message,
            $email
        );
        $subject = get_string('thankssubject', 'block_edwiser_site_monitor');
        $message = get_string(
            'thanksmessage',
            'block_edwiser_site_monitor',
            array(
                'user' => $firstname,
                'email' => ESM_SUPPORT_EMAIL
            )
        );
        $admin->firstname = 'Edwiser';
        $admin->lastname = '';
        $status &= utility::edwiser_site_monitor_send_email(
            $admin,
            $admin,
            $subject,
            $message
        );
        if (!$status) {
            return array(
                'status' => false,
                'header' => get_string('failed', 'block_edwiser_site_monitor'),
                'message' => get_string('emailfailed', 'block_edwiser_site_monitor') . get_string(
                    'checksettings',
                    'block_edwiser_site_monitor',
                    array(
                        'link' => (
                            new moodle_url(
                            '/admin/settings.php',
                                array(
                                'section' => 'outgoingmailconfig'
                                )
                            )
                        )->__toString(),
                        'text' => get_string('outgoingmailconfig', 'core_admin')
                    )
                )
            );
        }
        return array(
            'status' => true,
            'header' => get_string('success'),
            'message' => get_string('emailsuccess', 'block_edwiser_site_monitor')
        );
    }

    /**
     * Returns description of method parameters for get live_status function
     *
     * @return external_single_structure
     */
    public static function send_contactus_email_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, "Email send status"),
                'header' => new external_value(PARAM_ALPHA, "Email sending status header"),
                'message' => new external_value(PARAM_RAW, "Email sending status message")
            )
        );
        //return new external_value(PARAM_RAW, "Status of sent email");
    }




    /**
     * Describes the parameters for get blocks function
     *
     * @return external_function_parameters
     */
    public static function cursos1_parameters()
    {
        return new external_function_parameters(array());
    }

    /**
     * Get live_status of server
     *
     * @return array
     */
    public static function cursos1()
    {
        self::validate_context(context_system::instance());
        utility::edwiser_site_monitor_log_usage();
        $usage = usage::get_instance();
        return array(
            "cpu" => $usage->get_cpu_usage(),
            "memory" => $usage->get_memory_usage(),
            "storage" => $usage->get_storage_usage(),
            "liveusers" => $usage->get_live_users(),

        );
    }

    /**
     * Returns description of method parameters for get live_status function
     *
     * @return external_single_structure
     */
    public static function cursos1_returns()
    {
        return new external_single_structure([
            "cpu" => new external_value(PARAM_FLOAT, "cpu usage"),
            "memory" => new external_value(PARAM_FLOAT, "memory usage"),
            "storage" => new external_value(PARAM_FLOAT, "storage usage"),
            "liveusers" => new external_value(PARAM_INT, "number of live users"),
            "liveusers1" => new external_value(PARAM_INT, "number of live users")
        ]);
    }



    // --------

    public static function cursos()
    {
        global $CFG, $DB;
        require_once($CFG->libdir . "/gradelib.php");

        // Parámetros de entrada
        // $params = self::validate_parameters(
        //     self::cursos_parameters(),
        //     ['courseid' => $courseid, 'userid' => $userid]
        // );

        // Validar el contexto
        // $context = \context_course::instance($params['courseid']);
        // self::validate_context($context);

        // Consultar las calificaciones aquí
        //$grades = grade_get_grades($params['courseid'], 'mod', 'tu_plugin', 0, $params['userid']);
        $cursos = $DB->get_records('course');

        // Preparar la estructura de retorno
        $result = [];
        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'fullname' => $course->fullname,
                // Agrega más campos aquí según sea necesario
            ];
        }



        return ['courses' => $cursos];
    }

    public static function cursos_parameters()
    {
        return new external_function_parameters(array());
    }



    public static function cursos_returns()
    {
        return new external_single_structure([
            // 'courses' => new \external_multiple_structure(
            //     generate_course_structure()
            // )
            'courses' => new \external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'ID del curso'),
                    'fullname' => new external_value(PARAM_TEXT, 'Nombre completo del curso'),
                    // Define más campos aquí según sea necesario
                ])
            )
        ]);
    }



    // ------

    // informacion general

    public static function informaciongeneral()
    {
        global $CFG, $DB;

        //todos los usuarios
        $numusers = $DB->count_records('user');
        // usuarios activos
        $users = get_users(true, '', true, null, 'firstname ASC');
        //usuarios eliminados
        $sql = "SELECT * FROM {user} WHERE deleted = 1 ORDER BY firstname ASC";
        $usersdelete = $DB->get_records_sql($sql);
        //usurios suspendidos
        $sql = "SELECT * FROM {user} WHERE suspended = 1 ORDER BY firstname ASC";
        $userssuspendt = $DB->get_records_sql($sql);
        //usurios con cuentas manuales
        $sql = "SELECT * FROM {user} WHERE auth = 'manual' ORDER BY firstname ASC";
        $usersmanuales = $DB->get_records_sql($sql);
        //usurios con cuentas cas
        $sql = "SELECT * FROM {user} WHERE auth = 'casattras' ORDER BY firstname ASC";
        $userscas = $DB->get_records_sql($sql);
        // Obtener el rol de estudiante.
        $rolEstudiante = $DB->get_record('role', array('shortname' => 'student'));
        // Obtener el rol de profesor con permisos de edicion.
        $profesorRole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        //numero de estudiantes 
        $sql = "SELECT COUNT(DISTINCT u.id)
        FROM {user} u
        JOIN {role_assignments} ra ON ra.userid = u.id
        WHERE ra.roleid = '$rolEstudiante->id' AND u.deleted = 0";
        $count_students = $DB->count_records_sql($sql);

        //numero de docentes
        $sql = "SELECT COUNT(DISTINCT u.id)
        FROM {user} u
        JOIN {role_assignments} ra ON ra.userid = u.id
        WHERE ra.roleid = '$profesorRole->id' AND u.deleted = 0";
        $count_teacher = $DB->count_records_sql($sql);


        //
        $cursos = $DB->get_records('course');


        $numcourses = $DB->count_records('course');


        //self::validate_context(context_system::instance());        
        $usage = usage::get_instance();


        // Preparar la estructura de retorno
        return array(
            'numusers' => $numusers,
            'numuserdelete' => count($usersdelete),
            'numuseractivos' => count($users),
            'numusersuspent' => count($userssuspendt),
            'numusersmanuales' => count($usersmanuales),
            'numuuserscas' => count($userscas),
            'numestudent' => $count_students,
            'numteachers' => $count_teacher,
            'numcourses' => count($cursos),
            "cpu" => $usage->get_cpu_usage(),
            "memory" => $usage->get_memory_usage(),
            "memorytotal" => $usage->get_total_memory(),
            "storage" => $usage->get_storage_usage(),
            "storagetotal" => $usage->get_total_storage(),
            "liveusers" => $usage->get_live_users()
        );

    }

    public static function informaciongeneral_parameters()
    {
        return new external_function_parameters(array());
    }



    public static function informaciongeneral_returns()
    {
        return new external_single_structure(
            array(
                'numusers' => new external_value(PARAM_INT, 'Número de usuarios.'),
                'numuserdelete' => new external_value(PARAM_INT, 'Número de usuarios eliminados.'),
                'numuseractivos' => new external_value(PARAM_INT, 'Número de usuarios Activos.'),
                'numusersuspent' => new external_value(PARAM_INT, 'Número de usuarios supendidos.'),
                'numusersmanuales' => new external_value(PARAM_INT, 'Número de usuarios cuentas manueles.'),
                'numuuserscas' => new external_value(PARAM_INT, 'Número de usuarios cuentas cas.'),
                'numestudent' => new external_value(PARAM_INT, 'Número de estudiantes.'),
                'numteachers' => new external_value(PARAM_INT, 'Número de profesores.'),
                'numcourses' => new external_value(PARAM_INT, 'Número de cursos.'),
                "cpu" => new external_value(PARAM_FLOAT, "cpu usage"),
                "memory" => new external_value(PARAM_FLOAT, "memory usage"),
                "memorytotal" => new external_value(PARAM_FLOAT, "Memoriatotal"),
                "storage" => new external_value(PARAM_FLOAT, "storage usage"),
                "storagetotal" => new external_value(PARAM_FLOAT, "Storage total"),
                "liveusers" => new external_value(PARAM_INT, "number of live users")
            )
        );
    }



    // ------


}

//estructurar para enviar informacion con consulta directa y enviar todos los datos
function generate_course_structure()
{
    global $DB;

    $coursefields = $DB->get_columns('course');
    $structure = [];

    foreach ($coursefields as $name => $field) {
        $type = PARAM_RAW; // Puede que desees ajustar esto basado en el tipo de campo real
        $structure[$name] = new external_value($type, "Descripción de {$name}");
    }

    return new external_single_structure($structure);
}