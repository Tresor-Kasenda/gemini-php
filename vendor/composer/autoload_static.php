<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e1ae29b641a85e74125bbae41790323
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Scott\\GeminiPhp\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Scott\\GeminiPhp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4e1ae29b641a85e74125bbae41790323::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4e1ae29b641a85e74125bbae41790323::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4e1ae29b641a85e74125bbae41790323::$classMap;

        }, null, ClassLoader::class);
    }
}
