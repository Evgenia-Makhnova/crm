<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd9f0e9c4d770b54598928bd4219cd74c
{
    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'RetailCrm\\' => 
            array (
                0 => __DIR__ . '/..' . '/retailcrm/api-client-php/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitd9f0e9c4d770b54598928bd4219cd74c::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitd9f0e9c4d770b54598928bd4219cd74c::$classMap;

        }, null, ClassLoader::class);
    }
}