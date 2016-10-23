<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Buildamenu Controller
 *
 * This controller displays the list of current menu items in the
 * application and also allows the user to create new menu items
 * 
 */
class Buildamenu extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
    } 

    /*
     * Listing of menu items
     */
    function index($menu_group='')
    {
        $data = array();
        $data['current_group'] = 'admin';        
        if ($menu_group) {
            $this->Menu_model->where('menu_group', $menu_group);
            $data['current_group'] = $menu_group;
        }
        $this->Menu_model->order_by('level,menu_order');
        $data['menuitems'] = $this->Menu_model->as_array()->find_all();

        Template::set($data);
        Template::render();
    }

    /*
     * Adding a new menu item
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('menu_group','Menu Group','required|max_length[20]');
		$this->form_validation->set_rules('parent_id','Parent Id','required');
		$this->form_validation->set_rules('title','Title','required|max_length[100]');
		$this->form_validation->set_rules('url','Url','required|max_length[100]');
		$this->form_validation->set_rules('menu_order','Menu Order','required');
		$this->form_validation->set_rules('icon','Icon','max_length[100]');
		$this->form_validation->set_rules('description','Description','max_length[150]');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'menu_group' => $this->input->post('menu_group'),
				'parent_id' => $this->input->post('parent_id'),
				'title' => $this->input->post('title'),
				'url' => $this->input->post('url'),
				'menu_order' => $this->input->post('menu_order'),
				'status' => $this->input->post('status'),
				'level' => $this->input->post('level'),
				'icon' => $this->input->post('icon'),
				'description' => $this->input->post('description'),
            );
            
            $menuitem_id = $this->Menu_model->insert($params);
            redirect('buildamenu/index');
        }
        $data = array('menuitem'=>array());
        if (!isset($_POST['menu_group']))
            $data['menuitem'] = array('menu_group'=>'admin', 'parent_id' =>0, 'menu_order' => 1, 'status'=>1,'level'=>0);
        Template::set($data);
        Template::render();
    }  

    /*
     * Editing a menu item
     */
    function edit($id)
    {   
        // check if the menu item exists before trying to edit it
        $menuitem = $this->Menu_model->as_array()->find($id);
        
        if(!isset($menuitem['id']))
        {
            show_error('The menu item you are trying to edit does not exist.');
            redirect('buildamenu/index');
        }
        
        $this->load->library('form_validation');

        $this->form_validation->set_rules('menu_group','Menu Group','required|max_length[20]');
        $this->form_validation->set_rules('parent_id','Parent Id','required');
        $this->form_validation->set_rules('title','Title','required|max_length[100]');
        $this->form_validation->set_rules('url','Url','required|max_length[100]');
        $this->form_validation->set_rules('menu_order','Menu Order','required');
        $this->form_validation->set_rules('icon','Icon','max_length[100]');
        $this->form_validation->set_rules('description','Description','max_length[150]');
    
        if($this->form_validation->run())     
        {   
            $params = array(
                'menu_group' => $this->input->post('menu_group'),
                'parent_id' => $this->input->post('parent_id'),
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'menu_order' => $this->input->post('menu_order'),
                'status' => $this->input->post('status'),
                'level' => $this->input->post('level'),
                'icon' => $this->input->post('icon'),
                'description' => $this->input->post('description'),
            );

            $this->Menu_model->update($id,$params);            
            redirect('buildamenu/index');
        }
        $data['menuitem'] = $menuitem;
        Template::set($data);
        Template::render();
    }


    /*
     * Deleting menu item
     */
    function remove($id)
    {
        // check if the menu item exists
        $menuitem = $this->Menu_model->find($id);
        
        if(!isset($menuitem['id']))
        {
            show_error('The menu item you are trying to edit does not exist.');
            redirect('buildamenu/index');
        }
        
        $this->Menu_model->delete($id);
        redirect('buildamenu/index');
    }
    
}