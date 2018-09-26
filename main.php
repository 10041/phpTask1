<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 25.09.18
 * Time: 22:27
 */

$packages = [
    'A' => [
        'name' => 'A',
        'dependencies' => ['B', 'C'],
    ],
    'B' => [
        'name' => 'B',
        'dependencies' => [],
    ],
    'C' => [
        'name' => 'C',
        'dependencies' => ['B', 'D'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => [],
    ],
];


class FormatPackagesException extends \Exception {}

class CycleDependencieException extends \Exception {}

function checkPackagesName(array $packages): boolean{

}

function validatePackageDefinitions(array $packages): void{

}

function getAllPackageDependencies(array $packages, string $packageName): array{

}

try{
    throw new CycleDependencieException('aaaaaaaa');
}
catch (FormatPackagesException $e){
    print_r($e->getMessage()."\n");
}
catch (CycleDependencieException $e){
    print_r($e->getMessage()."\n");
}