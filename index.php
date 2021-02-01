<?php
class DomDocumentTagRemover
{
       private $iterator;
       private $dom = [];

       public function __construct(string $DocumentName)
       {
              try
              {
                     $this->dom = file($DocumentName, FILE_SKIP_EMPTY_LINES);
                     $this->iterator = new DomIterator($this->dom);
              }
              catch(Exception $e)
              {
                 echo "Файл не может быть считан".$e.'</br>';
              }                     
       }

       public function removeTag(string $TagName)
       {
              $this->iterator->rewind();
              do{
                     $currentValue = str_replace(' ', '', $this->iterator->current());
                     $searchStatus = preg_match("(\<{$TagName})", $currentValue);
                     if($searchStatus)
                     {                       
                            echo "Удаляю значение:".htmlspecialchars($currentValue).'</br>';
                            array_splice($this->dom, $this->iterator->key(), 1);
                     }
                     else 
                     {
                            $this->iterator->next();
                     }
              } while($this->iterator->valid());
       }

       public function removeTagWithAttribute(string $TagName, string $AttributeName, string $AttributeValue)
       {
              $this->iterator->rewind();
              do{
                     $currentValue = str_replace(' ', '', $this->iterator->current());
                     $searchStatus = preg_match("(\<{$TagName})", $currentValue);
                     if($searchStatus)
                     {                       
                            $searchStatus1 = preg_match("({$AttributeName}=\"{$AttributeValue}\")", $currentValue);
                            if($searchStatus)
                            {                                 
                                   echo "Удаляю значение по атрибуту:".htmlspecialchars($currentValue).'</br>';
                                   array_splice($this->dom, $this->iterator->key(), 1);
                            }
                            else
                            {
                                   $this->iterator->next();   
                            }
                     }
                     else 
                     {
                            $this->iterator->next();
                     }
              } while($this->iterator->valid());
       }       

       public function saveFile(string $NewFileName=null)
       {             
              if(!is_null($NewFileName))
              {
                     file_put_contents($NewFileName, $this->dom);
              }
              else
              {
                     file_put_contents('temp.html', $this->dom);
              }
       }
}

class DomIterator implements Iterator
{
       private $position = 0;
       private $dom = [];
   
       public function __construct(array &$Dom) {
           $this->position = 0;
           $this->dom = &$Dom;
       }
   
       public function rewind() {
           $this->position = 0;
       }
   
       public function current() {
           return $this->dom[$this->position];
       }
   
       public function key() {
           return $this->position;
       }
   
       public function next() {
           ++$this->position;
       }
   
       public function valid() {
           return isset($this->dom[$this->position]);
       }
}

//$t1 = str_replace(' ', '', htmlspecialchars('<meta name="description" content="Автосервис Автошуши в Шушарах автозапчасти Пушкин СТО">'));
//////echo htmlspecialchars($t1).'</br>';
//$toFind = 'meta';
//$test1 = preg_match( "(\&lt{$tagname})", $t1);
//echo $t1.'</br>';
//var_dump($test1);
//echo $test1.'</br>';


$domr = new DomDocumentTagRemover('test.html');
$domr->removeTag('title');
$domr->removeTagWithAttribute('meta','name','viewport');
$domr->removeTagWithAttribute('meta','name','keywords');
$domr->removeTagWithAttribute('meta','name','description');
$domr->saveFile('test1.html')
?>