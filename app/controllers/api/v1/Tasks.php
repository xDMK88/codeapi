<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($id="0")
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$uri_segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
		switch ($method) {
			case 'GET':
//получает список
				$this->load->model('task_model');
				if($id>0)
				{
					$item=$this->task_model->getSingleTask($id);
					$row = $item->row();
				//	$item->id = $id;

					$task_arr = array();
					if(isset($row)){
						// create array
						$task_arr = array(
							"id" =>  $row->id,
							"Название задачи" => $row->name_task,
							"Описание задачи" => $row->description_task,
							"Тип задачи" => $row->type_task,
							"Имя исполнителя" => $row->Performer,
							"Дата создания" => $row->date_create
						);

						http_response_code(200);
						echo json_encode($task_arr, JSON_UNESCAPED_UNICODE);
					}

					else{
						http_response_code(404);
						echo json_encode("Задача не найдены.", JSON_UNESCAPED_UNICODE);
					}
				}
				else
				{


					$stmt = $this->task_model->getTask();
					$itemCount = $stmt->num_rows();
					if($itemCount > 0){

						$taskArr = array();
						$taskArr["body"] = array();
						$taskArr["itemCount"] = $itemCount;

						foreach ($stmt->result_array() as $row)
						{
							extract($row);
							$data = array(
								"id" => $row['id'],
								"Название задачи" => $row['name_task'],
								"Описание задачи" => $row['description_task'],
								"Тип задачи" =>$row['type_task'],
								"Имя исполнителя" => $row['Performer'],
								"Дата создания" => $row['date_create']
							);
							array_push($taskArr["body"], $data);
						}






						echo json_encode($taskArr, JSON_UNESCAPED_UNICODE);
						http_response_code(200);
					}

					else{
						http_response_code(404);
						echo json_encode(
							array("message" => "No record found.")
						);
					}
				}

				break;
			case 'POST':
//создает новую

				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
				$this->load->model('task_model');

				$data = json_decode(file_get_contents("php://input"));

				$name_task = $data->name_task;
				$description_task = $data->description_task;
				$type_task = $data->type_task;
				$Performer = $data->Performer;
				$date_create = date('Y-m-d H:i:s');

				if($this->task_model->createTask($name_task,$description_task,$type_task,$Performer,$date_create)){
					// http_response_code(200);
					http_response_code(201);
					echo 'Задача создана успешно.';
				} else{
					http_response_code(404);
					echo 'Задача не была создана.';
				}
				break;
			case 'PATCH':
//изменяет данные
				$this->load->model('task_model');
				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


				$data = json_decode(file_get_contents("php://input"));

				$id = $data->id;

// employee values
				$name_task = $data->name_task;
				$description_task = $data->description_task;
				$type_task = $data->type_task;
				$Performer = $data->Performer;
				$date_create = date('Y-m-d H:i:s');

				if($this->task_model->updateTask($name_task,$description_task,$type_task,$Performer,$date_create,$id)){
					http_response_code(200);
					echo json_encode("Данные задачи обновлены.", JSON_UNESCAPED_UNICODE);
				} else{
					http_response_code(404);
					echo json_encode("Данные не возможно обновить", JSON_UNESCAPED_UNICODE);
				}
				break;
			case 'DELETE':
//удаляет данные
				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
				$this->load->model('task_model');

				$data = json_decode(file_get_contents("php://input"));

				$id = $data->id;

				if($this->task_model->deleteTask($id)){
					//    http_response_code(200);
					http_response_code(204);
					echo json_encode("Задача удалена.", JSON_UNESCAPED_UNICODE);
				} else{
					http_response_code(404);
					echo json_encode("Данные не возможно удалить", JSON_UNESCAPED_UNICODE);
				}
				break;
		}



	}
	public function task($id="0")
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$uri_segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
		switch ($method) {
			case 'GET':
//получает список
				$this->load->model('task_model');
				if($id>0)
				{
					$item=$this->task_model->getSingleTask($id);
					$row = $item->row();
					//	$item->id = $id;

					$task_arr = array();
					if(isset($row)){
						// create array
						$task_arr = array(
							"id" =>  $row->id,
							"Название задачи" => $row->name_task,
							"Описание задачи" => $row->description_task,
							"Тип задачи" => $row->type_task,
							"Имя исполнителя" => $row->Performer,
							"Дата создания" => $row->date_create
						);

						http_response_code(200);
						echo json_encode($task_arr, JSON_UNESCAPED_UNICODE);
					}

					else{
						http_response_code(404);
						echo json_encode("Задача не найдены.", JSON_UNESCAPED_UNICODE);
					}
				}
				else
				{


					$stmt = $this->task_model->getTask();
					$itemCount = $stmt->num_rows();
					if($itemCount > 0){

						$taskArr = array();
						$taskArr["body"] = array();
						$taskArr["itemCount"] = $itemCount;

						foreach ($stmt->result_array() as $row)
						{
							extract($row);
							$data = array(
								"id" => $row['id'],
								"Название задачи" => $row['name_task'],
								"Описание задачи" => $row['description_task'],
								"Тип задачи" =>$row['type_task'],
								"Имя исполнителя" => $row['Performer'],
								"Дата создания" => $row['date_create']
							);
							array_push($taskArr["body"], $data);
						}






						echo json_encode($taskArr, JSON_UNESCAPED_UNICODE);
						http_response_code(200);
					}

					else{
						http_response_code(404);
						echo json_encode(
							array("message" => "No record found.")
						);
					}
				}

				break;
			case 'POST':
//создает новую

				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
				$this->load->model('task_model');

				$data = json_decode(file_get_contents("php://input"));

				$name_task = $data->name_task;
				$description_task = $data->description_task;
				$type_task = $data->type_task;
				$Performer = $data->Performer;
				$date_create = date('Y-m-d H:i:s');

				if($this->task_model->createTask($name_task,$description_task,$type_task,$Performer,$date_create)){
					// http_response_code(200);
					http_response_code(201);
					echo 'Задача создана успешно.';
				} else{
					http_response_code(404);
					echo 'Задача не была создана.';
				}
				break;
			case 'PATCH':
//изменяет данные
				$this->load->model('task_model');
				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


				$data = json_decode(file_get_contents("php://input"));

				$id = $data->id;

// employee values
				$name_task = $data->name_task;
				$description_task = $data->description_task;
				$type_task = $data->type_task;
				$Performer = $data->Performer;
				$date_create = date('Y-m-d H:i:s');

				if($this->task_model->updateTask($name_task,$description_task,$type_task,$Performer,$date_create,$id)){
					http_response_code(200);
					echo json_encode("Данные задачи обновлены.", JSON_UNESCAPED_UNICODE);
				} else{
					http_response_code(404);
					echo json_encode("Данные не возможно обновить", JSON_UNESCAPED_UNICODE);
				}
				break;
			case 'DELETE':
//удаляет данные
				header("Access-Control-Allow-Methods: POST");
				header("Access-Control-Max-Age: 3600");
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
				$this->load->model('task_model');

				$data = json_decode(file_get_contents("php://input"));

				$id = $data->id;

				if($this->task_model->deleteTask($id)){
					//    http_response_code(200);
					http_response_code(204);
					echo json_encode("Задача удалена.", JSON_UNESCAPED_UNICODE);
				} else{
					http_response_code(404);
					echo json_encode("Данные не возможно удалить", JSON_UNESCAPED_UNICODE);
				}
				break;
		}
	}
}
