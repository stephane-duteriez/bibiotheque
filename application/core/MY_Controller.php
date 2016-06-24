<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/community_auth/core/Auth_Controller.php';

class MY_Controller extends Auth_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function bt_submit()
	    {
	    		$data_submit=array(
							'name' => 'mysubmit',
							'value' => 'Valider',
							'class' => 'btn btn-default'
							);

				return form_submit($data_submit);
	    }

	    public function input_field($name, $control=NULL)
	    {
				$form_data=array(
					'name' => $name,
					'id' => $name,
					'class' => 'form-control'
					);
				if (isset($control)) $form_data['value'] = $control->$name;
				$form = form_input($form_data);
				$label_attr=array(
					'class' => 'col-md-2 control-label');
				$label = form_label(ucfirst($name), $name, $label_attr);
				return array(
						'label'=>$label,
						'form'=>$form);
	    }

	    public function textarea_field($name, $control=NULL)
	    {
				$form_data=array(
					'name' => $name,
					'id' => $name,
					'class' => 'form-control',
					'rows' => '4'
					);
				if (isset($control)) $form_data['value'] = $control->$name;
				$form = form_textarea($form_data);
				$label_attr=array(
					'class' => 'col-md-2 control-label');
				$label = form_label(ucfirst($name), $name, $label_attr);
				return array(
						'label'=>$label,
						'form'=>$form);
	    }

	    public function date_field($name, $str_name, $control=NULL)
	    {
				$form_data=array(
					'type' => 'date',
					'placeholder' => '2012-05-25',
					'name' => $name,
					'id' => $name,
					'class' => 'form-control'
					);
				if (isset($control)) $form_data['value'] = substr($control->$name,0,10);
				$form = form_input($form_data);
				$label_attr=array(
					'class' => 'col-md-2 control-label');
				$label = form_label($str_name, $name, $label_attr);
				return array(
						'label'=>$label,
						'form'=>$form);
	    }
	    public function select_field($name, $str_name, $list_value, $control=NULL)
	    {
				$form_data=array(
					'name' => $name,
					'id' => $name,
					'class' => 'form-control',
					'options' => $list_value
					);
				if (isset($control)) $form_data['selected'] = $control->$name;
				$form = form_dropdown($form_data);
				$label_attr=array(
					'class' => 'col-md-2 control-label');
				$label = form_label($str_name, $name, $label_attr);
				return array(
						'label'=>$label,
						'form'=>$form);
	    }
}