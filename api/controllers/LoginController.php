<?php 
namespace api\controllers;

use Respect\Validation\Validator as V;

use api\models\User;
use api\controllers\Controller;
use api\lib\Auth;
use api\lib\Response;

//Controlador de autenticacion
class LoginController extends Controller{
	//retorna a la vista del signup
	public function login($request, $response){
		$validation = $this->validateLoginRequest($request);
        
        if (!$this->validator->isValid()) {
            return $response->withJson(['errors' => $validation->getErrors()], 422);
        }

        $user = $this->attempt($request->getParam('email'), $request->getParam('password'));

        if (is_object($user)) {
        	if (!$user->is_active) {
        		return $response->withJson('Your account is not active', 422);
        	}

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

        return $response->withJson('Invalid Email or Password', 422);
  

	}

	protected function validateLoginRequest($request) {
        return $this->validator->validate($request,
            [
                'email'    => v::noWhitespace()->notEmpty()->email(),
                'password' => v::noWhitespace()->notEmpty(),
            ]);
    }

    protected function attempt($email, $password) {
        if(!$user = User::where('email', $email)->first()) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
 ?>