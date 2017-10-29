<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Shop extends CI_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('product_model');
		$this->load->model('contact_model');
		$active = array(
			"home" => null,
			"about" => null,
			"contact" => null
		);
	}
	
	public function index() {
		
		$categoryData = $this->category_model->getAllCategoriesWithSubCategories();
		$sixProducts = $this->product_model->getActiveProduct();
		$active = array(
			"home" => null,
			"about" => null,
			"contact" => null
		);
		$active['home'] = "active";
		$this->load->view('layout/shop/header', $active);
		$this->load->view('shop/home', array('categoryData' => $categoryData, 'sixProducts' => $sixProducts));
		$this->load->view('layout/shop/footer');
	}
	
	public function product($product_id) {
		$data["product_id"] = $product_id;
		$data['product'] = $this->product_model->getProduct($product_id)->row();
		$data['reviews'] = $this->product_model->getProductReview($product_id);
		$active = array(
			"home" => null,
			"about" => null,
			"contact" => null
		);
		$active['home'] = "active";
		$data['disabled'] = "";
		if ($this->session->userdata('usertype') != "user") {
			$data['disabled'] = "disabled";
		}
		$this->load->view('layout/shop/header', $active);
		$this->load->view('shop/product_page', $data);
		$this->load->view('layout/shop/footer');
	}
	
	public function about() {
		$active = array(
			"home" => null,
			"about" => null,
			"contact" => null
		);
		$active['about'] = "active";
		$this->load->view('layout/shop/header', $active);
		$this->load->view('shop/about_page');
		$this->load->view('layout/shop/footer');
	}
	
	public function contact() {
		$active = array(
			"home" => null,
			"about" => null,
			"contact" => null
		);
		$active['contact'] = "active";
		$this->load->view('layout/shop/header', $active);
		$this->load->view('shop/contact');
		$this->load->view('layout/shop/footer');
	}

	public function addContact() {
		$this->form_validation->set_rules(
			'full_name', 'Full Name',
			'required|min_length[5]|max_length[100]',
			array(
				'required' => $this->dangerAlert('You have not provided your %s.'),
				'max_length' => $this->dangerAlert('Your {field} must not exceeds {param} characters long'),
				'min_length' => $this->dangerAlert('Your {field} must be at least {param} characters long')
			)
		);

		$this->form_validation->set_rules(
			'e-mail', 'E-Mail',
			'required|valid_email',
			array(
				'required' => $this->dangerAlert('You have not provided your %s.'),
				'max_length' => $this->dangerAlert('You did not provide a valid E-Mail Address')
			)
		);

		$this->form_validation->set_rules(
			'message', 'Message',
			'required|min_length[30]|max_length[500]',
			array(
				'required' => $this->dangerAlert('You have not provided your %s.'),
				'max_length' => $this->dangerAlert('Your {field} must not exceeds {param} characters long'),
				'min_length' => $this->dangerAlert('Your {field} must be at least {param} characters long')
			)
		);

		if ($this->form_validation->run() == FALSE) {
			$this->contact();
		} else {
			$data['full_name'] = $this->input->post('full_name');
			$data['email'] = $this->input->post('e-mail');
			$data['message'] = $this->input->post('message');
			$insert = $this->contact_model->addMessage($data);
			if ($insert) {
				$message = $this->successAlert('You have submitted a message!');
			} else {
				$message = $this->dangerAlert('Fail to submit a message');
			}
			$this->session->set_flashdata('message', $message);
			redirect('shop/contact');
		}

	}

	public function dangerAlert($message) {
		$alert = "<div class='alert alert-danger alert-dismissable'>";
		$alert .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
		$alert .= "$message";
		$alert .= "</div>";
		return $alert;
	}

	public function successAlert($message) {
		$alert = "<div class='alert alert-success alert-dismissable'>";
		$alert .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
		$alert .= "$message";
		$alert .= "</div>";
		return $alert;
	}

}
?>