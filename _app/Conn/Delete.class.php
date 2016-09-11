<?php

/**
 * Class Delete
 * classe responsável por remover dados nos banco.
 * DELETE FROM `ws_siteviews_agent` WHERE`agent_id`
 *
 */
class Delete extends Conn{

    private $Delete;
    private $Tabela;
    private $Places;
    private $Termos;
    private $Conn;
    private $Result;


    public function ExeDelete($Tabela,$Termos,$ParseString){
        $this->Tabela = $Tabela;
        $this->Termos = $Termos;
        parse_str($ParseString,$this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    public function getResult(){
        return $this->Result;
    }

    public function getRowCount(){
        return $this->Delete->rowCount();
    }

    public function setPlaces($ParseString){
        parse_str($ParseString,$this->Places);
        $this->getSyntax();
        $this->Execute();

    }


    /** metodos privados */
    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    private function getSyntax(){
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    private function Execute(){
        $this->Connect();

        try{
            $this->Delete->execute($this->Places);
            $this->Result = true;
        }catch (PDOException $e){
            $this->Result = null;
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());

        }
    }









}
