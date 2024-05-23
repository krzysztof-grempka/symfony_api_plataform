<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Symfony;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Message\MessageHandlerInterface;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Infrastructure\Shared\Symfony\DependencyInjection\Compiler\ClearNativeProviderAndProcessorsCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(sprintf('%s/config/{packages}/*.yaml', $this->getProjectDir()));
        $container->import(sprintf('%s/config/{packages}/%s/*.yaml', $this->getProjectDir(), $this->environment));

        $container->import(sprintf('%s/config/{services}/*.yaml', $this->getProjectDir()));
        $container->import(sprintf('%s/config/{services}/%s/*.yaml', $this->getProjectDir(), $this->environment));
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(sprintf('%s/config/{routes}/%s/*.yaml', $this->getProjectDir(), $this->environment));
        $routes->import(sprintf('%s/config/{routes}/*.yaml', $this->getProjectDir()));
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'query.bus']);

        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'command.bus']);

        $container->registerForAutoconfiguration(MessageHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'message.bus']);

        $container->addCompilerPass(new ClearNativeProviderAndProcessorsCompilerPass());
    }
}
