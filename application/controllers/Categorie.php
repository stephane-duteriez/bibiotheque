<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorie extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($id=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Categorie_model');

			$this->is_logged_in();
			
			$config = array(
				array(
					'field' => 'intitule',
					'label' => 'Intitule',
					'rules' => 'required'
				));

			$this->form_validation->set_rules($config);

			if ($id)
			{
				$categorie=$this->Categorie_model->get($id);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire Categorie';
				$data['hidden']=array('id_categorie'=>$id);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('intitule', isset($categorie)?$categorie:NULL));
				array_push($data['inputs'], $this->textarea_field('description', isset($categorie)?$categorie:NULL));

				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$categorie=array(
					'intitule'=>$this->input->post('intitule'),
					'description'=>$this->input->post('description')
					);
				if (isset($id)) $categorie['id_categorie']=$id;
				$this->Categorie_model->set($categorie);
				redirect('/');
			}

	}
	public function delete($id=NULL)
	{
			$this->load->model('Categorie_model');

			if (isset($id))
			{
				$this->Categorie_model->delete($id);
				redirect('/');
			} 
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}
}