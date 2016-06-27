<?php
class Admin_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
		// public function get_users($id_paroisse)
		// {				
		// 		$this->db->select('users.username, users.user_id, users.email, users.auth_level');
		// 		$this->db->from('users');
		// 		$this->db->join('acl', 'acl.user_id = users.user_id', 'inner');
		// 		$this->db->join('acl_actions', 'acl.action_id = acl_actions.action_id', 'inner');
		// 		$this->db->join('acl_categories', 'acl_categories.category_id = acl_actions.category_id');
		// 		$this->db->where(array('acl_categories.category_name'=>$id_paroisse));
		// 		$query = $this->db->get();
		// 		return $query->result_array();
		// }

		public function change_user_parameter()
		{
				$data_user = array();

				if ($this->input->post('username') != $this->auth_username)
				{
					$data_user['username'] = $this->input->post('username'); 
				}

				if ($this->input->post('email') != $this->auth_email)
				{
					$data_user['email'] = $this->input->post('email');
				}

				$this->db->where('user_id', $this->auth_user_id)
					->update( config_item('user_table'), $data_user );
		}

		public function change_user_password()
		{
				$this->load->library('form_validation');

				// Load form validation rules
				$this->load->model('validation_callables');
				$this->form_validation->set_rules(array(
					array(
						'field' => 'passwd',
						'label' => 'NEW PASSWORD',
						'rules' => array(
							'trim',
							'required',
							'matches[passwd_confirm]',
							array( 
								'_check_password_strength', 
								array( $this->validation_callables, '_check_password_strength' ) 
							)
						)
					),
					array(
						'field' => 'passwd_confirm',
						'label' => 'CONFIRM NEW PASSWORD',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'user_identification'
					)
				));
				log_message('debug', 'in the change_user_password');
				if( $this->form_validation->run() !== FALSE )
				{
					log_message('debug', 'validation pass');
					$this->load->vars( array( 'validation_passed' => 1 ) );

					$this->_change_password(
						set_value('passwd'),
						set_value('passwd_confirm'),
						set_value('user_identification'), 
						FALSE
					);
				}
				else
				{
					$this->load->vars( array( 'validation_errors' => validation_errors() ) );
				}
		}

		protected function _change_password($password, $password2, $user_id, $recovery_code)
		{
				// User ID check
				log_message('debug', 'in the change_password function '. ($this->auth_user_id == $user_id));
				if( $this->auth_level > 0) 
				{
					log_message('debug', 'level 1');
					if ( isset($user_id)) 
					{
						log_message('debug', 'level 2');
						if ($this->auth_user_id == $user_id )
						{
							log_message('debug', 'user_id validate');
							$query = $this->db->select( 'user_id' )
								->from( config_item('user_table') )
								->where( 'user_id', $user_id )
								->get();


						}
					} 
				} elseif (isset( $user_id ) && $user_id !== FALSE) {
					log_message('debug', '_password_change : no user is login ('. $user_id . ', ' . $recovery_code . ')');
					$query = $this->db->select( 'user_id' )
						->from( config_item('user_table') )
						->where( 'user_id', $user_id )
						->where( 'passwd_recovery_code', $recovery_code )
						->get();
				}
				// If above query indicates a match, change the password
				if( isset($query) && $query->num_rows() == 1 )
				{
					$user_data = $query->row();
					log_message('debug', '_password_change : one user was found :' . $user_data->user_id);

					$this->db->where( 'user_id', $user_data->user_id )
						->update( 
							config_item('user_table'), 
							array( 'passwd' => $this->authentication->hash_passwd( $password ) ) 
						);
				}
		}

		/**
		 * Get the user name, user salt, and hashed recovery code,
		 * but only if the recovery code hasn't expired.
		 *
		 * @param  int  the user ID
		 */
		public function get_recovery_verification_data( $user_id )
		{
			$recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration') );

			$query = $this->db->select( 'username, passwd_recovery_code' )
				->from( config_item('user_table') )
				->where( 'user_id', $user_id )
				->where( 'passwd_recovery_date >', $recovery_code_expiration )
				->limit(1)
				->get();

			if ( $query->num_rows() == 1 )
				return $query->row();
			
			return FALSE;
		}

		/**
		 * Validation and processing for password change during account recovery
		 */
		public function recovery_password_change()
		{
			$this->load->library('form_validation');

			// Load form validation rules
			$this->load->model('validation_callables');
			$this->form_validation->set_rules(array(
				array(
					'field' => 'passwd',
					'label' => 'NEW PASSWORD',
					'rules' => array(
						'trim',
						'required',
						'matches[passwd_confirm]',
						array( 
							'_check_password_strength', 
							array( $this->validation_callables, '_check_password_strength' ) 
						)
					)
				),
				array(
					'field' => 'passwd_confirm',
					'label' => 'CONFIRM NEW PASSWORD',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'recovery_code'
				),
				array(
					'field' => 'user_identification'
				)
			));

			if( $this->form_validation->run() !== FALSE )
			{
				$this->load->vars( array( 'validation_passed' => 1 ) );

				$this->_change_password(
					set_value('passwd'),
					set_value('passwd_confirm'),
					set_value('user_identification'),
					set_value('recovery_code')
				);
			}
			else
			{
				$this->load->vars( array( 'validation_errors' => validation_errors() ) );
			}
		}

	/**
	* Get data for a recovery
	* 
	* @param   string  the email address
	* @return  mixed   either query data or FALSE
	*/
	public function get_recovery_data( $email )
	{
		$query = $this->db->select( 'u.user_id, u.email, u.banned' )
			->from( config_item('user_table') . ' u' )
			->where( 'LOWER( u.email ) =', strtolower( $email ) )
			->limit(1)
			->get();

		if( $query->num_rows() == 1 )
			return $query->row();

		return FALSE;
	}

	/**
	 * Update a user record with data not from POST
	 *
	 * @param  int     the user ID to update
	 * @param  array   the data to update in the user table
	 * @return bool
	 */
	public function update_user_raw_data( $the_user, $user_data = array() )
	{
		$this->db->where('user_id', $the_user)
			->update( config_item('user_table'), $user_data );
	}

	public function send_recover_email($user_id, $user_email)
	{
		/**
         * Use the authentication libraries salt generator for a random string
         * that will be hashed and stored as the password recovery key.
         * Method is called 4 times for a 88 character string, and then
         * trimmed to 72 characters
         */
        $recovery_code = substr( $this->authentication->random_salt() 
            . $this->authentication->random_salt() 
            . $this->authentication->random_salt() 
            . $this->authentication->random_salt(), 0, 72 );

        // Update user record with recovery code and time
        $this->bapteme_model->update_user_raw_data(
            $user_id,
            array(
                'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
                'passwd_recovery_date' => date('Y-m-d H:i:s')
            )
        );

        // Set the link protocol
        $link_protocol = USE_SSL ? 'https' : NULL;

        // Set URI of link
        $link_uri = 'user_admin/recovery_verification/' . $user_id . '/' . $recovery_code;

        $data['special_link'] = anchor( 
            site_url( $link_uri, $link_protocol ), 
            site_url( $link_uri, $link_protocol ), 
            'target ="_blank"' 
        );

        echo $data['special_link'];
	}
		
}