<?php

class Upload
{
  private $File;
  private $Name;
  private $Send;

  /**IMAGE UPLOAD*/
  private $Width;
  private $Image;

  /** RESULTSET */
  private $Result;
  private $Error;

  /**DIRETORIOS*/
  private $Folder;
  private static $BaseDir;


  /**METODOS*/

  function __construct($BaseDir = NULL)
  {
    self::$BaseDir = ( (string) $BaseDir ? $BaseDir : '../uploads/');

    if(!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
      mkdir(self::$BaseDir, 0777);
    endif;
  }

  /*UPLOAD DE IMAGENS**/
  public function Image(array $Image, $Name = null, $Width = null, $Folder = null)
  {
    $this->File = $Image;
    $this->Name = ( (string) $Name ? $Name : substr($Image['name'],0,strrpos($Image['name'], '.' )));
    $this->Width = ( (int) $Width ? $Width : 1024 );
    $this->Folder = ( (string) $Folder ? $Folder : 'images' );

    $this->CheckFolder($this->Folder);
    $this->setFileName();
    $this->UploadImage();
  }

  //upload de file
  public function File(array $File, $Name = null, $Folder = null, $MaxFileSize = null)
  {
        $this->File = $File;
        $this->Name = ( (string) $Name ? $Name : substr($File['name'], 0, strrpos($File['name'], '.')) );
        $this->Folder = ( (string) $Folder ? $Folder : 'files' );
        $MaxFileSize = ( (int) $MaxFileSize ? $MaxFileSize : 10 );

        $FileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf'
        ];

        if($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
          $this->Result = false;
          $this->Error = 'Arquivo Muito Grande, Tamanho Máximo permito de {$MaxFileSize} mb';
        elseif(!in_array($this->File['type'], $FileAccept)):
          $this->Result = false;
          $this->Error = 'tipo de arquivo nao suportado, Envie .PDF OU DOCX!';
        else:
          $this->CheckFolder($this->Folder);
          $this->setFileName();
          $this->MoveFile();

        endif;

  }

  public function Media(array $Media, $Name = null, $Folder = null, $MaxMediaSize = null)
  {
    $this->File = $Media;
    $this->Name = ( (string) $Name ? $Name : substr($Media['name'], 0, strpos($Media['name'], '.')));
    $this->Folder = ( (string) $Folder ? $Folder : 'medias' );
    $MaxMediaSize = ( (int) $MaxMediaSize ? $MaxMediaSize : 20 );

    $FileAccept = [
        'audio/mp3',
        'audio/mp4'
    ];

    if($this->File['size'] > ($MaxMediaSize * (1024 * 1024))):
      $this->Result = false;
      $this->Error = 'MIDIA Muito Grande, Tamanho Máximo permito de {$MaxMediaSize} mb';
    elseif(!in_array($this->File['type'], $FileAccept)):
      $this->Result = false;
      $this->Error = 'tipo de arquivo nao suportado, Envie .MP3 OU MP4!';
    else:
      $this->CheckFolder($this->Folder);
      $this->setFileName();
      $this->MoveFile();

    endif;



  }


  public function getError()
  {
    return $this->Error;
  }

  public function getResult()
  {
    return $this->Result;
  }


  //PRIVATES

  private function CheckFolder($Folder)
  {
    list($y, $m) = explode('/', date('Y/m'));
    $this->CreateFolder("{$Folder}");
    $this->CreateFolder("{$Folder}/{$y}");
    $this->CreateFolder("{$Folder}/{$y}/{$m}");
    $this->Send = "{$Folder}/{$y}/{$m}/";
  }

  private function CreateFolder($Folder)
  {
    if(!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)):
      mkdir(self::$BaseDir . $Folder, 0777);
    endif;
  }

  private function setFileName()
  {
    $FileName = Check::Name($this->Name) . strrchr($this->File['name'], '.');

    if(file_exists(self::$BaseDir . $this->Send . $FileName)):
      $FileName = Check::Name($this->Name) . '-' . time() . strrchr($this->File['name'], '.');
    endif;
    $this->Name = $FileName;
  }

  private function UploadImage()
  {
    switch ($this->File['type']):
      case 'image/jpg':
      case 'image/jpeg':
      case 'image/pjpeg':
        $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
        break;
      case 'image/png':
      case 'image/x-png':
        $this->Image = imagecreatefrompng($this->File['tmp_name']);
        break;
    endswitch;

    if(!$this->Image):
      $this->Result = false;
      $this->Error = "Tipo de arquivo Invalido Envie JPEG OU PNG";
    else:
      $x = imagesx($this->Image);
      $y = imagesy($this->Image);
      $ImageX = ($this->Width < $x ? $this->Width : $x);
      $ImageH = ($ImageX * $y) / $x;

    $NewImage = imagecreatetruecolor($ImageX, $ImageH);
    imagealphablending($NewImage, false);
    imagesavealpha($NewImage, true);
    imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

      switch($this->File['type']):
        case 'image/jpg':
        case 'image/jpeg':
        case 'image/pjpeg':
          imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
          break;
        case 'image/png':
        case 'image/x-png':
          imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
          break;
      endswitch;

      if(!$NewImage):
        $this->Result = false;
        $this->Error = 'Tipo de arquivo Invalido, Escolha uma Imagem no Formato JPG OU PNG';
      else:
        $this->Result = $this->Send . $this->Name;
        $this->Error = null;
      endif;

      imagedestroy($this->Image);
      imagedestroy($NewImage);
    endif;
  }

  private function MoveFile()
  {
    if(move_uploaded_file($this->File['tmp_name'], self::$BaseDir . $this->Send . $this->Name )):
      $this->Result = $this->Send . $this->Name;
      $this->Error = null;
    else:
      $this->Result = false;
      $this->Error = 'Erro ao Mover o arquivo Favor Tente mais Tarde';
    endif;
  }


}
