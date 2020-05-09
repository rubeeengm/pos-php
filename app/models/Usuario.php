<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model{
    protected $table = 'usuario';
    protected $hidden = ['password'];

    public function rol()
    {
        return $this->belongsTo('app\models\Rol');
    }
}