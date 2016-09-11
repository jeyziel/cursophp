<?php

/**
 * Class Update
 * Classe responsavel pela atualizacao do bancos de dados.
 */

class Update extends Conn{
    private $Tabela;
    private $Dados;
    private $Termos;
    private $Places;
    private $Result;
    private $Update;
    private $Conn;



    //update tabela set(nome =:nome, idade=:idade) where id=:id
    public function ExeUpdate($Tabela,array $Dados, $Termos, $ParseString){
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = (string) $Termos;

        parse_str($ParseString,$this->Places);
        $this->getSyntax();
        $this->Execute();

    }

    public function getResult(){
        return $this->Result;
    }

    public function getRowCount(){
        return $this->Update->rowCount();

    }

    public function setPlaces($ParseString){
        parse_str($ParseString,$this->Places);
        $this->getSyntax();
        $this->Execute();
    }


    /** metodos privados*/

    //obtem o pdo e prepara a QUERY
    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Update = $this->Conn->prepare($this->Update);
    }

    private function getSyntax(){
        foreach($this->Dados as $Key => $Values):
            $Places[] = $Key . ' = :' . $Key;
        endforeach;

        $Places = implode(', ',$Places);
        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";

//        $a = array_merge($this->Dados,$this->Places);
//        var_dump($a);

    }

    private function Execute(){
        $this->Connect();

        try{
            $this->Update->execute(array_merge($this->Dados,$this->Places));
            $this->Result = true;

        }catch (PDOException $e){
            $this->Result = NULL;
            WSErro("<b>Erro ao atualizar:{$e->getMessage()}</b>",$e->getCode());

        }

    }







}
