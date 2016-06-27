<?php

class Upload extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->helper(array('form', 'url'));
        }

        public function index()
        {
                $this->load->view('templates/upload_form', array('error' => ' ' ));
        }

        public function do_upload()
        {
                $this->load->helper('file');

                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('userfile'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        $this->load->view('templates/upload_form', $error);
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());
                        
                        $config_resize['image_library'] = 'gd2';
                        echo $data['upload_data']['full_path'];
                        $config_resize['source_image'] = $data['upload_data']['full_path'];
                        $image_name=substr(md5(date('Y-m-d H:i:s')), -6) . $data['upload_data']['file_ext'];
                        $config_resize['new_image'] = './uploads/' .  $image_name;
                        $config_resize['maintain_ratio'] = TRUE;
                        $config_resize['width']         = 100;
                        $config_resize['height']       = 100;

                        $this->load->library('image_lib');
                        $this->image_lib->initialize($config_resize);                        
                        if ( ! $this->image_lib->resize())
                        {
                                echo $this->image_lib->display_errors();
                        }
                        $this->image_lib->clear();
                        unlink($data['upload_data']['full_path']);
                        $this->load->view('templates/upload_success', $data);
                }
        }
}
?>