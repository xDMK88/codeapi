<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Task_model extends CI_Model {

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


	public function __construct()
	{
		parent::__construct();
	}

	public function getTask(){
		$stmt = $this->db->query("SELECT id, name_task, description_task, type_task, Performer, date_create FROM task");

		return $stmt;
	}
	// READ single
	public function getSingleTask($id){
		$data = array();


		$query = $this->db->query("SELECT id, name_task, description_task, type_task, Performer, date_create FROM task WHERE id = '".$id."' LIMIT 0,1");



		//$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

		/*$this->name_task = $dataRow['name_task'];
		$this->description_task = $dataRow['description_task'];
		$this->type_task = $dataRow['type_task'];
		$this->Performer = $dataRow['Performer'];
		$this->date_create = $dataRow['date_create'];*/
		return $query;
	}
	public function createTask($name_task,$description_task,$type_task,$Performer,$date_create){

		$sqlQuery = "INSERT INTO task SET name_task = '".$name_task."',  description_task = '".$description_task."',  type_task = '".$type_task."', Performer = '".$Performer."', date_create = '".$date_create."'";

		$stmt = $this->db->query($sqlQuery);

		// sanitize

		if($stmt){
			return true;
		}
		return false;
	}

	// UPDATE
	public function updateTask($name_task,$description_task,$type_task,$Performer,$date_create,$id){

		$sqlQuery = "UPDATE task SET name_task = '$name_task', description_task = '".$description_task."', type_task = '".$type_task."', Performer = '".$Performer."', date_create = '".$date_create."' WHERE id = '".$id."'";

		$stmt = $this->db->query($sqlQuery);

		if($stmt){
			return true;
		}
		return false;
	}

	// DELETE
	function deleteTask($id){
		$id=htmlspecialchars(strip_tags($id));
		$sqlQuery = "DELETE FROM task WHERE id = '".$id."'";
		$stmt = $this->db->query($sqlQuery);
		if($stmt){
			return true;
		}
		return false;
	}
}
