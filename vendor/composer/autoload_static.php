<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcb5da62d73716ac8a1776f19367bc28f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'App\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitcb5da62d73716ac8a1776f19367bc28f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcb5da62d73716ac8a1776f19367bc28f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcb5da62d73716ac8a1776f19367bc28f::$classMap;

        }, null, ClassLoader::class);
    }
}
