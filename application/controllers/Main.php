<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

	public function liste_tout()
	{
            $this->load->model('Livre_model');
            $livres=$this->Livre_model->get();
			$data['livres']="";
			foreach ($livres as $livre) {
				$data['livres'] .= $this->load->view('templates/box_livre', $livre, True);
			}
			$this->load->model('Auteur_model');
			$data['auteurs']=$this->Auteur_model->get();
			$this->load->model('Editeur_model');
			$data['editeurs']=$this->Editeur_model->get();
			$this->load->model('Categorie_model');
			$data['categories']=$this->Categorie_model->get();
			$this->load->model('Emprunteur_model');
			$data['emprunteurs']=$this->Emprunteur_model->get();
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
