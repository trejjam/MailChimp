<?php
declare(strict_types=1);

namespace Trejjam\MailChimp\DI;

use GuzzleHttp;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use Trejjam\BaseExtension\DI\BaseExtension;
use Trejjam\MailChimp;
use function Safe\sprintf;

/**
 * Inspired by
 * - https://github.com/zbycz/mailchimp-v3-php/blob/master/MailchimpService.php
 * - https://github.com/Kdyby/CsobPaymentGateway
 *
 * @property ExtensionConfiguration $config
 */
final class MailChimpExtension extends BaseExtension
{
    protected $classesDefinition = [
        'http.client' => GuzzleHttp\Client::class,

        'request' => MailChimp\Request::class,

        'context'     => MailChimp\Context::class,
        'group.root'  => MailChimp\Group\Root::class,
        'group.lists' => MailChimp\Group\Lists::class,

        'lists'    => MailChimp\Lists::class,
        'segments' => MailChimp\Segments::class,
    ];

    protected $factoriesDefinition = [

    ];

    public function __construct()
    {
        $this->config = new ExtensionConfiguration();
    }

    public function getConfigSchema() : Schema
    {
        return Expect::from($this->config)->before(
            function (array $config) : array {
                if (true === ($config['findDataCenter'] ?? $this->config->findDataCenter)) {
                    // unable to find, possible use of neon parameter, which will be expanded later
                    $config['apiUrl'] = $this->config->apiUrlTemplate;
                }

                return $config;
            }
        );
    }

    /**
     * Extract dc from apikey
     *
     * http://developer.mailchimp.com/documentation/mailchimp/guides/get-started-with-mailchimp-api-3#resources
     *
     * @inheritdoc
     */
    public function loadConfiguration(bool $validateConfig = true) : void
    {
        if ($this->config->findDataCenter === true) {
            $accountDataCenter = Strings::match($this->config->apiKey, '~-(us(?:\d+))$~');
            assert($accountDataCenter !== null);
            $this->config->apiUrl = sprintf($this->config->apiUrlTemplate, $accountDataCenter[1], MailChimp\Request::VERSION);
        }

        foreach (array_keys($this->config->segments) as $listName) {
            Validators::assertField($this->config->lists, $listName);
        }
    }

    public function beforeCompile() : void
    {
        parent::loadConfiguration(false);

        parent::beforeCompile();

        $types = $this->getTypes();

        if ($this->config->http->clientFactory !== null) {
            if (is_string($this->config->http->clientFactory) && Strings::startsWith($this->config->http->clientFactory, '@')) {
                $types['http.client']->setCreator($this->config->http->clientFactory);
            }
            else {
                $this->loadDefinitionsFromConfig(
                    [
                        'http.client' => $this->config->http->clientFactory,
                    ]
                );
            }
        }

        $types['http.client']->setArguments(
            [
                'config' => $this->config->http->client,
            ]
        )->setAutowired(false);

        $types['request']->setArguments(
            [
                'httpClient' => $this->prefix('@http.client'),
                'apiUrl'     => $this->config->apiUrl,
                'apiKey'     => $this->config->apiKey,
            ]
        );

        $types['lists']->setArguments(
            [
                'lists' => $this->config->lists,
            ]
        );
        $types['segments']->setArguments(
            [
                'segments' => $this->config->segments,
            ]
        );
    }
}
