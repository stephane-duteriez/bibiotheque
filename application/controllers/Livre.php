<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Livre extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($id=NULL)
	{
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
			$this->load->model('Livre_model');
			$this->load->model('Auteur_model');
			$this->load->model('Categorie_model');

			$this->is_logged_in();
			
			$config = array(
				array(
					'field' => 'titre',
					'label' => 'Titre',
					'rules' => 'required'
				)
				);

			$this->form_validation->set_rules($config);

			if ($id)
			{
				$livre=$this->Livre_model->get($id);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire livre';
				$data['hidden']=array('id_livre'=>$id);
				$data['inputs']=array();

				array_push($data['inputs'], $this->input_field('titre', isset($livre)?$livre:NULL));
				array_push($data['inputs'], $this->input_field('serie', isset($livre)?$livre:NULL));
				array_push($data['inputs'], $this->textarea_field('resume', isset($livre)?$livre:NULL));
				array_push($data['inputs'], $this->input_field('ISBN', isset($livre)?$livre:NULL));
				array_push($data['inputs'], $this->date_field('publication', 'Date Publication', isset($livre)?$livre:NULL));
				$list_auteur = $this->Auteur_model->get_array();
				array_push($data['inputs'], $this->select_field('id_auteur', 'Auteur', $list_auteur, isset($livre)?$livre:NULL));
				$list_categorie = $this->Categorie_model->get_array();
				array_push($data['inputs'], $this->select_field('id_categorie', 'Categorie', $list_categorie, isset($livre)?$livre:NULL));

				$data['submit']=$this->bt_submit();

				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{
				$livre=array(
					'titre'=>$this->input->post('titre'),
					'serie'=>$this->input->post('serie'),
					'resume'=>$this->input->post('resume'),
					'ISBN'=>$this->input->post('ISBN'),
					'publication'=>$this->input->post('publication'),
					'id_auteur'=>$this->input->post('id_auteur')
					);
				if (isset($id)) $livre['id_livre']=$id;
				$categorie=$this->input->post('id_categorie');
				$this->Livre_model->set($livre, $categorie);
				redirect('/');
			}

	}
	public function delete($id=NULL)
	{
			$this->load->model('Livre_model');

			if (isset($id))
			{
				$this->Livre_model->delete($id);
				redirect('/');
			} 
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}

	public function afficher($id)
	{
			$this->load->model('Livre_model');

			$data['livre']=$this->Livre_model->get($id);
			if ($data['livre'])
			{
				$this->load->model('Exemplaire_model');
				$data['list_exemplaire']=$this->Exemplaire_model->from_livre($id);
				
				$this->load->view('templates/header');
				$this->load->view('afficher_livre', $data);
				$this->load->view('templates/footer');
			} else
			{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
			}
	}

	public function seek($indice, $option)
	{
			$this->load->model('Livre_model');
			if (strlen($indice) > 3&& isset($option) && $option!=0)
			{
				$list_livre=$this->Livre_model->seek($indice, $option);
				$sortie="";
				foreach ($list_livre as $livre) {
					$sortie .= $this->load->view('templates/box_livre', $livre, True);
				}
				echo $sortie;
			} else 
			{
				echo 'error';
			}
	}
}