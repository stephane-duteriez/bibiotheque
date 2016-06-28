<?php
class Livre_model extends MY_Model {

        public function __construct()
        {
                $this->load->database();
                $this->table = 'LIVRE';
                $this->id='id_livre';
                $this->ref='ref_livre';
        }

        public function get($ref=NULL)
        {
                if ($ref)
                {
                        $this->db->select($this->table . '.*, AUTEUR.nom, AUTEUR.prenom, APPARTIENT.id_categorie');
                        $this->db->from($this->table);
                        $this->db->join('AUTEUR', 'LIVRE.id_auteur' . '=' . 'AUTEUR.id_auteur', 'inner');
                        $this->db->join('APPARTIENT', 'APPARTIENT.id_livre=LIVRE.id_livre', 'left');
                        $this->db->where('LIVRE.'.$this->ref, $ref);
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
                if (isset($data[$this->ref]))
                {
                        // update the book and get the id of the book.
                        $this->db->update_batch($this->table, array($data), $this->ref);
                        $insert_id=$this->db->get_where($this->table, array($this->ref=>$data[$this->ref]))->row()->id_livre;

                        // change the categorie of the book.
                        $this->db->select('*');
                        $this->db->from('APPARTIENT');
                        $this->db->where($this->id, $insert_id);
                        $query=$this->db->get();
                        if ($query->num_rows())
                        {
                                $this->db->set('id_categorie', $categorie);
                                $this->db->where($this->id, $insert_id);
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
                        // there is no reference so we creat a new book.
                        $data[$this->ref]=$this->get_new_ref();
                        $this->db->insert($this->table, $data);
                        $insert_id=$this->db->insert_id();

                        // update the table that link with a categorie.
                        $this->db->insert('APPARTIENT', array(
                                'id_categorie'=>$categorie,
                                'id_livre'=>$insert_id
                                ));
                }
                return $data[$this->ref];
        }

        public function delete($ref)
        {
                // check if there are elemenet from the table 'exemaplaire' link to this 'livre'
                $this->db->select('*');
                $this->db->from($this->table);
                $this->db->join('EXEMPLAIRE', 'EXEMPLAIRE.'.$this->id . '=' . $this->table . '.' . $this->id);
                $this->db->where($this->ref, $ref);
                $query=$this->db->get();
                // if there are elements found refuse to supprime the 'livre'.
                if ($query->num_rows())
                {
                        redirect('livre/error/supprime/'.$id);
                } else
                {
                        $id_livre=$this->db->get_where($this->table, array($this->ref=>$ref))->row()->id_livre;
                        if (isset($id_livre))
                        {
                                // supprime the element from the table 'appartient' first.
                                $this->db->delete('APPARTIENT', array($this->id=>$id_livre));
                                $this->db->delete($this->table, array($this->id=>$id_livre));                                
                        }
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