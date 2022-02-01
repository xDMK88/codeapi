<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentModel extends CI_Model
{

	public function get_student()
	{
		$query = $this->db->get("students");
		return $query->result();
	}
	public function get_student_id($id)
	{
		$query = $this->db->get_where("students",array('id'=>$id));

		return $query->result();
	}
	public function insert_student($data)
	{
		return $this->db->insert('students', $data);
	}

	public function edit_student($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('students');
		return $query->row();
	}

	public function update_student($id,$data)
	{
		$this->db->where('id', $id);
		return $this->db->update('students', $data);
	}

	public function delete_student($id)
	{
		return $this->db->delete('students', ['id' => $id]);
	}

}

?>
