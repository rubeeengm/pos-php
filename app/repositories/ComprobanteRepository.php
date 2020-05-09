<?php
namespace app\repositories;

use core\{Auth, Log};
use app\helpers\{ResponseHelper,AnexGridHelper};
use app\models\{Comprobante};
use Exception;
//use DB;

class ComprobanteRepository {
    private $comprobante;

    public function __construct(){
        $this->comprobante = new Comprobante;
    }

    public function listar() : string {
        $anexgrid = new AnexGridHelper;

        try {
            $result = $this->comprobante->orderBy(
                $anexgrid->columna,         //columna en la que hacemos click
                $anexgrid->columna_orden    //ascendente o descendente
            )->skip($anexgrid->pagina) //pagina de la cual se esta usando los resultados
             ->take($anexgrid->limite) //hasta lo que indica el limite de la página
             ->get();                  //capturar los resultados

             foreach ($result as $r) {
                 $r->cliente = $r->cliente;
             }

            return $anexgrid->responde(
                $result,                //todos los registros
                $this->comprobante->count() //conteo
            );
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }

        return "";
    }

    public function obtener($id) : Comprobante {
        $model = new Comprobante();

        try {
            $model = $this->comprobante->find($id);
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }

        return $model;
    }

    public function anular(int $id) : ResponseHelper {
        $rh = new ResponseHelper;

        try {
            $this->comprobante->id = $id;
            $this->comprobante->anulado = 1;
            $this->comprobante->exists = true;

            $this->comprobante->save();
            $rh->setResponse(true);
        } catch (Exception $e) {
            Log::error(ComprobanteRepository::class, $e->getMessage());
        }

        return $rh;
    }

    public function generar(Comprobante $model, array $detalle) : ResponseHelper {
        $rh = new ResponseHelper;

        try {
            //DB::beginTransaction();

            $model->sub_total = 0;
            $model->iva = 0;
            $model->total = 0;
            $model->fecha = date('Y-m-d h:m:s');
            $model->anulado = 0;

            foreach ($detalle as $k => $d) {
                $d->orden = $k;
                $d->total = $d->cantidad * $d->precio;
                $model->total += $d->total;
            }

            //subtotal
            $model->sub_total = $model->total / 1.18;
            $model->iva = $model->total - $model->sub_total;

            //generar el comprobante
            $model->save();

            //guarda el detalle
            $model->detalle()->saveMany($detalle);

            //DB::commit();

            $rh->setResponse(true);
        } catch (Exception $e) {
            //DB::rollBack();
            Log::error(ProductoRepository::class, $e->getMessage());
        }

        return $rh;
    }
}