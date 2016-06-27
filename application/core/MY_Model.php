<?php

class MY_Model extends CI_Model {

		public function __construct()
        {
                $this->table = '';
                $this->id='';
                $this->ref='';
        }


        public function get_new_ref()
        {
                $ref = substr(md5(date('Y-m-d H:i:s')), -6);
                while($this->db->get_where($this->table, array($this->ref=>$ref))->num_rows) $ref = substr(md5(date()), -6);
                return $ref; 
        }
}