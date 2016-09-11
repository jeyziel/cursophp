<?php

/**
 * Class Conn[CONEXAO]
 * classe abstrata de conexao, Padrao SIGLETON;
 * Retonar um objeto PDO pelo método estatico getConn();
 * @Copyright (c) 2016,Jeyziel Gama.
 */
class Conn{
    private static $HOST = HOST;
    private static $User = USER;
    private static $Pass = PASS;
    private static $Dbsa = DBSA;
    /** @var PDO */
    private static $Connect = null;


    /**
     * conecta com o banco de dados com o pattern sigleton.
     * retorna um objeto PDO;
     */
    private static function Conectar(){

        try{
            if(self::$Connect == NULL):
                $dns = 'mysql:host=' . self::$HOST . ';dbname=' . self::$Dbsa ;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
               self::$Connect = new PDO($dns, self::$User, self::$Pass, $options);
            endif;

        }catch (PDOException $e){
            PHPErro($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine());
            die;
        }
        //tipo de erros q o pdo ai trabalhar
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return self::$Connect;

    }

    /** RETORNA UM OBJETO SINGLETON PATTERN. */
    public static function getConn(){
        return self::Conectar();
    }



}
