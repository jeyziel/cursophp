<?php

/**
 * Class Create
 * Classe Responsavel Por cadastro Genéticos no bancos de dados;
 * @copyright  (c) 2016, Jeyziel Gama
 */

class Create extends Conn{
    private $Tabela;
    private $Dados;
    private $Result;
    private $Insert;

    /** @var PDOStatement */
    private $Create;

    /** @var PDO */
    private $Conn;

    /**
     * @param  STRING $Tabela = INFORME O NOME DA TABELA NO BANCO
     * @param array $Dados = INFORME UM ARRAY INTERATIVO (NOME DA COLUNA => VALOR)
     */
    public function ExeCreate($Tabela, array $Dados){
        $this->Tabela = $Tabela;
        $this->Dados = $Dados;
        $this->getSyntax();
        $this->Execute();
    }

    /** @return  false ou true para o cadastro*/
    public function getResult(){
        return $this->Result;
    }

    //metodos privados

    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Insert);
    }

    private function getSyntax(){
        $Fileds = implode(', ',array_keys($this->Dados));
        $Places = ':' . implode(', :',array_keys($this->Dados));
        $this->Insert = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES ({$Places})";

    }

    private function Execute(){
        $this->Connect();

        try{
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();

        }catch (PDOException $e){
            $this->Result = NULL;
            WSErro("<b>Erro ao cadastrar:{$e->getMessage()}</b>",$e->getCode());

        }

    }





}