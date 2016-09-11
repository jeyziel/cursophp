<?php

define('HOME','http://localhost/phpoo/08-analise-e-estat%C3%ADstica/');

//CONFIGURACAO DO SITE ##################################
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBSA','wsphp');

//AUTOLOAD DE CLASSES ###################################
function __autoload($Class){
    $cDir = ['Conn','Helpers'];
    $iDir = null;

//CONTRA BARRA INCLUI COMO ARQUIVO
    foreach ($cDir as $dirName):
        if(!$iDir && file_exists(__DIR__ . "\\{$dirName}\\{$Class}.class.php")&& !is_dir(__DIR__ . "\\{$dirName}\\{$Class}.class.php") ):
            include_once (__DIR__ . "\\{$dirName}\\{$Class}.class.php");
            $iDir = true;
        endif;
    endforeach;

    if(!$iDir):
        trigger_error("Erro ao incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}

// TRATAMENTO DE ERROS ###################################
// CSS constantes :: TRATAMENTOS DE ERROS
define('WS_ACCEPT','accept');
define('WS_INFOR','infor');
define('WS_ALERT','alert');
define('WS_ERROR','error');

//WSERRO :: Exibe erros lançados :: Front
function WSErro($ErrMsg, $ErroNo, $ErrDie = null){
    $CssClass = ($ErroNo == E_USER_NOTICE ? WS_INFOR : ($ErroNo == E_USER_WARNING ? WS_ALERT : ($ErroNo == E_USER_ERROR ? WS_ERROR : $ErroNo)));
    echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";

    if($ErrDie):
        die;
    endif;
}

//PHP ERRO ::personaliza o gatilho do php
function PHPErro($ErrNo,$ErrMsg,$ErrFile,$ErrLine){
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na linha: {$ErrLine} ::</b> {$ErrMsg} </br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
