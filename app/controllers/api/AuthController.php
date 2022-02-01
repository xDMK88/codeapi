<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';

use chriskacerguis\RestServer\RestController;

class AuthController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
	}
	public function ValidatePhone_post()
	{
		$users = new AuthModel;

		$user_id = random_int(1,10000);
		$account_id = random_int(1,10000);


		$phone = $this->input->get('phone');
		$code = $this->input->get('code');
		$count_exist = $users->get_count_auth_phone($phone);
		if($count_exist==1)
		{
			$phone_array = $users->get_auth_phone($phone);
			if($phone_array->num_rows() > 0)
			{

				$this->response($phone_array->result(), 200);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'Не пройдена'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
		else
		{
			if(isset($phone) && $phone!="" && !isset($code))
			{
				$code_new = random_int(100000, 999999);
				$this->session->set_userdata('code', $code_new);
				$data = [
					'phone'=>$phone,
					'code'=>$code_new
				];

				$result = $users->insert_phone_codes($data);
				if($result > 0)
				{
					$this->response([
						'status' => true,
						'message' => '1 этап аунтификации пройден. Код: '.$code_new
					], RestController::HTTP_OK);
				}
				else
				{
					$this->response([
						'status' => false,
						'message' => 'Не пройдена'
					], RestController::HTTP_BAD_REQUEST);
				}

			} else if(isset($phone) && $phone!="" && isset($code) && $code!="") {
				$code_expired_count = $users->get_count_code_expired($phone,$code);
				//echo $code_expired_count;
				if($code_expired_count==0)
				{
					$code_new = random_int(100000, 999999);
					$this->session->set_userdata('code', $code_new);
					$data = [
						'phone'=>$phone,
						'code'=>$code_new
					];

					$result = $users->insert_phone_codes($data);
					if($result > 0)
					{
						$this->response([
							'status' => false,
							'message' => 'Ошибка время истекло. Код: '.$code_new
						], RestController::HTTP_UNAUTHORIZED);
					}
					else
					{
						$this->response([
							'status' => false,
							'message' => 'Не пройдена'
						], RestController::HTTP_BAD_REQUEST);
					}

				}
				else {


					$count_code = $users->get_count_code_phone($code, $phone);
					//	echo $count_code;
					if ($count_code == 1) {
						$count_us = $users->get_count_auth_phone($phone);

						$this->load->library('encryption');
						if ($count_us == 0) {
							$access_token = bin2hex($this->encryption->create_key(16));
							$refresh_token = bin2hex($this->encryption->create_key(16));
							$data = [
								'id_user' => $user_id,
								'phone' => $phone
							];
							$data_account = [
								'id_account' => $account_id,
								'id_user' => $user_id,
								'default_s' => 1
							];
							$data_tokens = [
								'id_account' => $account_id,
								'access_token' => $access_token,
								'refresh_token' => $refresh_token
							];


							$result_auth_phone = $users->insert_auth_phone($data);
							$result_auth_accounts = $users->insert_auth_accounts($data_account);
							$result_auth_tones = $users->insert_auth_tokens($data_tokens);
							if ($result_auth_phone > 0 && $result_auth_accounts > 0 && $result_auth_tones > 0) {
								$phone_array = $users->get_auth_phone($phone);
							//	$this->response([
						//			'status' => true,
								//	'message' => 'Аунтификация пройдена. Данные добавлены.'
							//	], RestController::HTTP_OK);
								$this->response($phone_array->result(), RestController::HTTP_OK);
							} else {
								$this->response([
									'status' => false,
									'message' => 'Не пройдена'
								], RestController::HTTP_BAD_REQUEST);
							}


						} else {
							$phone_array = $users->get_auth_phone($phone);
							if ($phone_array->num_rows() > 0) {

								$this->response($phone_array->result(), 200);
							} else {
								$this->response([
									'status' => false,
									'message' => 'Не пройдена'
								], RestController::HTTP_BAD_REQUEST);
							}
						}
					} else {
						$phone_array = $users->get_auth_phone($phone);
						if ($phone_array->num_rows() > 0) {

							$this->response($phone_array->result(), 200);
						} else {
							$this->response([
								'status' => false,
								'message' => 'Не пройдена'
							], RestController::HTTP_BAD_REQUEST);
						}
					}
				}
			}
			else
			{

			}
		}




	}
}
