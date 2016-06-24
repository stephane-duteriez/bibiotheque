<?php
class User_admin extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('Admin_model');
                $this->load->helper('url_helper');
                $this->load->helper('form');

        }

        public function login()
        {
                // Method should not be directly accessible
                if( $this->uri->uri_string() == 'bapteme/login')
                    show_404();

                if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
                    $this->require_min_level(1);

                $this->setup_login_form();
                $data['title'] = 'page de login';

                $this->load->view('templates/header', $data);
                $this->load->view('user_login/login_form', $data);
                $this->load->view('templates/footer', $data);

        }

        // --------------------------------------------------------------

        /**
        * Log out
        */
        public function logout()
        {
                $this->authentication->logout();

                // Set redirect protocol
                $redirect_protocol = USE_SSL ? 'https' : NULL;

                redirect( site_url( LOGIN_PAGE . '?logout=1', $redirect_protocol ) );
        }


        
        
        public function change_user_parameter()
        {
                $this->load->helper('form');
                $this->load->library('form_validation');

                if ($this->require_min_level(1))
                {
                        $validation_rules_username = array(
                                        'field' => 'username',
                                        'label' => 'username',
                                        'rules' => 'max_length[12]|is_unique[' . config_item('user_table') . '.username]',
                                        'errors' => array(
                                            'is_unique' => 'Username already in use.'
                                        )
                                );
                        $validation_rules_email = array(
                                        'field'  => 'email',
                                        'label'  => 'email',
                                        'rules'  => 'trim|required|valid_email|is_unique[' . config_item('user_table') . '.email]',
                                        'errors' => array(
                                            'is_unique' => 'Email address already in use.'
                                        )
                                );
                        $validation_rules = array();
                        if ($this->input->post('username') != $this->auth_username)
                        {
                                array_push($validation_rules, $validation_rules_username);
                        }
                        if ($this->input->post('email') != $this->auth_email)
                        {
                                array_push($validation_rules, $validation_rules_email);
                        }

                        $this->form_validation->set_rules($validation_rules);
                        if ($this->form_validation->run() == FALSE)
                        {
                                $data['title'] = 'Changememt des parametres de l\'utilisateurs';

                                $this->load->view('templates/header', $data);
                                $this->load->view('user_login/user_parameter', $data);
                                $this->load->view('templates/footer', $data);
                        }
                        else
                        {
                                $this->bapteme_model->change_user_parameter();
                                redirect(base_url());
                        }
                } else {        
                        redirect(base_url());
                }
        }

        public function change_user_password()
        {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->load->model('validation_callables');

                if ($this->require_min_level(1))
                {
                    $validation_rules=array(array(
                                'field' => 'passwd',
                                'label' => 'passwd',
                                'rules' => array(
                                    'trim',
                                    'required',
                                    array( 
                                        '_check_password_strength', 
                                        array( $this->validation_callables, '_check_password_strength' ) 
                                    )
                                ),
                                'errors' => array(
                                    'required' => 'The password field is required.'
                                )
                        )
                    );
                    $this->form_validation->set_rules($validation_rules);
                    if ($this->input->post('password') != $this->input->post('password_confirm') ||      $this->form_validation->run() == FALSE)
                    {
                                $data['title'] = 'Changememt mot de pass de l\'utilisateur';
                                $data['type_password_change'] = "change password";

                                $this->load->view('templates/header', $data);
                                $this->load->view('user_login/change_password_form', $data);
                                $this->load->view('templates/footer', $data);
                    } else
                    {
                        $this->bapteme_model->change_user_password();
                        redirect(base_url());
                    }
                } else {
                        redirect(base_url());
                }
        }

    /**
    * User recovery form
    */
    public function recover()
    {
        // Load resources
        $this->load->model('bapteme_model');

        /// If IP or posted email is on hold, display message
        if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
        {
            $data['disabled'] = 1;
        }
        else
        {
            // If the form post looks good
            if( $this->tokens->match && $this->input->post('email') )
            {
                if( $user_data = $this->bapteme_model->get_recovery_data( $this->input->post('email') ) )
                {
                    // Check if user is banned
                    if( $user_data->banned == '1' )
                    {
                        // Log an error if banned
                        $this->authentication->log_error( $this->input->post('email', TRUE ) );

                        // Show special message for banned user
                        $data['banned'] = 1;
                    }
                    else
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
                            $user_data->user_id,
                            array(
                                'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
                                'passwd_recovery_date' => date('Y-m-d H:i:s')
                            )
                        );

                        // Set the link protocol
                        $link_protocol = USE_SSL ? 'https' : NULL;

                        // Set URI of link
                        $link_uri = 'user_admin/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;

                        $data['special_link'] = anchor( 
                            site_url( $link_uri, $link_protocol ), 
                            site_url( $link_uri, $link_protocol ), 
                            'target ="_blank"' 
                        );

                        $data['confirmation'] = 1;
                    }
                }

                // There was no match, log an error, and display a message
                else
                {
                    // Log the error
                    $this->authentication->log_error( $this->input->post('email', TRUE ) );

                    $data['no_match'] = 1;
                }
            }
        }
        $data['title'] = "Réinitialisation de mot de pass";

        echo $this->load->view('templates/header', $data, TRUE);

        echo $this->load->view('user_login/recover_form', ( isset( $data ) ) ? $data : '', TRUE );

        echo $this->load->view('templates/footer', $data, TRUE);
    }

    /**
     * Verification of a user by email for recovery
     * 
     * @param  int     the user ID
     * @param  string  the passwd recovery code
     */
    public function recovery_verification( $user_id = '', $recovery_code = '' )
    {
        /// If IP is on hold, display message
        if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
        {
            $data['disabled'] = 1;
        }
        else
        {
            // Load resources
            $this->load->model('bapteme_model');

            if( 
                /**
                 * Make sure that $user_id is a number and less 
                 * than or equal to 10 characters long
                 */
                is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

                /**
                 * Make sure that $recovery code is exactly 72 characters long
                 */
                strlen( $recovery_code ) == 72 &&

                /**
                 * Try to get a hashed password recovery 
                 * code and user salt for the user.
                 */
                $recovery_data = $this->bapteme_model->get_recovery_verification_data( $user_id ) )
            {
                /**
                 * Check that the recovery code from the 
                 * email matches the hashed recovery code.
                 */
                if( $recovery_data->passwd_recovery_code == $this->authentication->check_passwd( $recovery_data->passwd_recovery_code, $recovery_code ) )
                {
                    $data['user_id']       = $user_id;
                    $data['username']     = $recovery_data->username;
                    $data['recovery_code'] = $recovery_data->passwd_recovery_code;
                }

                // Link is bad so show message
                else
                {
                    $data['recovery_error'] = 1;

                    // Log an error
                    $this->authentication->log_error('');
                }
            }

            // Link is bad so show message
            else
            {
                $data['recovery_error'] = 1;

                // Log an error
                $this->authentication->log_error('');
            }

            /**
             * If form submission is attempting to change password 
             */
            if( $this->tokens->match )
            {
                $this->bapteme_model->recovery_password_change();
            }
        }

        $data['title'] = "verification réinitialisation mot de passe";
        $data['type_password_change'] = "password recovery";
        $link_uri = 'user_admin/recovery_verification/' . $user_id . '/' . $recovery_code;
        $data['link_uri'] = $link_uri;

        echo $this->load->view('templates/header', $data, TRUE);
        echo $this->load->view('user_login/change_password_form', $data, TRUE );
        echo $this->load->view('templates/footer', $data, TRUE);
    }

      

