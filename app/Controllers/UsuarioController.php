<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class UsuarioController extends \Com\Daw2\Core\BaseController {
       
  
    function mostrarUsuarios(){
        $data = [];
        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/usuarios';
        
        $modelo = new \Com\Daw2\Models\UsuarioModel();
        $data['usuarios'] = $modelo->mostrarUsuarios();                
        
        $this->view->showViews(array('templates/header.view.php', 'usuario.view.php', 'templates/footer.view.php'), $data);
    }
}