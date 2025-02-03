<?php

namespace Botble\Mpesa;

use Botble\Base\PluginManagement\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    public static function getDescription(): string
    {
        return 'M-Pesa Payment Plugin using Daraja API.';
    }

    public static function getAuthor(): string
    {
        return 'maina';
    }

    public static function getVersion(): string
    {
        return '1.0.0';
    }

    public function boot()
    {
        // Register routes if necessary
        $this->setNamespace('plugins.mpesa')->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publish configuration file so it can be overridden in the main config folder
        $this->publishes([__DIR__ . '/../config/mpesa.php' => config_path('mpesa.php')], 'config');
    }
}
