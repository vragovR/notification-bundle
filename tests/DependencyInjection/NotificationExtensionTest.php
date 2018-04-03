<?php
namespace NotificationBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var NotificationExtension
     */
    protected $extension;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new NotificationExtension();
    }

    /**
     * @return void
     */
    public function testCreateEmailService()
    {
        $config = [
            'notification' => [
                'clients' => [
                    'first' => [
                        'from' => 'from',
                        'reply_to' => 'reply_to'
                    ],
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->has('notification.client.first'));
    }
}
