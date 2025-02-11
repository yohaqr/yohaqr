<?php

namespace Yoha\Qr\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunTests extends Command
{
    protected static string $defaultName = 'test:run';

    public function __construct()
    {
        parent::__construct(name: self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Runs PHPUnit tests')
            ->setHelp('This command runs PHPUnit tests and displays the results.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Running PHPUnit tests...</info>');
    
        // Detect OS and set correct PHPUnit command
        $phpunitCommand = ['php', 'vendor/phpunit/phpunit/phpunit', '--bootstrap', 'vendor/autoload.php', 'tests'];
    
        $process = new Process($phpunitCommand);
        $process->setTimeout(null);
    
        try {

            $process->mustRun(function ($type, $buffer) use ($output) {
                // Helper to safely convert any value to a string
                $safeToString = function ($value): string {
                    if (is_scalar($value) || $value === null) {
                        return sprintf('%s', $value);
                    }
                    $json = json_encode($value);
                    // If json_encode fails, return an empty string (or you can return a default message)
                    return $json === false ? '' : $json;
                };
            
                if (is_iterable($buffer)) {
                    foreach ($buffer as $line) {
                        $output->write($safeToString($line));
                    }
                } else {
                    $output->write($safeToString($buffer));
                }
            });
            
    
            return Command::SUCCESS;

        } catch (ProcessFailedException $exception) {
            $output->writeln('<error>Tests failed:</error>');
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }
    }
    

}
