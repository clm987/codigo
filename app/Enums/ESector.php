<?php
abstract class ESector extends BasicEnum
{
    const BARRA = 1;
    const CERVECERIA = 2;
    const COCINA = 3;
    const CANDYBAR = 4;

    public static function getName($value)
    {
        switch ($value) {
            case 1:
                $name = 'BARRA';
                break;
            case 2:
                $name = 'CERVECERIA';
                break;
            case 3:
                $name = 'COCINA';
                break;
            case 4:
                $name = 'CANDYBAR';
                break;
            default:
                $name = 'valor erroneo';
                break;
        }
        return $name;
    }
}
