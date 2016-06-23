<?php
class Editeur_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'EDITEUR';
                $this->id='id_editeur';
        }

        public function get($id_editeur=NULL)
        {
                        if ($id_editeur)
                        {
                                $this->db->select('*');
                                $this->db->from('EDITEUR');
                                $this->db->where('id_editeur', $id_editeur);
                                $query=$this->db->get();
                                return $query->row();
                        } else
                        {
                		$this->db->select('*');
                		$this->db->from('EDITEUR');
                		$query=$this->db->get();
                		return $query->result_array();
                        }
        }

        public function set($editeur)
        {       
                if (isset($editeur['id_editeur']))
                {
                        $this->db->update_batch('EDITEUR', array($editeur), 'id_editeur');
                } else
                {
                        $this->db->insert('EDITEUR', $editeur);
                }
        }

        public function delete($id_editeur)
        {
                $query = $this->db->get_where('EDITE', array('id_editeur'=>$id_editeur));
                if ($query->num_rows())
                {
                        redirect('editeur/error/supprime');
                } else
                {
                        $this->db->delete('EDITEUR', array('id_editeur'=>$id_editeur));
                }
        }

        public function get_array()
        {
                $this->db->select($this->id. ', intitule');
                $this->db->from($this->table);
                $query=$this->db->get();
                $datas=$query->result_array();
                $indexe = array();
                foreach ($datas as $data) {
                        $indexe[$data[$this->id]]=$data['intitule'];
                }
                return $indexe;
        }
}