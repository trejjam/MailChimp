<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

Tester\Environment::setup();

$tempDir = implode(DIRECTORY_SEPARATOR, [__DIR__, 'temp', Nette\Utils\Random::generate()]);
$logDir = implode(DIRECTORY_SEPARATOR, [__DIR__, 'log']);

if (!file_exists($tempDir)) {
    @mkdir($tempDir, 0777, true);
}

if (!file_exists($logDir)) {
    @mkdir($logDir, 0777, true);
}

$configurator = new Nette\Bootstrap\Configurator;
$configurator->setDebugMode(true);
Tracy\Debugger::$logDirectory = $logDir;
//$configurator->enableDebugger($logDir);
$configurator->setTempDirectory($tempDir);

$configurator->createRobotLoader()
    ->addDirectory(dirname(__DIR__) . '/src')
    ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
if (file_exists(__DIR__ . '/config/config.local.neon')) {
    $configurator->addConfig(__DIR__ . '/config/config.local.neon');
}
$configurator->addStaticParameters([
    'ENV' => array_filter(getenv(), function (string $key): bool {
        return str_starts_with($key, 'MAILCHIMP_');
    }, ARRAY_FILTER_USE_KEY),
]);

return $configurator->createContainer();
