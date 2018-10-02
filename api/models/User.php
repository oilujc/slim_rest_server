<?php 
namespace api\models;
use Illuminate\Database\Eloquent\Model;

//Modelo del usuario
class User extends Model {
	//Establece la tabla del modelo
	protected $table = 'user';
	//Datos rellenables de la tabla
	protected $fillable = [
		'email',
		'password',
		'firstname',
		'lastname',
		'is_active',
		'is_admin',
		'image',
	];
}
 ?>