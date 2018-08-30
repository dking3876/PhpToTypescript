<?php 
namespace PhpToTypescript;
ini_set('display_errors', 1);



use PhpParser;
use PhpParser\Node;
class Converter{

    private $path;
    private $masterPathParts;
    private $files;
    private $root;
    private static $instance = null;
    private function __construct($pathToModels)
    {
        $this->path = $pathToModels;
        $this->masterPathParts = explode(DIRECTORY_SEPARATOR, $this->path);
        $this->files = $this->getDirContents($this->path);
    }

    public static function convert($pathToModels){
        $instance = new self($pathToModels);
        $t = debug_backtrace();
        $rootParts = explode(DIRECTORY_SEPARATOR, $t[0]['file']);
        array_pop($rootParts);
        $instance->root = $root = implode(DIRECTORY_SEPARATOR, $rootParts);
        $instance->readAndWriteNewFiles();

    }

    /**
     *
     */
    private function readAndWriteNewFiles()
    {
        foreach ($this->files as $file) {
            $result = $this->convertToTypescript($file['path']);
            $my_file = $file['basename'];
            $pathParts = array_diff($file['pathParts'], $this->masterPathParts);
            array_pop($pathParts);
            $newPath = implode(DIRECTORY_SEPARATOR, $pathParts);
            //   var_dump($newPath);
            try {
                if (!\file_exists($this->root.'/convert/'. $newPath)) {
                    mkdir($this->root.'/convert/' . $newPath, null, true);
                }

            } catch (\Exception $e) {

            }
            $handle = fopen($this->root.'/convert/' . $newPath . DIRECTORY_SEPARATOR . $result['className'] . '.ts', 'w') or die('Cannot open file:  ' . $my_file);
            fwrite($handle, $result['code']);
        }
    }
    private function getDirContents($dir, &$results = array()){

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

    private function convertToTypescript($filePath){
        $parser = new \PhpParser\Parser\Php7(new PhpParser\Lexer\Emulative);
        $traverser = new \PhpParser\NodeTraverser;
        $visitor = new Visitor;
        $traverser->addVisitor($visitor);
    
        try {
            // @todo Get files from a folder recursively
            var_dump($filePath);
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