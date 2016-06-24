<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

	public function liste_tout()
	{
			$this->is_logged_in();

            $this->load->model('Livre_model');
            $livres=$this->Livre_model->get();
			$data['livres']="";
			foreach ($livres as $livre) {
				$data['livres'] .= $this->load->view('templates/box_livre', $livre, True);
			}
			$data['auteurs']="";
			$this->load->model('Auteur_model');
			$auteurs=$this->Auteur_model->get();
			foreach ($auteurs as $auteur) {
				$data['auteurs'] .= $this->load->view('templates/box_auteur', $auteur, True);
			}
			$this->load->model('Editeur_model');
			$editeurs=$this->Editeur_model->get();
			$data['editeurs']="";
			foreach ($editeurs as $editeur) {
				$data['editeurs'] .= $this->load->view('templates/box_editeur', $editeur, True);
			}
			$this->load->model('Categorie_model');
			$categories=$this->Categorie_model->get();
			$data['categories']="";
			foreach ($categories as $categorie) {
				$data['categories'] .= $this->load->view('templates/box_categorie', $categorie, True);
			}

			$this->load->model('Emprunteur_model');
			$emprunteurs=$this->Emprunteur_model->get();
			$data['emprunteurs']="";
			foreach ($emprunteurs as $emprunteur) {	
				$data['emprunteurs'] .= $this->load->view('templates/box_emprunteur', $emprunteur, True);
			}
			$this->load->view('templates/header');
			$this->load->view('liste_tout', $data);
			$this->load->view('templates/footer');
	}

	public function index()
	{
			$this->load->view('templates/header');
			$this->load->view('chercher');
			$this->load->view('templates/footer');
	}

}
