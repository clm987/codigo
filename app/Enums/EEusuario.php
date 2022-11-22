<?php
require_once './Enums/BasicEnum.php';
abstract class EEusuario extends BasicEnum
{
    const ACTIVO = 1;
    const SUSPENDIDO = 2;
    const ELIMINADO = 3;
}
