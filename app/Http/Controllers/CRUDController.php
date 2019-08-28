<?php

namespace App\Http\Controllers;

use App\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Break_;

class CRUDController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function medicine(Request $r)
    {
        //El objeto CRUDHelper reutilizar el código para cada tipo de tabla que se quiera utilizar.
        $helper = new CRUDHelper("App\\Medicine");

        //El objeto procesa los parámetros.
        $result = $helper->proccess($r);

        //Se devuelve en formato JSON los resultados.
        return response()->json($result);
    }
    public function user(Request $r)
    {
        //Lo mismo de arriba pero con diferente tabla.
        $helper = new CRUDHelper("App\\User");
        $result = $helper->proccess($r);
        return response()->json($result);
    }
}


/**
 * UNA EXTRAÑA FORMA DE REUTILIZAR EL CÓDIGO DEL PATRÓN CRUD
 *
 * Class SCRUMHelper
 * @package App\Http\Controllers
 */
class CRUDHelper {
    protected $table = "";
    public function __construct($table)
    {
        $this->table = $table;
    }
    public function proccess(Request $r, $verifiy = true){

        //Verificar si ha iniciado sesión.
        if((Auth::guest()) && $verifiy){
            return [];
        }

        $method = strtoupper($r->getMethod());
        $id = $r->get("id", 0);
        $data = $r->get("data", []);

        //Nombre de la tabla seleccionada (especificada en el constructor).
        $table = $this->table;

        //Arreglo que contienen datos por defectos que se retornaran en formato JSON.
        $result = array("result" => 0, "method" => $method, "data" => [], "errors" => []);

        switch ($method){

                //Crea un nuevo elemento o lo actualiza.
            case "PUT":
                $all =  $r->all();
                $result["id"] = $id;
                $result["received"] = $data;
                $result["all"] = $all;

                //Se obtiene la información sobre validación, desde la función de la tabla.
                $validate = $table::getValidation();

                //En caso de que se vaya a actualizar los datos, se cambia la regla de validación única.
                if($id > 0){
                    $validate["code"] .= ',id,' . $id;
                }

                //Se crea el validador de parámetros.
                $validator = Validator::make($data, $validate);

                //Se comprueba si el validador arroja errores, si es así devuelve los errores y no realiza cambios.
                if($validator->fails()){
                    $result["errors"] = $validator->messages();
                    break;
                }

                //Se busca el objeto a modificar: update.
                $obj = null;
                if($id > 0){
                    $obj = $table::find($id);
                }

                //Si el objeto no existe, se crea.
                $med = $obj == null ? new $table : $obj;

                //Obtener los campos de la tabla que se pueden llenar.
                $fillable = $med->getFillable();

                //Se asignan los parámetros al modelo de la tabla.
                foreach ($data as $k => $v){
                    //Se omiten parámetros que no sean de la tabla.
                    if(in_array($k, $fillable)){
                        $med->$k = $v;
                    }
                }
                //Se guardan los cambios en la base de datos.
                $med->save();
                break;

                //Devuelve una lista con todos los elementos
            case "GET":
                $result["data"] = $table::all()->toArray();
                break;

                //Elimina un elemento en específico
            case "DELETE":
                $table::destroy($id);
                $result["data"] = $id;
                $result["result"] = 1;
                break;

                //No se usa?
            case "POST":
                break;
        }
        return $result;
    }
}
