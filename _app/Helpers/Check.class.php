<?php

/**
 * Classe responsavel por validar e manipular dados no sistema
 * resolucao de escopo
 * Class Check
 */
class Check
{
    private static $Data;
    private static $Format;

    public static function Email($Email)
    {
        self::$Data = (string) $Email;
        self::$Format = '/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if(preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }

    public static function Name($Name)
    {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----','----','---','--'),'-',self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }

   public static function Data($Data)
   {
       self::$Format = explode(' ', $Data);
       self::$Data = explode('/',self::$Format[0]);

       if(empty(self::$Format['1'])):
           self::$Format[1] = date('H:i:s');
       endif;

       self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1] ;

       return self::$Data;

   }

   public static function Words($String, $Limite, $Pointer = null)
   {
       self::$Data = strip_tags(trim($String));
       self::$Format = (int) $Limite;
       $ArrWords = explode(' ',self::$Data);
       $NumWords = count($ArrWords);
       $NewWords = implode(' ',array_slice($ArrWords,0,self::$Format));
       $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer);
       $Result = (self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data);

       return $Result;

//       var_dump($ArrWords,$NewWords,$NumWords);
   }

   public static function CatByName($CategoryByName)
   {
       $read = new read;
       $read->ExeRead('ws_categories',"WHERE category_name = :name","name={$CategoryByName}");
       if($read->getRowCount()):
           foreach ($read->getResult() as $Categorias):
               return $Categorias['category_id'];
           endforeach;
       else:
           echo "A categoria {$CategoryByName} nao foi encontrada";
           die;
       endif;
   }

   public static function userOnline()
   {
       $now = date('Y-m-d H:i:s');
       $DeleteUserOnline = new Delete;
       $DeleteUserOnline->ExeDelete('ws_siteviews_online', "WHERE online_endview < :now", "now={$now}");

       $readUserOnline = new Read;
       $readUserOnline->ExeRead('ws_siteviews_online');
       return $readUserOnline->getRowCount();
       

   }

   public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null)
   {
       self::$Data = 'uploads/' . $ImageUrl;

       if(file_exists(self::$Data) && !is_dir(self::$Data)):
           $Patch = HOME;
           $Imagem = self::$Data;
//           return $Patch . $Imagem;
           return "<img src=\"{$Patch}/tim.php?src={$Patch}/{$Imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\">";
       else:
           return false;
       endif;
   }

}
