<?php
abstract class EEmesa extends BasicEnum
{
    const CON_CLIENTE_ESPERANDO = 1;
    const CON_CLIENTE_COMIENDO = 2;
    const CON_CLIENTE_PAGANDO = 3;
    const CERRADA = 4;
}
