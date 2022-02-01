<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/SMSAPIController.php';
class AuthModel extends CI_Model
{
	public function get_count_auth_phone($phone)
	{
		$query = $this->db->get_where('users', array('phone'=>$phone));
		return $query->num_rows();
	}
	public function get_count_code_phone($code,$phone)
	{
		$query = $this->db->get_where('usersphonecodes', array('code'=>$code, 'phone'=>$phone));
		return $query->num_rows();
	}
	public function get_auth_phone($phone)
	{
		$this->db->select('u.id_user as id_user, u.phone as phone, a.name as name, a.default_s as default');
		$this->db->from('users u');
		$this->db->join('accounts a', 'a.id_user = u.id_user', 'left');
		$this->db->where('u.phone', $phone);
		$query = $this->db->get();

		//$query = $this->db->get_where('users', array('phone'=>$phone));
		return $query;
	}
	public function get_count_code_expired($phone,$code)
	{
		$date_expired = date('i');

		$query = $this->db->query("SELECT * FROM usersphonecodes WHERE STR_TO_DATE(date_create, '%Y-%m-%d %H:%i:%s') > DATE_SUB(NOW(), INTERVAL 5 MINUTE) and phone='".$phone."' and code='".$code."'");//get_where('usersphonecodes', array('code'=>$code, 'phone'=>$phone));
	//	$row = $query->row();
	//	..$newDate = date("i", strtotime($row['date_create']));
		//$new_minutes = $date_expired+(60*5);
		//$diff_date = $newDate-$date_expired;
		//if($diff_date==5)
		//{

		//}
		return $query->num_rows();
	}

    public function insert_auth_phone($data)
    {
		return $this->db->insert('users', $data);
    }
    public function insert_auth_accounts($data)
	{
		return $this->db->insert('accounts',$data);
	}
	public function insert_auth_tokens($data)
	{
		return $this->db->insert("userstokens",$data);
	}




    public function insert_phone_codes($data)
	{
		return $this->db->insert('usersphonecodes', $data);
	}
	/*public function ajaxRequestPostLogin(Request $request)
	{

		$login = request()->input('username');
		$code = random_int(100000, 999999);
		Request()->session()->put('code', $code);

		SMSAPIController::send_sms($login ,"Ваш код: ".$code,1);



		$headers = "From: info@cosmocrm.ru\r\n";
		$headers .= "Reply-To: ". strip_tags($login) . "\r\n";
		//   $headers .= "CC: susan@example.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$message = '<p><strong>Ваш код для входа: </strong> '.$code.' </p>';
		mail($login,"Код для входа",$message,$headers);





		return Request()->session()->get('code');
	}*/
}
/* End of file AuthModel.php */
/* Location: ./application/controllers/AuthModel.php */
