<?php
require_once './Utils/ManejadorCSV.php';

class CsvController
{

    public function GenerarCSV($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre_archivo = $parametros['nombre_archivo'];
        $nombre_con_extension = $nombre_archivo . ".csv";
        $auxListaObjetos = Encuesta::obtenerTodos();
        if (!ManejadorCSV::GuardarArchivoCSV($auxListaObjetos, $nombre_con_extension)) {
            $payload = json_encode(array("mensaje" => "Archivo Guardado con exito."));
            $response->getBody()->write($payload);
        }

        $payload = json_encode(array("mensaje" => "Ocurrio un error."));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function LeerCsv($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $ruta_archivo = $parametros['ruta_archivo'];
        if (ManejadorCSV::LeerArchivoCSV($ruta_archivo)) {
            $payload = json_encode(array("mensaje" => "Encuestas cargadas con exito."));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
        $payload = json_encode(array("mensaje" => "ocurrio un error al cargar los datos."));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
