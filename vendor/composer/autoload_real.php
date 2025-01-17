<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitece0e9bd8ee6fcb97e0174640e26d51e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitece0e9bd8ee6fcb97e0174640e26d51e', 'loadClassLoader'), true, false);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitece0e9bd8ee6fcb97e0174640e26d51e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitece0e9bd8ee6fcb97e0174640e26d51e::getInitializer($loader));

        $loader->setClassMapAuthoritative(true);
        $loader->setApcuPrefix('CF8Sne0o0SuShzOiNphs4');
        $loader->register(false);

        $filesToLoad = \Composer\Autoload\ComposerStaticInitece0e9bd8ee6fcb97e0174640e26d51e::$files;
        $requireFile = \Closure::bind(static function ($fileIdentifier, $file) {
            if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
                $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

                require $file;
            }
        }, null, null);
        foreach ($filesToLoad as $fileIdentifier => $file) {
            $requireFile($fileIdentifier, $file);
        }

        return $loader;
    }
}
