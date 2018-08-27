<?php
namespace PhpToTypescript\Typescript;
use PhpParser\Node\Param;

    class Property_
    {
        /** @var string */
        public $name;
        /** @var string */
        public $type;
        /** @var bool */
        public $isOptional;
        public function __construct($name, $type = "any", $isOptional = false)
        {
            $this->name = $name;
            $this->type = $type;
            $this->isOptional = $isOptional;
        }

        public function __toString()
        {
            $optional = $this->isOptional? '?': '';
            return "{$this->name}{$optional}: {$this->type}";
        }
    }