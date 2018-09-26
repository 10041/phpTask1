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
        'name' => 'A',
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

function checkPackagesName(array $packages): bool{
    foreach ($packages as $key=>$value){
        if($key !== $value['name']){
            return FALSE;
        }
    }
    return TRUE;
}


function validatePackageDefinitions(array $packages): void{

}

function getAllPackageDependencies(array $packages, string $packageName): array{

}

try{
    print_r(checkPackagesName($packages)."\n");
}
catch (FormatPackagesException $e){
    print_r($e->getMessage()."\n");
}
catch (CycleDependencieException $e){
    print_r($e->getMessage()."\n");
}