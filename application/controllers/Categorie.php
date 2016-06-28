<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorie extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($ref=NULL)
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

			if ($ref)
			{
				$categorie=$this->Categorie_model->get($ref);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire Categorie';
				$data['hidden']=array('ref_categorie'=>$ref);
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
				if (isset($ref)) $categorie['ref_categorie']=$ref;
				$this->Categorie_model->set($categorie);
				redirect('/categorie/liste');
			}

	}
	public function delete($id=NULL)
	{
			$this->load->model('Categorie_model');

			if (isset($id))
			{
				$this->Categorie_model->delete($id);
				redirect('/categorie/liste');
			} 
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}

	public function liste()
	{
		$this->is_logged_in();
		$data['liste']='';
        $this->load->model('Categorie_model');
		$categories=$this->Categorie_model->get();
		foreach ($categories as $categorie) {
			$data['liste'] .= $this->load->view('templates/box_categorie', $categorie, True);
		}
		$data['title']='Categorie';
		$this->load->view('templates/header');
		$this->load->view('templates/liste', $data);
		$this->load->view('templates/footer');

	}
}