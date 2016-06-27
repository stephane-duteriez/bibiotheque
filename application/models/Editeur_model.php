<?php
class Editeur_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'EDITEUR';
                $this->id='id_editeur';
                $this->ref='ref_editeur';
        }

        public function get($ref_editeur=NULL)
        {
                        if ($ref_editeur)
                        {
                                $this->db->select('*');
                                $this->db->from('EDITEUR');
                                $this->db->where('ref_editeur', $ref_editeur);
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
                if (isset($editeur['ref_editeur']))
                {
                        $this->db->update_batch('EDITEUR', array($editeur), 'ref_editeur');
                } else
                {
                        $editeur[$this->ref]=$this->get_new_ref();
                        $this->db->insert('EDITEUR', $editeur);
                }
        }

        public function delete($ref_editeur)
        {
                $id_editeur = $this->db->get_where('EDITEUR', array($this->ref=>$ref_editeur))->row()->id_editeur;
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