<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Created By: Rian Reski A
* 2019
*/
	
	function _sidebar_app()
	{
		$ci =& get_instance();
		$output_menu='';
		$level = $ci->session->userdata('tpp_level');
		if ($level) {
			$ci->load->model('M_acl');
			$sql = $ci->M_acl->_acl($level);
			foreach ($sql as $row) {
				$sub_data['id'] 		= $row->id; 
				$sub_data['title'] 		= $row->title;
				$sub_data['icon'] 		= $row->icon;
				$sub_data['url'] 		= $row->url;
				$sub_data['type'] 		= $row->type;
				$sub_data['parent'] 	= $row->parent; 
				$data[] = $sub_data;
			}

			foreach($data as $key => &$value){
			 $output[$value["id"]] = &$value;
			}

			foreach($data as $key => &$value){
			 if($value["parent"] && isset($output[$value["parent"]]))
			 {
			  $output[$value["parent"]]["child"][] = &$value;
			 }
			}
			foreach($data as $key => &$value){
			 if($value["parent"] && isset($output[$value["parent"]]))
			 {
			  unset($data[$key]);
			 }
			}

	        foreach($data as $m1 => $r1){ 	
				$md_none = '';
				if ($r1['type'] == "class-lg") {
						$md_none = "d-md-none";
				}
		          if(empty($r1['child'])){
		            $output_menu.='<li class="nav-item '.$md_none.'">
		                <a href="'.base_url($r1['url']).'"  class="nav-link">             
		                    <i class="'.$r1['icon'].'"></i> 
		                    <span>'.$r1['title'].'</span>
		                </a></li>';
		          }else{
		              $output_menu.='<li class="treeview-menu nav-item nav-item-submenu">
		                              <a href="#" class="nav-link">
		                                  <i class="'.$r1['icon'].'"></i> 
		                                  <span>'.$r1['title'].'</span>
		                              </a>';
			            $output_menu.='<ul class="nav nav-group-sub" data-submenu-title="'.$r1['title'].'">';

			            foreach($r1['child'] as $m2 => $r2){
			                $output_menu.='<li class="nav-item">
			                                  <a href="'.base_url($r2['url']).'" class="nav-link">'.$r2['title'].'</a>
			                              </li>';
			            }
		            $output_menu.='</ul>';

		            $output_menu.='</li>';
		          } 
	      	}
	        
		}
		
        return $output_menu;
	}

/* End of file app_helper.php */
/* Location: ./application/helpers/app_helper.php */