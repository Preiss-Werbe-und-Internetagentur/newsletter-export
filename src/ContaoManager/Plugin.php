<?php

namespace PWI\ContaoNewsletterExportBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\NewsletterBundle\ContaoNewsletterBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use PWI\ContaoNewsletterExportBundle\ContaoNewsletterExportBundle;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{

    public function getBundles(ParserInterface $parser): array
    {

        return [
            BundleConfig::create(ContaoNewsletterExportBundle::class)
                ->setReplace(['contao-newsletter-export-bundle'])
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    ContaoNewsletterBundle::class
                ])
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {

        return $resolver
            ->resolve(__DIR__ . '/../Resources/config/routing.yml')
            ->load(__DIR__ . '/../Resources/config/routing.yml');
    }
}