<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

//Model es parte de laravel
class ComprobanteDetalle extends Model{
	//tabla a la que va a apuntar
    protected $table = 'comprobante_detalle';

    public function producto(){
    	return $this->belongsTo('app\models\Producto');
    }
}