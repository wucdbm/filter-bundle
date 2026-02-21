<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->load('Wucdbm\\Bundle\\WucdbmFilterBundle\\Form\\Type\\', __DIR__ . '/../../Form/Type/*')
        ->tag('form.type');
};
