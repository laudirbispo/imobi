<?php
namespace app\controls;

use config\connect_db;

/** Control user activity
 * 
 * Tipos de privacidade: 
 * @public - visível a todos os usuários
 * @private - visível aos administradores e ao próprio usuário
 * @justme - visível somente ao próprio usuário
 *
 */

class activityRecord
{
    private $con = null;
    
    public function __construct()
    {
        $con_db = new connect_db();
        $this->con = $con_db->connect();
    }
    
    public function record ($user_id, $privacy, $activity, $activity_body, $activity_link)
    {
        
        $insert = $this->con->prepare('INSERT INTO activity_record (user_id, privacy, activity, activity_body, activity_link) VALUES (?, ?, ?, ?, ?) ');
        $insert->bind_param('issss', $user_id, $privacy, $activity, $activity_body, $activity_link);
        $insert->execute();
        $rows = $insert->affected_rows;
        $insert->close();
        
        return ($insert and $rows > 0) ? true : false ;
    }
    
    // terminar classe

}
