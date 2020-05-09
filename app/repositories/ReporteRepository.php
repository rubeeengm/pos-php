<?php
namespace app\repositories;

use core\{Log};
use app\helpers\{AnexGridHelper};
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use app\models\{ sqlViews\ReporteProducto };

class ReporteRepository {
    private $reporte_producto;

    public function __construct(){
        $this->reporte_producto = new ReporteProducto();
    }

    public function ventasPorMes() : string {
        $anexgrid = new AnexGridHelper;

        try {
            $sql = '
                SELECT
                    YEAR(fecha) anio,
                    MONTH(fecha) mes,
                    (
                        SELECT
                            SUM(costo * cantidad)
                        FROM 
                            comprobante_detalle
                        WHERE 
                            comprobante_id = c.id
                    ) costo,
                    SUM(total) total
                FROM
                    comprobante c
                WHERE 
                    c.anulado = 0
                GROUP BY
                    c.id, anio, mes
            ';

            $result = DB::select("
                SELECT
                    anio,
                    mes,
                    SUM(costo) costo,
                    SUM(total) total
                FROM
                    ($sql) alias
                GROUP BY 
                    anio, mes
                ORDER BY 
                    anio desc, mes desc
                LIMIT $anexgrid->pagina, $anexgrid->limite
            ");            

            $total = DB::select("
                SELECT
                    COUNT(*) t
                FROM
                    ($sql) alias
            ");

            return $anexgrid->responde(
                $result,
                $total[0]->t
             );
        } catch (Exception $e) {
            Log::error(ReporteRepository::class, $e->getMessage());
        }

        return "";
    }

    public function productosPorMes($y, $m) : string {
        $anexgrid = new AnexGridHelper;

        try {
            $result = $this->reporte_producto->orderBy(
                $anexgrid->columna,
                $anexgrid->columna_orden
            )->where('anio', $y)
                ->where('mes', $m)
                ->skip($anexgrid->pagina)
                ->take($anexgrid->limite)
                ->get();

            return $anexgrid->responde(
                $result,
                $this->reporte_producto->count()
             );
        } catch (Exception $e) {
            Log::error(ReporteRepository::class, $e->getMessage());
        }

        return "";
    }
}