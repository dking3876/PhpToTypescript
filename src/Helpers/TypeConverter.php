<?php 
namespace PhpToTypescript\Helpers;
/**
 * Class TypeConverter
 * @package PhpToTypescript\Helpers
 */
class TypeConverter{
    /**
     * @var array
     */
    private static $TypeMap = [
        'mixed'     => 'any',
        'int'       => 'number',
        'float'     => 'number',
        'array'     => 'Array<any>',
        'bool'      => 'boolean',
        'string'    => 'string',
    ];

    /**
     * Add special TypeMap
     * @example TypeConverter::addNewTypeMap('User', '_User'); For converting User class into the abstract class this library will spit out.
     * @param $phpTerm
     * @param $typescript
     */
    public static function addNewTypeMap($phpTerm, $typescript){
        self::$TypeMap[$phpTerm] = $typescript;
    }

    /**
     * @param $phpTerm
     * @return mixed
     */
    public static function findTypescriptType($phpTerm){
        if(isset(self::$TypeMap[$phpTerm])){
            return self::$TypeMap[$phpTerm];
        }
        return $phpTerm;
    }
}