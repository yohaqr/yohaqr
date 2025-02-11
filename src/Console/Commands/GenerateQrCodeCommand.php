<?php

namespace Yoha\Qr\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateQrCodeCommand extends Command
{
    protected static string $defaultName = 'qr:generate';

    public function __construct()
    {
        parent::__construct(name: self::$defaultName); // Ensure command name is set properly
    }

    protected function configure(): void
    {
        $this
            ->setDescription(description: 'Generates a QR code')
            ->setHelp(help: 'This command allows you to generate a QR code with custom settings.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // QR code generation logic here
            $output->writeln('<info>QR Code generated successfully!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
