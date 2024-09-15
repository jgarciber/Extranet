<?php
// Funcion de autocarga de la clase al instanciarse
spl_autoload_register('autoload');

function autoload($clase,$dir=null){
    // Se indica la carpeta donde desarrollare el proyecto
    if (is_null($dir)){
        $dir = realpath(dirname(__FILE__));
    }
    // Recorrido recursivo para localizar las clases
    foreach (scandir($dir) as $file){
        // Si es una carpeta distinta del sistema buscar la clase en la carpeta
        if (is_dir($dir."/".$file) AND substr($file, 0, 1) != "."){
            autoload($clase, $dir."/".$file);
        }
        //Si es fichero y el nombre coincide con la clase a instanciar
        else if (is_file($dir."/".$file) AND $file == $clase.".php"){
            require_once($dir."/".$file);
        }
    }
}
