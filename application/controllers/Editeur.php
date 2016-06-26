<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editeur extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($id_editeur=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Editeur_model');

			$this->is_logged_in();
			
			$config = array(
				array(
					'field' => 'intitule',
					'label' =>'Intitule',
					'rules' => 'required'
				));

			$this->form_validation->set_rules($config);

			if ($id_editeur)
			{
				$editeur=$this->Editeur_model->get($id_editeur);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='fromulaire éditeur';
				$data['hidden']=array('id_editeur'=>$id_editeur);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('intitule', isset($editeur)?$editeur:NULL));
				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$editeur=array(
					'intitule'=>$this->input->post('intitule')
					);
				if (isset($id_editeur)) $editeur['id_editeur']=$id_editeur;
				$this->Editeur_model->set($editeur);
				redirect('/');
			}

	}
	public function delete($id_editeur=NULL)
	{
			$this->load->model('Editeur_model');

			if (isset($id_editeur))
			{
				$this->Editeur_model->delete($id_editeur);
				redirect('/');
			} 
			echo 'il n\'y a pas d\'editeur avec cette id.';
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}
}