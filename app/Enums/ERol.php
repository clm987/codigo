<?php
abstract class ERol extends BasicEnum
{
    const BARTENDER = 1;
    const CERVECERO = 2;
    const COCINERO = 3;
    const MOZO = 4;
    const SOCIO = 5;

    public static function getName($value)
    {
        switch ($value) {
            case 1:
                $name = 'BARTENDER';
                break;
            case 2:
                $name = 'CERVECERO';
                break;
            case 3:
                $name = 'COCINERO';
                break;
            case 4:
                $name = 'MOZO';
                break;
            case 5:
                $name = 'SOCIO';
                break;
            default:
                $name = 'valor erroneo';
                break;
        }
        return $name;
    }
}
