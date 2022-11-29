<?php
abstract class EEPedido extends BasicEnum
{
    const PENDIENTE = 1;
    const EN_PREPARACION = 2;
    const LISTO_PARA_SERVIR = 3;
    const CANCELADO = 4;

    public static function getName($value)
    {
        switch ($value) {
            case 1:
                $name = 'PENDIENTE';
                break;
            case 2:
                $name = 'EN_PREPARACION';
                break;
            case 3:
                $name = 'LISTO_PARA_SERVIR';
                break;
            case 4:
                $name = 'CANCELADO';
                break;
            default:
                $name = 'valor erroneo';
                break;
        }
        return $name;
    }
}
