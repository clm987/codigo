<?php
abstract class EEmesa extends BasicEnum
{
    const CON_CLIENTE_ESPERANDO = 1;
    const CON_CLIENTE_COMIENDO = 2;
    const CON_CLIENTE_PAGANDO = 3;
    const CERRADA = 4;

    public static function getName($value)
    {
        switch ($value) {
            case 1:
                $name = 'CON_CLIENTE_ESPERANDO';
                break;
            case 2:
                $name = 'CON_CLIENTE_COMIENDO';
                break;
            case 3:
                $name = 'CON_CLIENTE_PAGANDO';
                break;
            case 4:
                $name = 'CERRADA';
                break;
            default:
                $name = 'valor erroneo';
                break;
        }
        return $name;
    }
}
