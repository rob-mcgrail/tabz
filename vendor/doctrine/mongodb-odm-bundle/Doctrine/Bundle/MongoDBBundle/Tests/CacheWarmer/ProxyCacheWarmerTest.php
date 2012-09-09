<?php

/*
 * This file is part of the Doctrine MongoDBBundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doctrine\Bundle\MongoDBBundle\Tests\CacheWarmer;

use Doctrine\Bundle\MongoDBBundle\CacheWarmer\ProxyCacheWarmer;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class ProxyCacheWarmerTest extends \Doctrine\Bundle\MongoDBBundle\Tests\TestCase
{
    /**
     * This is not necessarily a good test, it doesn't generate any proxies
     * because there are none in the AnnotationsBundle. However that is
     * rather a task of doctrine to test. We touch the lines here and
     * verify that the container is called correctly for the relevant information.
     *
     * @group DoctrineODMMongoDBProxy
     */
    public function testWarmCache()
    {
        $testManager = $this->createTestDocumentManager(array(
            __DIR__ . "/../DependencyInjection/Fixtures/Bundles/AnnotationsBundle/Document")
        );

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $registry = new ManagerRegistry('MongodB', array(), array('default' => 'doctrine.odm.mongodb.default_document_manager', 'foo' => 'doctrine.odm.mongodb.foo_document_manager'), 'default', 'default', 'Doctrine\ODM\MongoDB\Proxy\Proxy');
        $registry->setContainer($container);

        $container->expects($this->at(0))
                  ->method('getParameter')
                  ->with($this->equalTo('doctrine.odm.mongodb.proxy_dir'))
                  ->will($this->returnValue(sys_get_temp_dir()));
        $container->expects($this->at(1))
                  ->method('getParameter')
                  ->with($this->equalTo('doctrine.odm.mongodb.auto_generate_proxy_classes'))
                  ->will($this->returnValue(false));
        $container->expects($this->at(2))
                  ->method('get')
                  ->with($this->equalTo('doctrine.odm.mongodb'))
                  ->will($this->returnValue($registry));
        $container->expects($this->at(3))
                  ->method('get')
                  ->with($this->equalTo('doctrine.odm.mongodb.default_document_manager'))
                  ->will($this->returnValue($testManager));
        $container->expects($this->at(4))
                  ->method('get')
                  ->with($this->equalTo('doctrine.odm.mongodb.foo_document_manager'))
                  ->will($this->returnValue($testManager));

        $cacheWarmer = new ProxyCacheWarmer($container);
        $cacheWarmer->warmUp(sys_get_temp_dir());
    }

    /**
     * @group DoctrineODMMongoDBProxy
     */
    public function testSkipWhenProxiesAreAutoGenerated()
    {
        $testManager = $this->createTestDocumentManager(array(
            __DIR__ . "/../DependencyInjection/Fixtures/Bundles/AnnotationsBundle/Document")
        );

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
                  ->method('getParameter')
                  ->with($this->equalTo('doctrine.odm.mongodb.proxy_dir'))
                  ->will($this->returnValue(sys_get_temp_dir()));
        $container->expects($this->at(1))
                  ->method('getParameter')
                  ->with($this->equalTo('doctrine.odm.mongodb.auto_generate_proxy_classes'))
                  ->will($this->returnValue(true));
        $container->expects($this->at(2))
                  ->method('getParameter')
                  ->with($this->equalTo('assertion'))
                  ->will($this->returnValue(true));

        $cacheWarmer = new ProxyCacheWarmer($container);
        $cacheWarmer->warmUp(sys_get_temp_dir());

        $container->getParameter('assertion'); // check that the assertion is really the third call.
    }

    /**
     * @group DoctrineODMMongoDBProxy
     */
    public function testProxyCacheWarmingIsNotOptional()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $cacheWarmer = new ProxyCacheWarmer($container);

        $this->assertFalse($cacheWarmer->isOptional());
    }
}