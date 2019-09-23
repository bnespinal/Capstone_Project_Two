<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit710087d1e4cf45fe376da4a3cf78e42c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit710087d1e4cf45fe376da4a3cf78e42c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit710087d1e4cf45fe376da4a3cf78e42c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
