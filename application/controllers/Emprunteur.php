<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emprunteur extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($id=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Emprunteur_model');

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
				),
				array(
					'field' => 'mail',
					'label' => 'Email',
					'rules' => 'trim|valid_email',
					'errors' => array(
                        'valid_email' => 'Mail not valide.')
                ),
				);

			$this->form_validation->set_rules($config);

			if ($id)
			{
				$emprunteur=$this->Emprunteur_model->get($id);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire emprunteur';
				$data['hidden']=array('id_emprunteur'=>$id);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('nom', isset($emprunteur)?$emprunteur:NULL));
				array_push($data['inputs'], $this->input_field('prenom', isset($emprunteur)?$emprunteur:NULL));
				array_push($data['inputs'], $this->input_field('adresse', isset($emprunteur)?$emprunteur:NULL));
				array_push($data['inputs'], $this->date_field('naissance', 'Date Naissance', isset($emprunteur)?$emprunteur:NULL));
				array_push($data['inputs'], $this->input_field('mail', isset($emprunteur)?$emprunteur:NULL));
				array_push($data['inputs'], $this->input_field('phone', isset($emprunteur)?$emprunteur:NULL));


				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$emprunteur=array(
					'nom'=>$this->input->post('nom'),
					'prenom'=>$this->input->post('prenom'),
					'naissance'=>$this->input->post('naissance'),
					'adresse'=>$this->input->post('adresse'),
					'mail'=>$this->input->post('mail'),
					'phone'=>$this->input->post('phone')
					);
				if (isset($id)) $emprunteur['id_emprunteur']=$id;
				$this->Emprunteur_model->set($emprunteur);
				redirect('/');
			}

	}

	public function delete($id=NULL)
	{
			$this->load->model('Emprunteur_model');

			if (isset($id))
			{
				$this->Emprunteur_model->delete($id);
				redirect('/');
			} 
			echo 'il n\'y a pas d\'emprunteur avec cette id.';
			
	}

	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}

	public function get($id=NULL)
	{
		if(isset($id))
		{
			$this->load->model('Emprunteur_model');
			$emprunteur=$this->Emprunteur_model->get($id);
			$sortie=json_encode($emprunteur);
			echo $sortie;
			
		} else {
			echo 'erreur.';
		}
	}
}