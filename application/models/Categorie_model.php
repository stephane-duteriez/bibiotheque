<?php
class Categorie_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'CATEGORIE';
                $this->id='id_categorie';
                $this->ref='ref_categorie';
        }

        public function get($ref=NULL)
        {
                if ($ref)
                {
                        $this->db->select('*');
                        $this->db->from($this->table);
                        $this->db->where($this->ref, $ref);
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
                $this->db->select($this->id. ', intitule');
                $this->db->from($this->table);
                $query=$this->db->get();
                $auteurs=$query->result_array();
                $indexe = array();
                foreach ($auteurs as $auteur) {
                        $indexe[$auteur[$this->id]]=$auteur['intitule'];
                }
                return $indexe;
        }
        public function set($data)
        {       
                if (isset($data[$this->ref]))
                {
                        $this->db->update_batch($this->table, array($data), $this->ref);
                } else
                {
                        $data[$this->ref]=$this->get_new_ref();
                        $this->db->insert($this->table, $data);
                }
        }

        public function delete($ref)
        {
                $id_categorie = $this->db->get_where($this->table, array($this->ref=>$ref))->row()->id_categorie;
                $query = $this->db->get_where('APPARTIENT', array($this->id=>$id_categorie));
                if ($query->num_rows())
                {
                        redirect('auteur/error/supprime/'.$ref);
                } else
                {
                        $this->db->delete($this->table, array($this->ref=>$ref));
                }
        }
}