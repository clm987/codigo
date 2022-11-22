<?php
abstract class EEPedido extends BasicEnum
{
    const PENDIENTE = 1;
    const EN_PREPARACION = 2;
    const LISTO_PARA_SERVIR = 3;
    const CANCELADO = 4;
}
