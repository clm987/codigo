<?php
define("RUTA_BASECSV", "C:/Users/usuario/Desktop/");
require_once './models/Encuesta.php';

class ManejadorCSV
{
    public static function GuardarArchivoCSV($array_de_encuestas, $nomre_archivo)
    {
        $array_de_string = [];
        foreach ($array_de_encuestas as $encuesta) {
            array_push($array_de_string, $encuesta->EncuestaToCsv());
        }
        return file_put_contents(RUTA_BASECSV . $nomre_archivo, $array_de_string);
    }

    public static function LeerArchivoCSV($ruta_archivo)
    {
        $_archivo = null;
        $_arrayDatos = "";
        $arrayEncuestas = [];
        if (file_exists($ruta_archivo)) {
            $_archivo = fopen($ruta_archivo, "r") or die("No se pudo abrir el archivo!");
            if ($_archivo == null) {
                return false;
            } else {
                while (($_arrayDatos = (fgetcsv($_archivo))) !== false) {
                    $id = $_arrayDatos[0];
                    $puntuacion_mesa = $_arrayDatos[1];
                    $puntuacion_restaurante = $_arrayDatos[2];
                    $puntuacion_mozo = $_arrayDatos[3];
                    $puntuacion_cocinero = $_arrayDatos[4];
                    $descripcion_experiencia = $_arrayDatos[5];
                    $promedio_puntuacion = $_arrayDatos[6];
                    $fecha_alta = $_arrayDatos[7];
                    $_auxEncuesta = new Encuesta();
                    $_auxEncuesta->puntuacion_mesa = $puntuacion_mesa;
                    $_auxEncuesta->puntuacion_restaurante = $puntuacion_restaurante;
                    $_auxEncuesta->puntuacion_mozo = $puntuacion_mozo;
                    $_auxEncuesta->puntuacion_cocinero = $puntuacion_cocinero;
                    $_auxEncuesta->descripcion_experiencia = $descripcion_experiencia;
                    $_auxEncuesta->promedio_puntuacion = $promedio_puntuacion;
                    $_auxEncuesta->crearEncuesta();
                }
                fclose($_archivo);
                return true;
            }
        } else {
            return false;
        }
    }
}
