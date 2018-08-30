<?php 
namespace PhpToTypescript\Helpers;
class TypeConverter{
    static $termMaps = [
        'mixed'     => 'any',
        'int'       => 'number',
        'float'     => 'number',
        'array'     => 'Array<any>',
        'bool'      => 'boolean',
        'string'    => 'string',
        'mixed'     => 'any'
    ];
    public static function findTypescriptType($phpTerm){
        return self::$termMaps[$phpTerm]?: $phpTerm;
    }
}