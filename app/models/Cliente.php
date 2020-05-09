<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

//Model es parte de laravel
class Cliente extends Model{
	//tabla a la que va a apuntar
    protected $table = 'cliente';
}