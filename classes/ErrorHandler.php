<?php

namespace local_sgevea;

use Exception;

class ErrorHandler
{
    public static function logException(Exception $exception)
    {
        // Registra la excepción en un archivo de registro o base de datos.
    }

    public static function showError($message = null, $title = null)
    {
        global $OUTPUT;
        $titleShow = get_string('pluginname', 'local_sgevea') . ' ' . $title;
        $messageShow = (is_siteadmin()) ? $message : get_string('error', 'local_sgevea');
        $cssShow = (is_siteadmin()) ? 'danger' : 'light';

        //__self::throwException($message);

        // Muestra mensajes de error al usuario.
        // Puedes integrar Mustache aquí para la presentación de mensajes de error.

        return $OUTPUT->render_from_template('local_sgevea/error', array(
            'alertmsg' => $messageShow,
            'alerttit' => $titleShow,
            'alertcss' => $cssShow
        ));
    }
    public static function throwException($messageShow)
    {
        throw new Exception($messageShow);
    }
}
