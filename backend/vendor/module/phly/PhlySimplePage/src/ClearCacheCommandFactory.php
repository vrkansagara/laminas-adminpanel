<?php declare(strict_types=1);

/**
 * @link      https://github.com/weierophinney/PhlySimplePage for the canonical source repository
 * @copyright Copyright (c) 2020 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/weierophinney/PhlySimplePage/blog/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlySimplePage;

use Psr\Container\ContainerInterface;

class ClearCacheCommandFactory
{
    public function __invoke(ContainerInterface $container): ClearCacheCommand
    {
        return new ClearCacheCommand(
            $container->get('PhlySimplePage\PageCache')
        );
    }
}
