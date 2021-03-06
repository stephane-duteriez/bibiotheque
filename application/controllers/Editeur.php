<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editeur extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($ref_editeur=NULL)
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

			if ($ref_editeur)
			{
				$editeur=$this->Editeur_model->get($ref_editeur);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='fromulaire éditeur';
				$data['hidden']=array('ref_editeur'=>$ref_editeur);
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
				if (isset($ref_editeur)) $editeur['ref_editeur']=$ref_editeur;
				$this->Editeur_model->set($editeur);
				redirect('/editeur/liste');
			}

	}
	public function delete($id_editeur=NULL)
	{
			$this->load->model('Editeur_model');

			if (isset($id_editeur))
			{
				$this->Editeur_model->delete($id_editeur);
				redirect('/editeur/liste');
			} 
			echo 'il n\'y a pas d\'editeur avec cette id.';
			
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
        $this->load->model('Editeur_model');
		$editeurs=$this->Editeur_model->get();
		foreach ($editeurs as $editeur) {
			$data['liste'] .= $this->load->view('templates/box_editeur', $editeur, True);
		}
		$data['title']='Editeur';
		$this->load->view('templates/header');
		$this->load->view('templates/liste', $data);
		$this->load->view('templates/footer');

	}
}