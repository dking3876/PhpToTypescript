<?php 
namespace PhpToTypescript;
ini_set('display_errors', 1);
$loader = require __dir__.'/../vendor/autoload.php'; //@todo add definitions to composer file for
$loader->addPsr4('kanban\\api\\', __DIR__.'/../app'); //define  where to look for this once i properly namespace it


use PhpParser;
use PhpParser\Node;
class Converter{

    function getDirContents($dir, &$results = array()){

        $directory = new \RecursiveDirectoryIterator($dir);
        foreach(new \RecursiveIteratorIterator($directory) as $filename => $current){

            $path = $current->getRealPath();
            $pathParts = explode(DIRECTORY_SEPARATOR, $path);
            // var_dump($pathParts);
            $name = $current-> getFilename();
            if($current->isFile()){
                $results[] = ['path'=> $path, 'name'=>$name, 'basename' => $current->getBasename(), 'pathParts'=>$pathParts];
            }
            
        }
        
        return $results;
    }

    function convertToTypescript($filePath){
        $parser = new \PhpParser\Parser\Php7(new PhpParser\Lexer\Emulative);
        $traverser = new \PhpParser\NodeTraverser;
        $visitor = new Visitor;
        $traverser->addVisitor($visitor);
    
        try {
            // @todo Get files from a folder recursively
           $code = file_get_contents($filePath);
    

            // parse
            $stmts = $parser->parse((string)$code);
    
            // traverse
            $stmts = $traverser->traverse($stmts);
    
            // echo "<pre><code>" . $visitor->getOutput() . "</code></pre>".$visitor->className;
            return ['code'=>$visitor->getOutput(), 'className'=>'_'.$visitor->className];
    
        } catch (\PhpParser\Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }
   
}
 ### Start of the main part
 $masterPath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'app/model';
 $masterPathParts = explode(DIRECTORY_SEPARATOR, $masterPath);
 // var_dump($masterPathParts);
 $files = \MyParser\getDirContents($masterPath);
 foreach($files as $file){
   $result = convertToTypescript($file['path']);
   $my_file = $file['basename'];
   $pathParts = array_diff($file['pathParts'],$masterPathParts);
   array_pop($pathParts);
   $newPath = implode(DIRECTORY_SEPARATOR, $pathParts );
     //   var_dump($newPath);
     try{
         if(!\file_exists(__DIR__.'/../convert/'.$newPath)){
             mkdir(__DIR__.'/../convert/'.$newPath, null, true);
         }
         
     }catch(\Exception $e){

     }
   $handle = fopen(__DIR__.'/../convert/'.$newPath.DIRECTORY_SEPARATOR.$result['className'].'.ts', 'w') or die('Cannot open file:  '.$my_file);
   fwrite($handle, $result['code']);
 }