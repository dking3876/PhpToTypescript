<?php

namespace PhpToTypescript;
use PhpToTypescript\Typescript;
/**
 * Class Visitor
 * @package PhpToTypescript
 */
class Visitor extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var bool
     */
    private $isActive = false;

    /** @var TypeScript\Interface_[] */
    private $output = [];

    /** @var TypeScript\Interface_ */
    public $currentInterface;

    /**
     * @var
     */
    public $className;


    /**
     * @param \PhpParser\Node $node
     * @return int|null|\PhpParser\Node|void
     */
    public function enterNode(\PhpParser\Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\Class_) {

            /** @var \PhpParser\Node\Stmt\Class_ $class */
            $class = $node;
            $this->className = $class->name;
            // If there is "@TypeScriptMe" in the class phpDoc, then ...
            if ($class->getDocComment() && strpos($class->getDocComment()->getText(), "@TypeScriptMe") !== false) {
                $this->isActive = true;
                $this->output[] = $this->currentInterface = new TypeScript\Interface_($class->name);
            }
        }
        if ($this->isActive) {
            if ($node instanceof \PhpParser\Node\Stmt\Property) {
                /** @var \PhpParser\Node\Stmt\Property $property */
                $property = $node;

                if ($property->isPublic()) {
                    $type = $this->parsePhpDocForProperty($property->getDocComment());
                    $isOptional = $this->parsePhpDocForOptional($property->getDocComment());
                    $this->currentInterface->properties[] = new TypeScript\Property_($property->props[0]->name, $type, $isOptional);
                }
            } else {
//                    var_dump($node );
            }
            if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
                /** @var \PhpParser\Node\Stmt\ClassMethod $method */
                $method = $node;

                if ($method->isPublic()) {
                    $params = $method->getParams();
                    $returnType = $method->getReturnType();
                    $this->currentInterface->methods[] = new TypeScript\Method_($method->name, $params, $returnType);
                }
            }
        }
    }

    /**
     * @param \PhpParser\Node $node
     * @return int|null|\PhpParser\Node|\PhpParser\Node[]|void
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\Class_) {
            $this->isActive = false;
        }
    }

    /**
     * @param \PhpParser\Comment|null $phpDoc
     * @return bool
     */
    private function parsePhpDocForOptional($phpDoc): bool
    {

        if ($phpDoc !== null) {
            if (strpos($phpDoc->getText(), 'optional') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \PhpParser\Comment|null $phpDoc
     */
    private function parsePhpDocForProperty($phpDoc)
    {
        $result = "any";

        if ($phpDoc !== null) {
            if (preg_match('/@var[ \t]+([a-z0-9\[\]]+)/i', $phpDoc->getText(), $matches)) {
                $t = trim($matches[1]);

                //does the match end with [] if so remove [] and make it Array<this>
                if (strpos($t, '[]') !== false) {
                    $t = substr($t, 0, -2);
                    $t = Helpers\TypeConverter::findTypescriptType($t);
                    $result = "Array<" . $t . ">";
                } else{
                    $result = Helpers\TypeConverter::findTypescriptType($t);
                }
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return implode("\n\n", array_map(function ($i) {
            return (string)$i;
        }, $this->output));
    }
}
    