<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb71ed262eb46b291eff7c57d413f4ad
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Office365\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Office365\\' => 
        array (
            0 => __DIR__ . '/..' . '/vgrem/php-spo/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb71ed262eb46b291eff7c57d413f4ad::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb71ed262eb46b291eff7c57d413f4ad::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbb71ed262eb46b291eff7c57d413f4ad::$classMap;

        }, null, ClassLoader::class);
    }
}
