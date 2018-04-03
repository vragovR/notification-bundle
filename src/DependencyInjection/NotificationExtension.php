<?php
namespace NotificationBundle\DependencyInjection;

use NotificationBundle\Service\EmailService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NotificationExtension
 *
 * @package NotificationBundle\DependencyInjection
 */
class NotificationExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('notification', $config);

        $this->addClients($config['clients'], $container);
    }

    /**
     * @param array            $clients
     * @param ContainerBuilder $container
     */
    protected function addClients(array $clients, ContainerBuilder $container)
    {
        foreach ($clients as $name => $config) {
            $definition = new Definition(EmailService::class, [
                '$params' => $config,
            ]);

            $definition->setAutowired(true);

            $container->setDefinition(
                sprintf('notification.client.%s', $name),
                $definition
            );
        }
    }
}
