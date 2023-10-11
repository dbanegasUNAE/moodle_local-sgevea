<?php

namespace local_sgevea;

use Exception;

class ExceptionView extends \RuntimeException
{
    protected $modifiedMessage;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct('', $code, $previous);
        $this->modifyErrorMessage($message);
    }

    public function getModifiedMessage()
    {
        return $this->modifiedMessage;
    }

    protected function modifyErrorMessage($message)
    {
        global $USER;

        // Verifica si el usuario actual es un administrador.
        $isAdministrator = is_siteadmin();

        // Modifica el mensaje de acuerdo al tipo de usuario.
        if ($isAdministrator) {
            // Si es un administrador, conserva el mensaje original.
            $this->modifiedMessage = $message;
        } else {
            // Si no es un administrador, muestra un mensaje genérico.
            $this->modifiedMessage = "Lo sentimos, ha ocurrido un error. Por favor, contacte al administrador del sitio.";
        }
    }
}
