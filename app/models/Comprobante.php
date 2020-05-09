<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

//Model es parte de laravel
class Comprobante extends Model{
	//tabla a la que va a apuntar
    protected $table = 'comprobante';

    public function detalle(){
    	return $this->hasMany('app\models\ComprobanteDetalle');
    }

    public function cliente(){
        return $this->belongsTo('app\models\Cliente');
    }

    public function idForView() : string{
    	if (empty($this->id)) return '';
    	else return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getIdForViewAttribute() : string{
    	if (empty($this->id)) return '';
    	else return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}