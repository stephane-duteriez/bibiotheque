<?php
class Auteur_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'AUTEUR';
                $this->id='id_auteur';
        }

        public function get($id=NULL)
        {
                if ($id)
                {
                        $this->db->select('*');
                        $this->db->from($this->table);
                        $this->db->where($this->id, $id);
                        $query=$this->db->get();
                        return $query->row();
                } else
                {
        		$this->db->select('*');
        		$this->db->from($this->table);
        		$query=$this->db->get();
        		return $query->result_array();
                }
        }

        // retourne un array pour construire les listes deroulentes.
        public function get_array()
        {
                $this->db->select($this->id. ', nom, prenom');
                $this->db->from($this->table);
                $query=$this->db->get();
                $auteurs=$query->result_array();
                $indexe = array();
                foreach ($auteurs as $auteur) {
                        $indexe[$auteur[$this->id]]=$auteur['nom']. ' ' . $auteur['prenom'];
                }
                return $indexe;
        }

        public function set($data)
        {       
                if (isset($data[$this->id]))
                {
                        $this->db->update_batch($this->table, array($data), $this->id);
                } else
                {
                        $this->db->insert($this->table, $data);
                }
        }

        public function delete($id)
        {
                $query = $this->db->get_where('LIVRE', array($this->id=>$id));
                if ($query->num_rows())
                {
                        redirect('auteur/error/supprime/'.$id);
                } else
                {
                        $this->db->delete($this->table, array($this->id=>$id));
                }
        }
}