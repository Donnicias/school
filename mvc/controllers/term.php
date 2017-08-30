<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Term extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("term_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('term', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$this->data['terms'] = $this->term_m->get_term();
			$this->data["subview"] = "term/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'term_name', 
				'label' => $this->lang->line("term_name"), 
				'rules' => 'trim|required|xss_clean|max_length[60]'
			)
		);
		return $rules;
	}

	public function add() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "term/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$array = array();
					$array["term_name"] = $this->input->post("term_name");
					$array["term_status"] = 0;
					$this->term_m->insert_term($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("term/index"));
				}
			} else {
				$this->data["subview"] = "term/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			if ((int)$id) {
				$this->data['term'] = $this->term_m->get_term($id);
					if($this->data['term']) {
						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) { 
								$this->data["subview"] = "term/edit";
								$this->load->view('_layout_main', $this->data);
							} else {
								$array = array();
								$array["term_name"] = $this->input->post("term_name");
								
								$this->term_m->update_term($array, $id);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("term/index"));
							}
						} else {
							$this->data["subview"] = "term/edit";
							$this->load->view('_layout_main', $this->data);
						}
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			if((int)$id) {
				$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
				if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
					if($this->data['systemadmin']) {
						if($this->data['systemadmin']->photo != 'defualt.png') {
							unlink(FCPATH.'uploads/images/'.$this->data['systemadmin']->photo);
						}
						$this->systemadmin_m->delete_systemadmin($id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("systemadmin/index"));
					} else {
						redirect(base_url("systemadmin/index"));
					}
				} else {
					redirect(base_url("systemadmin/index"));
				}
			} else {
				redirect(base_url("systemadmin/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	function active() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$id = $this->input->post('id');
			$this->data['terms'] = $this->term_m->get_term($id);
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'checked') {
						$this->term_m->update_term(array('term_status' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchecked') {
						$this->term_m->update_term(array('term_status' => 0), $id);
						echo 'Success';
					} else {
						echo "Error";
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}
}

/* End of file user.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/user.php */