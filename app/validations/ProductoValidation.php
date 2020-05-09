<?php
namespace app\validations;
use Respect\Validation\Validator as v;
use app\helpers\ResponseHelper;
use app\models\Usuario;

class ProductoValidation {
    public static function validate (array $model) {
        try{
            $v = v::key('nombre', v::stringType()->notEmpty())
              ->key('marca', v::stringType()->notEmpty())
              ->key('costo', v::stringType()->notEmpty())
              ->key('precio', v::stringType()->notEmpty());

            $v->assert($model);
        } catch (\Exception $e) {
            $rh = new ResponseHelper();
            $rh->setResponse(false, null);
            $rh->validations = $e->findMessages([
                'nombre' => '{{name}} es requerido',
                'marca' => '{{name}} es requerido',
                'costo' => '{{name}} es requerido',
                'precio' => '{{name}} es requerido',
            ]);

            exit(json_encode($rh));
        }
    }
}