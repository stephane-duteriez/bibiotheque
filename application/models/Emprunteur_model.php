<?php
class Emprunteur_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'EMPRUNTEUR';
                $this->id='id_emprunteur';
                $this->ref='ref_emprunteur';
        }

        public function get($ref=NULL)
        {
                if ($ref) // if there is a reference return only the element .
                {
                        $this->db->select('*');
                        $this->db->from($this->table);
                        $this->db->where($this->ref, $ref);
                        $query=$this->db->get();
                        return $query->row();
                } else // return all the elements.
                {
                        $this->db->select('*');
                        $this->db->from($this->table);
                        $query=$this->db->get();
                        return $query->result_array();
                }
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
                $id_emprunteur = $this->db->get_where($this->table, array($this->ref=>$ref))->row()->id_emprunteur;
                $query = $this->db->get_where('EMPRUNTE', array($this->id=>$id_emprunteur));
                if ($query->num_rows())
                {
                        redirect('emprunteur/error/supprime/'.$ref);
                } else
                {
                        $this->db->delete($this->table, array($this->id=>$id_emprunteur));
                }
        }

        public function emprunter($ref_exemplaire, $ref_emprunteur)
        {
                $id_emprunteur = $this->db->get_where($this->table, array($this->ref=>$ref_emprunteur))->row()->id_emprunteur;
                $id_exemplaire = $this->db->get_where('EXEMPLAIRE', array($this->ref=>$ref_exemplaire))->row()->id_exemplaire;
                $emprunt = array (
                        'id_emprunteur'=>$id_emprunteur,
                        'id_exemplaire'=>$id_exemplaire,
                        'date_emprunt'=>date('Y-m-d G:i:s')
                        );

                $this->db->insert('EMPRUNTE', $emprunt);
        }
}