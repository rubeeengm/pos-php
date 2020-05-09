<?php
namespace app\repositories;

use core\{Auth, Log};
use app\helpers\{ResponseHelper,AnexGridHelper};
use app\models\{Cliente};
use Exception;

class ClienteRepository {
    private $cliente;

    public function __construct(){
        $this->cliente = new Cliente;
    }

    public function listar() : string {
        $anexgrid = new AnexGridHelper;

        try {
            $result = $this->cliente->orderBy(
                $anexgrid->columna,         //columna en la que hacemos click
                $anexgrid->columna_orden    //ascendente o descendente
            )->skip($anexgrid->pagina) //pagina de la cual se esta usando los resultados
             ->take($anexgrid->limite) //hasta lo que indica el limite de la página
             ->get();                  //capturar los resultados

            return $anexgrid->responde(
                $result,                //todos los registros
                $this->cliente->count() //conteo
            );
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return "";
    }
    //responsehelper, retorna informacion adicional, está ligado a la lógica
    //del formulario que implementa ajax
    public function guardar(Cliente $model) : ResponseHelper {
        $rh = new ResponseHelper;

        try {
            $this->cliente->id = $model->id;
            $this->cliente->nombre = $model->nombre;
            $this->cliente->direccion = $model->direccion;

            if(!empty($model->id)){
                /*
                 * Al setear este valor a True hacemos que Eloquent lo considere como un registro existente,
                 * por lo tanto hará un update
                 */
                $this->cliente->exists = true;
            }
            
            $this->cliente->save();
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function obtener($id) : Cliente {
        $cliente = new Cliente;

        try {
            //busca un registro y obtiene un solo resultado
            $cliente = $this->cliente->find($id);
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $cliente;
    }

    public function eliminar(int $id) : ResponseHelper {
        $rh = new ResponseHelper;

        try {
            $this->cliente->destroy($id);
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function buscar(string $q) : array{
        $result = [];

        try {
            $result = $this->cliente
                           ->where('nombre','like',"%$q%")
                           ->orderBy('nombre')
                           ->get()
                           ->toArray();
        } catch (Exception $e) {
            Log::error(ClienteRepository::class, $e->getMessage());
        }

        return $result;
    }    
}