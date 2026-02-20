<?php

use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;
use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

$config = new Configuration;

return $config
    ->addPathToExclude(__DIR__.'/src/Tooling')
    ->ignoreErrorsOnPackage('aryeo/eloquent-filters', [ErrorType::PROD_DEPENDENCY_ONLY_IN_DEV]);
