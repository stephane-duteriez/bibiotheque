<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Livre extends MY_Controller {

	public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }

    public function form($ref=NULL)
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

			if ($ref)
			{
				$livre=$this->Livre_model->get($ref);
			} 
			
			if ($this->form_validation->run()==FALSE)
			{
				$data['titre']='formulaire livre';
				$data['hidden']=array('ref_livre'=>$ref);
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
				$data['upload']=TRUE;
				$data['ref_image']=isset($livre)?$livre->ref_image:NULL;
				$this->load->view('templates/header');
				$this->load->view('templates/formulaire', $data);
				$this->load->view('templates/footer');
			}
			else
			{

				$this->load->helper('file');

                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
	            $this->load->library('upload', $config);

	            if ( ! $this->upload->do_upload('userfile'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        $this->load->view('templates/upload_form', $error);
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());
                        
                        $config_resize['image_library'] = 'gd2';
                        echo $data['upload_data']['full_path'];
                        $config_resize['source_image'] = $data['upload_data']['full_path'];
                        $image_name=substr(md5(date('Y-m-d H:i:s')), -6) . $data['upload_data']['file_ext'];
                        $config_resize['new_image'] = './uploads/' .  $image_name;
                        $config_resize['maintain_ratio'] = TRUE;
                        $config_resize['width']         = 200;
                        $config_resize['height']       = 200;

                        $this->load->library('image_lib');
                        $this->image_lib->initialize($config_resize);                        
                        if ( ! $this->image_lib->resize())
                        {
                                echo $this->image_lib->display_errors();
                        }
                        $this->image_lib->clear();
                        unlink($data['upload_data']['full_path']);
                }
				
				$livre=array(
					'titre'=>$this->input->post('titre'),
					'serie'=>$this->input->post('serie'),
					'resume'=>$this->input->post('resume'),
					'ISBN'=>$this->input->post('ISBN'),
					'publication'=>$this->input->post('publication'),
					'id_auteur'=>$this->input->post('id_auteur'),
					'ref_image'=>isset($image_name)?$image_name:((isset($livre))?$livre->ref_image:'')
					);
				if (isset($ref)) $livre['ref_livre']=$ref;
				$categorie=$this->input->post('id_categorie');
				$ref = $this->Livre_model->set($livre, $categorie);
				redirect('/livre/afficher/'.$ref);
			}

	}
	public function delete($ref=NULL)
	{
			$this->load->model('Livre_model');

			if (isset($ref))
			{
				$this->Livre_model->delete($ref);
				redirect('/livre/liste');
			} 
			
	}
	public function error($id_editeur=NULL)
	{
				$this->load->view('templates/header');
				$this->load->view('templates/error');
				$this->load->view('templates/footer');
	}

	public function afficher($ref)
	{
			$this->load->model('Livre_model');

			$data['livre']=$this->Livre_model->get($ref);
			if ($data['livre'])
			{
				$this->load->model('Exemplaire_model');
				$data['list_exemplaire']=$this->Exemplaire_model->from_livre($data['livre']->id_livre);
				
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

	public function seek($indice=NULL, $option=NULL)
	{
			$this->load->model('Livre_model');
			if (isset($indice)&&isset($option))
			{
				if (strlen($indice) > 3&& isset($option) && $option!=0)
				{
					$list_livre=$this->Livre_model->seek($indice, $option);
				}
			} else
			{
				$option = array (
					1=>$this->input->get('1'),
					2=>$this->input->get('2'),
					4=>$this->input->get('4'),
					8=>$this->input->get('8')
					);
				$list_livre=$this->Livre_model->seek_adv($option);
			}

			if (isset($list_livre))
			{
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

	public function liste()
	{
		$this->is_logged_in();

        $this->load->model('Livre_model');
        $livres=$this->Livre_model->get();
		$data['liste']="";
		foreach ($livres as $livre) {
			$data['liste'] .= $this->load->view('templates/box_livre', $livre, True);
		}
		$data['title']='Livre';
		$this->load->view('templates/header');
		$this->load->view('templates/liste', $data);
		$this->load->view('templates/footer');

	}
}