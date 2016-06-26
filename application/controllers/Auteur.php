<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auteur extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($id=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Auteur_model');

			$this->is_logged_in();
			
			$config = array(
				array(
					'field' => 'nom',
					'label' => 'Nom',
					'rules' => 'required'
				),
				array(
					'field' => 'prenom',
					'label' => 'Prenom',
					'rules' => 'required'
				)
				);

			$this->form_validation->set_rules($config);

			if ($id)
			{
				$auteur=$this->Auteur_model->get($id);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire auteur';
				$data['hidden']=array('id_auteur'=>$id);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('nom', isset($auteur)?$auteur:NULL));
				array_push($data['inputs'], $this->input_field('prenom', isset($auteur)?$auteur:NULL));
				array_push($data['inputs'], $this->input_field('nationalite', isset($auteur)?$auteur:NULL));
				array_push($data['inputs'], $this->date_field('date_naissance', 'Date Naissance', isset($auteur)?$auteur:NULL));
				array_push($data['inputs'], $this->date_field('date_dece', 'Date Naissance', isset($auteur)?$auteur:NULL));
				$list_genre = array(
						'0' => 'homme',
						'1' => 'femme'
					);
				array_push($data['inputs'], $this->select_field('genre', 'genre', $list_genre, isset($auteur)?$auteur:NULL));


				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$auteur=array(
					'nom'=>$this->input->post('nom'),
					'prenom'=>$this->input->post('prenom'),
					'date_naissance'=>$this->input->post('date_naissance'),
					'date_dece'=>$this->input->post('date_dece'),
					'nationalite'=>$this->input->post('nationalite'),
					'genre'=>$this->input->post('genre')
					);
				if (isset($id)) $auteur['id_auteur']=$id;
				$this->Auteur_model->set($auteur);
				redirect('/');
			}

	}
	public function delete($id=NULL)
	{
			$this->load->model('Auteur_model');

			if (isset($id))
			{
				$this->Auteur_model->delete($id);
				redirect('/');
			} 
			echo 'il n\'y a pas d\'auteur avec cette id.';
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}
}