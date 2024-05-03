<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0863ee264454afd32d38d28920351a83
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Kevinmancuso\\Ronikdesign\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Kevinmancuso\\Ronikdesign\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit0863ee264454afd32d38d28920351a83::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0863ee264454afd32d38d28920351a83::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0863ee264454afd32d38d28920351a83::$classMap;

        }, null, ClassLoader::class);
    }
}