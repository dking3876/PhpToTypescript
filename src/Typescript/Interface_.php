<?php 
namespace PhpToTypescript\Typescript;
use PhpParser\Node\Param;
class Interface_
    {
        /** @var string */
        public $name;
        /** @var Property_[] */
        public $properties = [];
        /**
         * @var Method_[]
         */
        public $methods = [];
        public function __construct($name)
        {
            $this->name = $name;

        }

        public function __toString()
        {
            $result = "export abstract class _{$this->name} {\n";
            $result .= implode(";\n", array_map(function ($p) { return "  " . (string)$p;}, $this->properties));
            $result .= ";\n";
            $result .= implode(";\n", array_map(function ($p) { return "  " . (string)$p;}, $this->methods));
            $result .= "\n}";
            return $result;
        }
    }