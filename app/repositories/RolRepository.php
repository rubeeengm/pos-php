<?php
namespace app\repositories;

use core\{Log};
use app\models\{Rol};
use Exception;
use Illuminate\Database\Eloquent\Collection;

class RolRepository {
    private $rol;

    public function __construct(){
        $this->rol = new Rol;
    }

    public function listar() : Collection {
        $result = [];

        try {
            $result = $this->rol
                           ->orderBy('nombre')
                           ->get();
        } catch (Exception $e) {
            Log::error(UsuarioRepository::class, $e->getMessage());
        }

        return $result;
    }
}