<?php
namespace app\controls;

class securePage 
{ 
    
    private $base_path;
    
    private $allowed_pages = array ('home', 'support', 'faq', 'feedback', 'access_denied', '404', 'news', 'add_news', 'promotions', 'add_promotion', 'scrapbook', 'advertising', 'gallery', 'agenda', 'suervey', 'news', 'add_news', 'edit_news', 'top', 'images_album', 'my_account', 'vehicles', 'add_cars', 'edit_cars', 'images_car', 'new_user', 'users', 'products', 'add_products', 'edit_products', 'settings_products', 'user_edit_perms', 'admin_redefine_password', 'slides', 'clients', 'add_clients', 'edit_clients', 'settings_vehicles', 'add_motorcycles', 'images_motorcycle', 'edit_motorcycles', 'statistics_vehicles', 'properties', 'add_properties', 'edit_properties', 'images_properties', 'settings_properties', 'preview_property', 'settings_properties', 'contracts', 'add_contracts', 'new_template_contracts', 'edit_template_contracts', 'contracts_details', 'teste', 'site_settings', 'system_settings');
    
    public function __construct($base_path = null)
    {
        $this->base_path = ($base_path === null) ? '' : $base_path ;
    }

    public function urlInclude($getPage)
    {
        if (!file_exists($this->base_path.$getPage.'.php'))
        {
            $page = $_SERVER['DOCUMENT_ROOT'].'/app/views/404.php' ;
        }
        else if (in_array($getPage, $this->allowed_pages))
        {
            $page = $this->base_path.$getPage.'.php' ;   
        }
        else
        {
            $page = $_SERVER['DOCUMENT_ROOT'].'/app/views/404.php' ;
        }
        
        include $page;
    
    }
    
    
    public function getVars($url, $get)
    { 
        // params default/ 
        $params = array(
            "limit" => "25",
            "select" => "all",
            "order" => "date-desc",
            "pagination" => "0",
        );
        
        $arr = parse_url($url);
        $dominio = $arr['path'];
        $query = (isset($arr['query'])) ? $arr['query'] : null ;
          
        $query = explode('&', $query);

        foreach($query as $vars)
        {
            $vars = explode('=', $vars);
            @$params[$vars[0]] = $vars[1];
        }
        
        $get = explode('&', $get);
        foreach($get as $vg)
        {
            $values = explode('=', $vg);
            @$params[$values[0]] = $values[1];  
        }

        ksort($params);
        return $dominio.'?'.http_build_query($params);

    }

    
    

}