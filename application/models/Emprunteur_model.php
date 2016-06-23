<?php
class Emprunteur_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'EMPRUNTEUR';
                $this->id='id_emprunteur';
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
                $query = $this->db->get_where('EMPRUNTE', array($this->id=>$id));
                if ($query->num_rows())
                {
                        redirect('auteur/error/supprime/'.$id);
                } else
                {
                        $this->db->delete($this->table, array($this->id=>$id));
                }
        }

        public function emprunter($id_exemplaire, $id_emprunteur)
        {
                $emprunt = array (
                        'id_emprunteur'=>$id_emprunteur,
                        'id_exemplaire'=>$id_exemplaire,
                        'date_emprunt'=>date('Y-m-d G:i:s')
                        );

                $this->db->insert('EMPRUNTE', $emprunt);
        }
}