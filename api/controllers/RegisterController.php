<?php 
namespace api\controllers;

use Respect\Validation\Validator as V;

use api\models\User;
use api\models\UserInfo;

use api\controllers\Controller;

use api\lib\Auth;
use api\lib\Response;

//Controlador de autenticacion
class RegisterController extends Controller{
	//retorna a la vista del signup
	public function signup($request, $response){
		$validation = $this->validateRegisterRequest($request);
        
        if (!$this->validator->isValid()) {
            return $response->withJson(['errors' => $validation->getErrors()], 422);
        }

        $user = User::create([
	        		'email' => $request->getParam('email'),
	        		'password' => password_hash($request->getParam('password') , PASSWORD_DEFAULT),
	        		'firstname' => strtolower($request->getParam('firstname')),
	        		'lastname' => strtolower($request->getParam('lastname')),
	        		'is_active' => True,
	        		'is_admin' => false,
	        		'image' => null,
	        	]);


        $token = Auth::SignIn([
        	'id' => $user->iduser,
        	'firstname' => $user->fisrtname,
        	'lastname' => $user->lastname,
        	'email' => $user->email,
        	'is_admin' => $user->is_admin,
        	'image' => $user->image,
        ]);

        return $response->withJson(['result'=> $token]);
	}

	protected function validateRegisterRequest($request) {
        return $this->validator->validate($request,
            [
                'email'    => v::noWhitespace()->notEmpty()->email()->existsInTable($this->db->table('user'), 'email'),
                'password' => v::noWhitespace()->notEmpty(),
                'firstname' => v::noWhitespace()->notEmpty(),
                'lastname' => v::noWhitespace()->notEmpty(),
            ]);
    }
}
 ?>