# Php To Typescript
This package will convert your php classes into abstract class in typescript.  All classes will be appended by an underscore(_) and should be extended in a concrete class.

### Author

Deryk William King <dking3876@msn.com>

### Requirements 

- Php 7.x
- Composer

### Installation

Install the PhpToTypescript package `composer require dking3876/PhpToTypescript`

### Instructions

This package will convert a folder of models and create typescript file structure matching the source with abstract classes for each one. You will need to modify the files with any appropriate import statements (import {User} from './User' ).  
Create a php script with the following code.

```php
<?php 
include 'vendor/autoload.php';
use PhpToTypescript\Converter;

$pathToYourModelFolder = '';
$convert = Converter::convert($pathToYourModelFolder);

```

Run your new script and a 'convert' folder will be created in the folder your run the script from with the models folder and all the created ts files.




