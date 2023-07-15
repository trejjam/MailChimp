<?php
declare(strict_types=1);

namespace Trejjam\MailChimp\Cases\Tests;

use Nette;
use Tester;
use Tester\Assert;
use Trejjam\MailChimp;

$container = require __DIR__ . '/../bootstrap.php';

final class ContextTest extends Tester\TestCase
{
    public function __construct(
        private readonly Nette\DI\Container $container
    ) {
    }

    public function testConfig() : void
    {
        $mailchimpContext = $this->container->getByType(MailChimp\Context::class);

        Assert::type(MailChimp\Context::class, $mailchimpContext);

        $rootResponse = $mailchimpContext->getRootGroup()->get();

        Assert::equal('account_id', $rootResponse->account_id);

        $listsResponse = $mailchimpContext->getListsGroup()->getAll();
    }
}

$test = new ContextTest($container);
$test->run();
