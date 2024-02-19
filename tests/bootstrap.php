<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

$kernel = new Kernel('test', true);
$kernel->boot();

$application = new Application($kernel);
$application->setAutoExit(false);

executeCommand($application, "doctrine:schema:drop", ["--force" => true]);
executeCommand($application, "doctrine:schema:create");
executeCommand($application, "doctrine:fixtures:load");

function executeCommand(Application $application, $command, array $options = []): void
{
    $options['--env'] = 'test';

    $options = array_merge($options, ['command' => $command]);

    $input = new ArrayInput($options);
    $input->setInteractive(false);

    $application->run($input);
}
