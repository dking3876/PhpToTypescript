<?php 
namespace PhpToTypescript\Typescript;
class Method_{
    /** @var string  */
    public $name;
    /**
     * @var Param[]
     */
    public $params = [];

    /** @var string */
    public $return;
    public function __construct(string $name, $params, $return = null)
    {
        $this->name = $name;
        $this->params = $params;
        $this->return = $return;
    }

    public function __toString()
    {
        $result = "abstract {$this->name}(";
        $result .= implode(",", array_map(function ($p){
            $type = "";
            if(isset($p->type)){
                $type = $p->type->name;
            }
            return  " " .(string)$p->var->name.':'.$type;}, $this->params)
        );
        if($this->return) {
            $result .= "):{$this->return}";
        }

        return $result;
    }
}