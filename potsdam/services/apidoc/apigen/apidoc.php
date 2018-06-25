<?php

//namespace ApiDoc;

//include 'crada\php-apidoc\Crada\Apidoc\Builder.php';
//include 'crada\php-apidoc\Crada\Apidoc\Template.php';
require __DIR__ . '/../vendor/autoload.php';
include 'ApiDefinition.php';

use Crada\Apidoc\Builder;
use Crada\Apidoc\Exception;

$classes = array(
   'ApiDefinition'
   //'Crada\Apidoc\TestClasses\Article'
   
);

$output_dir  = __DIR__.'';
$output_file = 'api.html'; // defaults to index.html

try {
    $builder = new Builder($classes, $output_dir, 'Potsdam Device API(s)', $output_file);
    $builder->generate();
} catch (Exception $e) {
    echo 'There was an error generating the documentation: ', $e->getMessage();
}