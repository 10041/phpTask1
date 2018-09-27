<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 25.09.18
 * Time: 22:27
 */
class PackagesException extends \Exception {}

class FormatPackagesException extends PackagesException {}

class CycleDependencyException extends PackagesException {}


/**
 * @param array $packages
 *
 * @return void
 *
 * @throws FormatPackagesException
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
 *
 * @return void
 *
 * @throws FormatPackagesException
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
 *
 * @return void
 *
 * @throws FormatPackagesException
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
 *
 * @return void
 *
 * @throws CycleDependencyException
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


/**
 * @param array $packages
 *
 * @return void
 *
 * @throws CycleDependencyException
 * @throws FormatPackagesException
 */
function validatePackageDefinitions(array $packages): void{
    checkPackagesName($packages);
    checkDependenciesKey($packages);
    checkDependencies($packages);
    checkCycleDependencies($packages, []);
}


/**
 * @param array $packages
 * @param string $packageName
 *
 * @return array
 */
function getArrayDependencies(array $packages, string $packageName): array{
    $PackageDependencies = [$packageName];

    foreach ($packages[$packageName]['dependencies'] as $package){
        if(!empty($package)){
            array_push($PackageDependencies, getArrayDependencies($packages, $package));
        }
    }
    return $PackageDependencies;
}

/**
 * @param array $depArray
 *
 * @return array
 */
function convertToOneDimensionalArray(array $depArray): array{
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($depArray));
    return iterator_to_array($iterator, false);
}

/**
 * @param array $packages
 * @param string $packageName
 *
 * @return array
 *
 * @throws CycleDependencyException
 * @throws FormatPackagesException
 */
function getAllPackageDependencies(array $packages, string $packageName): array{
    validatePackageDefinitions($packages);
    $arrayDependencies = convertToOneDimensionalArray(getArrayDependencies($packages, $packageName));
    array_shift($arrayDependencies);
    return array_unique($arrayDependencies);
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
        'dependencies' => ['B', 'D'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => [],
    ]
];

try{
    var_dump(getAllPackageDependencies($packages, 'A'));
}
catch (PackagesException $e){
    print_r($e->getMessage()."\n");
}
