<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfc4834dbb88761b768b0875e773a60df
{
    public static $prefixLengthsPsr4 = array (
        'X' => 
        array (
            'XmlProcessor\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'XmlProcessor\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/XmlProcessor',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfc4834dbb88761b768b0875e773a60df::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfc4834dbb88761b768b0875e773a60df::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfc4834dbb88761b768b0875e773a60df::$classMap;

        }, null, ClassLoader::class);
    }
}