/**
     * Most minimal user creation. You will of course make your
     * own interface for adding users, and you may even let users
     * register and create their own accounts.
     *
     * The password used in the $user_data array needs to meet the
     * following default strength requirements:
     *   - Must be at least 8 characters long
     *   - Must be at less than 72 characters long
     *   - Must have at least one digit
     *   - Must have at least one lower case letter
     *   - Must have at least one upper case letter
     *   - Must not have any space, tab, or other whitespace characters
     *   - No backslash, apostrophe or quote chars are allowed
     */

    

public function create_user()
    {
        // Customize this array for your user
        $user_data = array(
            'username'   => 'test',
            'passwd'     => 'Testtest99',
            'email'      => 'test@example.com',
            'auth_level' => '9', // 9 if you want to login @ examples/index.
        );

        $this->is_logged_in();

        echo $this->load->view('templates/header', '', TRUE);

        // Load resources
        $this->load->model('examples_model');
        $this->load->model('validation_callables');
        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = array(
      array(
        'field' => 'username',
        'label' => 'username',
        'rules' => 'max_length[12]|is_unique[' . config_item('user_table') . '.username]',
                'errors' => array(
                    'is_unique' => 'Username already in use.'
                )
      ),
      array(
        'field' => 'passwd',
        'label' => 'passwd',
        'rules' => array(
                    'trim',
                    'required',
                    array( 
                        '_check_password_strength', 
                        array( $this->validation_callables, '_check_password_strength' ) 
                    )
                ),
                'errors' => array(
                    'required' => 'The password field is required.'
                )
      ),
      array(
                'field'  => 'email',
                'label'  => 'email',
                'rules'  => 'trim|required|valid_email|is_unique[' . config_item('user_table') . '.email]',
                'errors' => array(
                    'is_unique' => 'Email address already in use.'
                )
      ),
      array(
        'field' => 'auth_level',
        'label' => 'auth_level',
        'rules' => 'required|integer|in_list[1,6,9]'
      )
    );

    $this->form_validation->set_rules( $validation_rules );

    if( $this->form_validation->run() )
    {
            $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
            $user_data['user_id']    = $this->examples_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['username'] ) )
            {
                $user_data['username'] = NULL;
            }

      $this->db->set($user_data)
        ->insert(config_item('user_table'));

      if( $this->db->affected_rows() == 1 )
        echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p>';
    }
    else
    {
      echo '<h1>User Creation Error(s)</h1>' . validation_errors();
    }

        echo $this->load->view('templates/footer', '', TRUE);
    }
  }