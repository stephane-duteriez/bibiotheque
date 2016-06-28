<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exemplaire extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($ref=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Exemplaire_model');

			$this->is_logged_in();
			
			$config = array(
				array(
					'field' => 'reference',
					'label' => 'Reference',
					'rules' => 'required'
				));

			$this->form_validation->set_rules($config);

			$ref_livre = $this->input->get('ref_livre');

			if ($ref)
			{
				$exemplaire=$this->Exemplaire_model->get($ref);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$this->load->model('Editeur_model');
				$this->load->model('Livre_model');
				$this->load->model('Exemplaire_model');

				$data['titre']='formulaire Exemplaire';
				$data['hidden']=array('ref_exemplaire'=>$ref);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('reference', isset($exemplaire)?$exemplaire:NULL));
				$list_livre = $this->Livre_model->get_array();
				$livre =  $this->Livre_model->get($ref_livre);
				array_push($data['inputs'], $this->select_field('id_livre', 'Livre', $list_livre, isset($exemplaire)?$exemplaire:((isset($livre))?(object) array('id_livre'=>$livre->id_livre):NULL)));
				$list_editeur = $this->Editeur_model->get_array();
				array_push($data['inputs'], $this->select_field('id_editeur', 'Editeur', $list_editeur, isset($exemplaire)?$exemplaire:NULL));

				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$exemplaire=array(
					'reference'=>$this->input->post('reference'),
					'id_livre'=>$this->input->post('id_livre')
					);
				if (isset($ref)) $exemplaire['ref_exemplaire']=$ref;
				$ref_livre = $this->Exemplaire_model->set($exemplaire, $this->input->post('id_editeur'));
				redirect('/livre/afficher/' . $ref_livre);
			}

	}
	public function delete($ref=NULL)
	{
			$this->load->model('Exemplaire_model');

			if (isset($ref))
			{
				$ref_livre=$this->Exemplaire_model->delete($ref);
				redirect('/livre/afficher/'.$ref_livre);
			} 
			
	}
	public function error($id_editeur=NULL)
	{
			$this->load->view('templates/header');
			$this->load->view('templates/error');
			$this->load->view('templates/footer');
	}

	public function rendre($id_emprunt=NULL)
	{
			$this->load->model('Exemplaire_model');
			if (isset($id_emprunt))
			{
				$sortie=$this->Exemplaire_model->rendre($id_emprunt);
				if ($sortie)
				{
					echo 'exemplaire rendu.';
				} else
				{
					echo 'erreur.';
				}
			} else
			{
				echo 'erreur.';
			}
	}

	public function emprunter($id_exemplaire, $id_emprunteur)
	{
			$this->load->model('Emprunteur_model');
			if (isset($id_emprunteur)&&isset($id_exemplaire))
			{
				$this->Emprunteur_model->emprunter($id_exemplaire, $id_emprunteur);
			}
	}
}