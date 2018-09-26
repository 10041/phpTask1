<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 25.09.18
 * Time: 22:27
 */

class FormatPackagesException extends \Exception {}

class CycleDependencyException extends \Exception {}

/**
 * @param array $packages
 * @throws FormatPackagesException
 *
 * @return void
 */
function checkPackagesName(array $packages): void{
    foreach ($packages as $key=>$value){
        if($key !== $value['name']){
            throw new FormatPackagesException("the names do not match");
        }
    }
}

/**
 * @param array $packages
 * @throws FormatPackagesException
 *
 * @return void
 */
function checkDependenciesKey(array $packages): void{
    foreach ($packages as $key=>$value){
        if(!array_key_exists('dependencies', $value)){
            throw new FormatPackagesException("not key 'dependencies'");
        }
    }
}


/**
 * @param array $packages
 * @throws FormatPackagesException
 *
 * @return void
 */
function checkDependencies(array $packages): void{
    foreach ($packages as $key=>$value){
        foreach ($value['dependencies'] as $dependencie){
            if(!array_key_exists($dependencie, $packages)){
                throw new FormatPackagesException("dependencie ".$dependencie." not found");
            }
        }
    }
}


/**
 * @param array $packages
 * @param array $usedDependencies
 * @throws CycleDependencyException
 *
 * @return void
 */
function checkCycleDependencies(array $packages, array $usedDependencies): void{
    foreach ($packages as $key => $value){
        if(!empty($value['dependencies'])){
            if(in_array($value['name'], $usedDependencies)){
                throw new CycleDependencyException("Cycle dependency ".$value['name']);
            }
            array_push($usedDependencies, $value['name']);
            foreach ($value['dependencies'] as $dependency){
                if(in_array($dependency, $usedDependencies)){
                    throw new CycleDependencyException("Cycle dependency ".$dependency);
                }
                checkCycleDependencies($packages[$dependency], $usedDependencies);
            }
        }
    }
}

function validatePackageDefinitions(array $packages): void{

}

function getAllPackageDependencies(array $packages, string $packageName): array{

}


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
        'dependencies' => ['B', 'D', 'C'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => [],
    ],
];

try{
    print_r(checkCycleDependencies($packages, [])."\n");
}
catch (FormatPackagesException $e){
    print_r($e->getMessage()."\n");
}
catch (CycleDependencyException $e){
    print_r($e->getMessage()."\n");
}