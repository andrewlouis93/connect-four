<?php

class Account extends CI_Controller {
	 
	function __construct() {
		// Call the Controller constructor
		parent::__construct();
		session_start();
	}

	public function _remap($method, $params = array()) {
		// enforce access control to protected functions

		$protected = array('updatePasswordForm','updatePassword','index','logout');

		if (in_array($method,$protected) && !isset($_SESSION['user']))
			redirect('account/loginForm', 'refresh'); //Then we redirect to the index page again
		 
		return call_user_func_array(array($this, $method), $params);
	}

	//called to load the login view which prompts for username and password
	function loginForm() {
		$this->load->view('account/loginForm');
	}

	//validates user credentials: username and password
	function login() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
			
		//ensures fields are not blank
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('account/loginForm');
		}
		else
		{
			$login = $this->input->post('username');
			$clearPassword = $this->input->post('password');

			$this->load->model('user_model');

			$user = $this->user_model->get($login);
			 
			//ensures user exists and input password matches existing password
			if (isset($user) && $user->comparePassword($clearPassword)) {
				$_SESSION['user'] = $user;
				$data['user']=$user;

				//change user's status in table enabling invites
				$this->user_model->updateStatus($user->id, User::AVAILABLE);

				redirect('arcade/index', 'refresh'); //redirect to the main application page
			}
			else {
				$data['errorMsg']='Incorrect username or password!';
				$this->load->view('account/loginForm',$data);
			}
		}
	}

	//Logs user out, results in a user leaving the game or the waiting area
	function logout() {
		$user = $_SESSION['user'];
		$this->load->model('user_model');
		//chage user status in table
		$this->user_model->updateStatus($user->id, User::OFFLINE);
		session_destroy();
		redirect('account/index', 'refresh'); //Then we redirect to the index page again
	}

	//Invoked to load a user registration form
	function newForm() {
		$this->load->view('account/newForm');
	}

	//Creates a new user once fields in registration form are validated
	function createNew() {
		//ensures fields aren't empty, username and email have to be
		//unique as well
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.login]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('first', 'First', "required");
		$this->form_validation->set_rules('last', 'last', "required");
		$this->form_validation->set_rules('email', 'Email', "required|is_unique[user.email]");

		//ensures fields meet criteria before proceeding
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('account/newForm');
		}
		else
		{
			$user = new User();

			$user->login = $this->input->post('username');
			$user->first = $this->input->post('first');
			$user->last = $this->input->post('last');
			$clearPassword = $this->input->post('password');
			$user->encryptPassword($clearPassword);
			$user->email = $this->input->post('email');
	   
			$this->load->model('user_model');

	   
			$error = $this->user_model->insert($user);
	   
			$this->load->view('account/loginForm');
		}
	}

	//Invoked to load a view that prompts to update password
	function updatePasswordForm() {
		$this->load->view('account/updatePasswordForm');
	}

	//Updates a user's password once the change is validated
	function updatePassword() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('oldPassword', 'Old Password', 'required');
		$this->form_validation->set_rules('newPassword', 'New Password', 'required');
		 
		//do not proceed if any passwords fields are empty
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('account/updatePasswordForm');
		}
		else
		{
			$user = $_SESSION['user'];
	   
			$oldPassword = $this->input->post('oldPassword');
			$newPassword = $this->input->post('newPassword');
	   
			//updates password in the instance the oldPassword field is correct
			if ($user->comparePassword($oldPassword)) {
				$user->encryptPassword($newPassword);
				$this->load->model('user_model');
				$this->user_model->updatePassword($user);
				redirect('arcade/index', 'refresh'); //Then we redirect to the index page again
			}
			else {
				$data['errorMsg']="Incorrect password!";
				$this->load->view('account/updatePasswordForm',$data);
			}
		}
	}

	//Loads view that prompts for user information needed to recover password
	function recoverPasswordForm() {
		$this->load->view('account/recoverPasswordForm');
	}

	//Emails the user their password once email is validated
	function recoverPassword() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('account/recoverPasswordForm');
		}
		else
		{
			$email = $this->input->post('email');
			$this->load->model('user_model');
			$user = $this->user_model->getFromEmail($email);

			if (isset($user)) {
				$newPassword = $user->initPassword();
				$this->user_model->updatePassword($user);

				$this->load->library('email');
				 
				$config['protocol']    = 'smtp';
				$config['smtp_host']    = 'ssl://smtp.gmail.com';
				$config['smtp_port']    = '465';
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = 'your gmail user name';
				$config['smtp_pass']    = 'your gmail password';
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'text'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not

				$this->email->initialize($config);

				$this->email->from('csc309Login@cs.toronto.edu', 'Login App');
				$this->email->to($user->email);

				$this->email->subject('Password recovery');
				$this->email->message("Your new password is $newPassword");

				$result = $this->email->send();

				//$data['errorMsg'] = $this->email->print_debugger();

				//$this->load->view('emailPage',$data);
				$this->load->view('account/emailPage');

			}
			else {
				$data['errorMsg']="No record exists for this email!";
				$this->load->view('account/recoverPasswordForm',$data);
			}
		}
	}
}

