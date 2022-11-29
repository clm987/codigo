<?php
require_once './Enums/BasicEnum.php';
abstract class EEusuario extends BasicEnum
{
    const ACTIVO = 1;
    const SUSPENDIDO = 2;
    const ELIMINADO = 3;

    public static function getName($value)
    {
        switch ($value) {
            case 1:
                $name = 'ACTIVO';
                break;
            case 2:
                $name = 'SUSPENDIDO';
                break;
            case 3:
                $name = 'ELIMINADO';
                break;
            default:
                $name = 'valor erroneo';
                break;
        }
        return $name;
    }
}
