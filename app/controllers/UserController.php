<?php
use Phalcon\Http\Response;
use Phalcon\Http\Request;

class UserController Extends ControllerBase
{


    public function apiTestAction(){	
		
		$response  =  new Response();
		$response->setContentType("application/json","UTF-8");
		
		
		$response->setStatusCode(200,"ok");
		$response->setJsonContent([
				"test"     => "Working",
				"success" => true

				
		]);
		
		return $response;
		
		
		
    }
    
    public function GetUsersAction(){

        $response = new Response();
        $response->setContentType("application/json","UTF-8");

        $users = User::find();

        $response->setJsonContent($users);

        return $response;
    }

    
    public function createAction(){

        $data = $this->request->getJsonRawbody();

        $response = new Response();
        $response->setContentType('application/json', 'UTF-8');

        $data_response = [
            'success' => false,
            "message" => ""
        ];
        

        $user_email_exist = User::findFirst([
            'conditions' => 'email ="'.$data->email.'"']);

        if(!$user_email_exist)
        {
            
            $user = new User();

            $user->name = $data->name;
            $user->age = $data->age;
            $user->email = $data->email; 

            $user->save();

            if(!$user->save())
            {
                $m = "";
                foreach($user->getMessages() as $message)
                {
                    $m = $m .$message;
                }
                $data_response['message'] = $m; 
            }else{
                    $data_response['success'] = true;
                    $data_response['message'] = "Guardado correctamente";
            }
        }else{

            $data_response['message'] = "Usuario ya existe";
        }

        $response->setStatusCode(200,"ok");
		$response->setJsonContent($data_response);
		
		return $response;

    }

    public function getUsers()
    {
        $response  =  new Response();
        $response->setContentType("application/json","UTF-8");
        
        $user_exist = User::find([
            'conditions' => 'id' .$id.''
        ]);

        $data_send = [];
        foreach($user_exist as $ue)
        {
            $data = [
                'id' => $ue->id,
                'name' => $ue->name,
                'age' => $ue->age,
                'email' => $ue->email
            ];
            array_push($data_send, $data);
        }
        $response->setStatusCode(200,"ok");
		$response->setJsonContent([
				"data"     => $data_send
		]);
		
		return $response;
    }
}
?>