<?php
class Exemplaire_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'EXEMPLAIRE';
                $this->id='id_exemplaire';
                $this->ref='ref_exemplaire';
        }

        public function get($ref=NULL)
        {
                if ($ref)
                {
                        $this->db->select('*');
                        $this->db->from($this->table);
                        $this->db->join('EDITE', 'EDITE.id_exemplaire=EXEMPLAIRE.id_exemplaire', 'inner');
                        $this->db->where('EXEMPLAIRE.'.$this->ref, $ref);
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

        public function from_livre($id)
        {
                $this->db->select($this->table . '.*, EMPRUNTE.date_rendu, EMPRUNTE.id_emprunt, EMPRUNTE.date_emprunt');
                $this->db->from($this->table);
                $this->db->join('EMPRUNTE', 'EMPRUNTE.' . $this->id . '=EXEMPLAIRE.' . $this->id, 'left');
                $this->db->where('id_livre', $id);
                $this->db->where('(EMPRUNTE.date_emprunt=(select max(EMPRUNTE.date_emprunt) from EMPRUNTE where EMPRUNTE.id_exemplaire=EXEMPLAIRE.id_exemplaire) or EMPRUNTE.date_emprunt is NULL)');
                $query=$this->db->get();
                return $query->result_array();
        }

        // retourne un array pour construire les listes deroulentes.
        public function get_array()
        {
                $this->db->select($this->id. ', reference');
                $this->db->from($this->table);
                $query=$this->db->get();
                $datas=$query->result_array();
                $indexe = array();
                foreach ($datas as $data) {
                        $indexe[$data[$this->id]]=$data['reference'];
                }
                return $indexe;
        }

        public function set($data, $id_editeur)
        {       
                if (isset($data[$this->ref]))
                {
                        // update the entity and get the id. 
                        $this->db->update_batch($this->table, array($data), $this->ref);
                        $insert_id=$this->db->get_where($this->table, array($this->ref=>$data[$this->ref]))->row()->id_exemplaire;

                        // update the table editeur.
                        $this->db->set('id_editeur', $id_editeur);
                        $this->db->where($this->id, $insert_id);
                        $this->db->update('EDITE');
                } else
                {
                        // creat a new entity exemplaire.
                        $data[$this->ref]=$this->get_new_ref();
                        $this->db->insert($this->table, $data);
                        $insert_id=$this->db->insert_id();
                        $this->db->insert('EDITE', array(
                                'id_editeur'=>$id_editeur,
                                'id_exemplaire'=>$insert_id
                                ));
                }
        }

        public function delete($ref)
        {
                $id_exemplaire = $this->db->get_where($this->table, array($this->ref=>$ref))->row()->id_exemplaire;
                $query = $this->db->get_where('EMPRUNTE', array($this->id=>$id_exemplaire));
                if ($query->num_rows())
                {
                        redirect('exemplaire/error/supprime/'.$ref);
                } else
                {
                        $this->db->delete('EDITE', array($this->id=>$id_exemplaire));
                        $this->db->delete($this->table, array($this->ref=>$ref));
                }
        }

        public function rendre($id_emprunt)
        {
                $this->db->select('*');
                $this->db->from('EMPRUNTE');
                $this->db->where('id_emprunt', $id_emprunt);
                $query = $this->db->get();
                $emprunt = $query->row();
                if (is_null($emprunt->date_rendu))
                {
                        $emprunt->date_rendu=date('Y-m-d G:i:s');
                        $this->db->replace('EMPRUNTE', $emprunt);
                        return True;
                }
                return False;
        }
}