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
			$menu = $ci->M_acl->_acl($level);

			//index elements by id
			foreach ($menu as $item) {
			    $item->subs = array();
			    $indexedItems[$item->id] = (object) $item;
			}
			//assign to parent
			$topLevel = array();
			foreach ($indexedItems as $item) {
			    if ($item->parent == 0) {
			        $topLevel[] = $item;
			    } else {
			        $indexedItems[$item->parent]->subs[] = $item;
			    }
			}

			$output_menu = renderMenuSidebar($topLevel);
	        
		}
		
        return $output_menu;
	}

	function renderMenuSidebar($items)
	{
       $render = '';
	    foreach ($items as $item) {
	        if (!empty($item->subs)) {
	        	$render.='<li class="treeview-menu nav-item nav-item-submenu">
                              <a href="#" class="nav-link">
                                  <i class="'.$item->icon.'"></i> 
                                  <span>'.$item->title.'</span>
                              </a>';
	            $render.='<ul class="nav nav-group-sub" data-submenu-title="'.$item->title.'">';
		        $render.= renderMenuSidebar($item->subs);
		        $render.='</ul></li>';
	        }else {
	        	if (empty($item->icon)) {
		        	$render.='<li class="nav-item list-feed list-feed-solid">
	                                  <a href="'.base_url($item->url).'" class="nav-link">
	                                  <div class="list-feed-item pl-3">'.$item->title.'</div></a>
	                              </li>';
		        }else {
		        	$render.='<li class="nav-item ">
			                <a href="'.base_url($item->url).'"  class="nav-link">             
			                    <i class="'.$item->icon.'"></i> 
			                    <span>'.$item->title.'</span>
			                </a></li>';
		        }
	        }
	    }

	    return $render;
		      	
	}

	//recursive function
	function renderMenu($items) {
	    $render = '<ul>';

	    foreach ($items as $item) {
	        $render .= '<li>' . $item->title;
	        if (!empty($item->subs)) {
	            $render .= renderMenu($item->subs);
	        }
	        $render .= '</li>';
	    }

	    return $render . '</ul>';
	}

/* End of file app_helper.php */
/* Location: ./application/helpers/app_helper.php */