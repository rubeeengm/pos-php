<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

//Model es parte de laravel
class Producto extends Model{
	//tabla a la que va a apuntar
    protected $table = 'producto';

    public function getMargenAttribute() : float{
    	$ingreso = $this->precio - $this->costo;

    	return round($ingreso / $this->costo * 100, 0);
    }

    public function getTieneFotoAttribute() : string{
    	return empty($this->foto) ? "No" : "Si";
    }
}