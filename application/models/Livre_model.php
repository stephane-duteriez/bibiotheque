<?php
class Livre_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'LIVRE';
                $this->id='id_livre';
        }

        public function get($id=NULL)
        {
                if ($id)
                {
                        $this->db->select($this->table . '.*, AUTEUR.nom, AUTEUR.prenom, APPARTIENT.id_categorie');
                        $this->db->from($this->table);
                        $this->db->join('AUTEUR', 'LIVRE.id_auteur' . '=' . 'AUTEUR.id_auteur', 'inner');
                        $this->db->join('APPARTIENT', 'APPARTIENT.id_livre=LIVRE.id_livre', 'left');
                        $this->db->where('LIVRE.'.$this->id, $id);
                        $query=$this->db->get();
                        return $query->row();
                } else
                {
                        $this->db->select($this->table . '.* , AUTEUR.nom, AUTEUR.prenom');
                        $this->db->from($this->table);
                        $this->db->join('AUTEUR', 'LIVRE.id_auteur' . '=' . 'AUTEUR.id_auteur', 'inner');
                        $query=$this->db->get();
                        return $query->result_array();
                }
        }

        // retourne un array pour construire les listes deroulentes.
        public function get_array()
        {
                $this->db->select($this->id. ', titre, ISBN');
                $this->db->from($this->table);
                $query=$this->db->get();
                $livres=$query->result_array();
                $indexe = array();
                foreach ($livres as $livre) {
                        $indexe[$livre[$this->id]]=$livre['titre']. ' ' .$livre['ISBN'];
                }
                return $indexe;
        }

        public function set($data, $categorie)
        {       
                if (isset($data[$this->id]))
                {
                        $this->db->update_batch($this->table, array($data), $this->id);
                        $insert_id=$data[$this->id];
                        $this->db->select('*');
                        $this->db->from('APPARTIENT');
                        $this->db->where($this->id, $data[$this->id]);
                        $query=$this->db->get();
                        if ($query->num_rows())
                        {
                                $this->db->set('id_categorie', $categorie);
                                $this->db->where($this->id, $data[$this->id]);
                                $this->db->update('APPARTIENT');
                        } else
                        {
                                $this->db->insert('APPARTIENT', array(
                                'id_categorie'=>$categorie,
                                'id_livre'=>$insert_id
                                ));
                        }
                } else
                {
                        $this->db->insert($this->table, $data);
                        $insert_id=$this->db->insert_id();
                        $this->db->insert('APPARTIENT', array(
                                'id_categorie'=>$categorie,
                                'id_livre'=>$insert_id
                                ));
                }
        }

        public function delete($id)
        {
                $query = $this->db->get_where('EXEMPLAIRE', array($this->id=>$id));
                if ($query->num_rows())
                {
                        redirect('livre/error/supprime/'.$id);
                } else
                {
                        $this->db->delete('APPARTIENT', array($this->id=>$id));
                        $this->db->delete($this->table, array($this->id=>$id));
                }
        }

        public function seek($indice, $option)
        // recherche des livres contenant 'indice' dans l'un des champs.
        {
                $this->db->select($this->table . '.*, AUTEUR.nom, AUTEUR.prenom, CATEGORIE.intitule');
                $this->db->from($this->table);
                $this->db->join('AUTEUR', 'LIVRE.id_auteur' . '=' . 'AUTEUR.id_auteur', 'inner');
                $this->db->join('APPARTIENT', 'APPARTIENT.id_livre=LIVRE.id_livre', 'left');
                $this->db->join('CATEGORIE', 'APPARTIENT.id_categorie=CATEGORIE.id_categorie');
                if ($option % 2>=1) $this->db->like('LIVRE.titre', $indice);
                if ($option % 4>=2) $this->db->or_like('LIVRE.resume', $indice);
                if ($option % 8>=4) $this->db->or_like('AUTEUR.nom', $indice);
                if ($option % 16>=8) $this->db->or_like('LIVRE.serie', $indice);
                $query=$this->db->get();
                return $query->result_array();
        }
}