<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

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

	// Table
	private $db_table = "task";

	// Columns
	public $id;
	public $name_task;
	public $description_task;
	public $type_task;
	public $Performer;
	public $date_create;

	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function getTask(){
		$stmt = $this->db->query("SELECT id, name_task, description_task, type_task, Performer, date_create FROM task");
		$stmt->result_array();
		return $stmt;
	}
	public function createTask(){
		$name_task=htmlspecialchars(strip_tags($this->name_task));
		$description_task=htmlspecialchars(strip_tags($this->description_task));
		$type_task=htmlspecialchars(strip_tags($this->type_task));
		$Performer=htmlspecialchars(strip_tags($this->Performer));
		$date_create=htmlspecialchars(strip_tags($this->date_create));
		$sqlQuery = "INSERT INTO". $this->db_table ." SET
                        name_task = $name_task, 
                        description_task = $description_task, 
                        type_task = $type_task, 
                        Performer = $Performer, 
                        date_create = $date_create";

		$stmt = $this->db->query($sqlQuery);

		// sanitize





		if($stmt){
			return true;
		}
		return false;
	}
	// READ single
	public function getSingleTask(){
		$id = $this->id;
		$sqlQuery = "SELECT id, name_task, description_task, type_task, Performer, date_create FROM task WHERE id = '$id' LIMIT 0,1";

		$stmt = $this->db->query($sqlQuery);

		$dataRow = $stmt->row();

		//$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->name_task = $dataRow['name_task'];
		$this->description_task = $dataRow['description_task'];
		$this->type_task = $dataRow['type_task'];
		$this->Performer = $dataRow['Performer'];
		$this->date_create = $dataRow['date_create'];
	}
	// UPDATE
	public function updateTask(){

		$name_task=htmlspecialchars(strip_tags($this->name_task));
		$description_task=htmlspecialchars(strip_tags($this->description_task));
		$type_task=htmlspecialchars(strip_tags($this->type_task));
		$Performer=htmlspecialchars(strip_tags($this->Performer));
		$date_create=htmlspecialchars(strip_tags($this->date_create));
		$id=htmlspecialchars(strip_tags($this->id));

		$sqlQuery = "UPDATE task SET name_task = '$name_task', description_task = '$description_task', type_task = '$type_task', Performer = '$Performer', date_create = '$date_create' WHERE id = '$id'";

		$stmt = $this->db->query($sqlQuery);

		if($stmt){
			return true;
		}
		return false;
	}

	// DELETE
	function deleteTask(){
		$id=htmlspecialchars(strip_tags($this->id));
		$sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = '$id'";
		$stmt = $this->db->query($sqlQuery);
		if($stmt){
			return true;
		}
		return false;
	}
}
