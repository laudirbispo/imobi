<SECTION CLASS="container-fluid">


        <?php 
            if( empty($_GET['pageHelp']) or !isset($_GET['pageHelp'])  )
            {
                include ($_SERVER['DOCUMENT_ROOT'].'/app/support/pages/starting.php');
            } 
            else 
            {
                require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
                $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($HTMLPurifier_config);
                $getPage = $purifier->purify($_GET['pageHelp']);

                $base_path = $_SERVER['DOCUMENT_ROOT'].'/app/support/pages/';
                if (!file_exists($base_path.$getPage.'.php'))
                {
                    $page = $_SERVER['DOCUMENT_ROOT'].'/app/views/404.php' ;
                }
                else
                {
                    $page = $base_path.$getPage.'.php' ;  
                }

                require_once($page);
            }               
        ?>
      

</SECTION>
