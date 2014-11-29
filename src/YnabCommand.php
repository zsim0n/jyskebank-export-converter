<?php

namespace zsim0n\JyskeBank;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use zsim0n\JyskeBank\CSVReader;


class YnabCommand extends Command {
    protected function configure()
    {
        $this->setName("jyske:ynab")
            ->setDescription("Convert JyskeBank CSV to YNAB")
            ->setDefinition(array(
                new InputArgument('input', InputArgument::REQUIRED, 'Source location)'),
                new InputOption('skip-afstemt','S',InputOption::VALUE_NONE,'')

            ));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        if ($path = $input->getArgument('input')) {

            $reader = CSVReader::CreateFromPath($path);

            $out = fopen('php://output', 'w');

            fputcsv($out, array('Date', 'Payee', 'Category', 'Memo', 'Outflow' , 'Inflow'), ',', '"');
            $head = $reader->getHead();
            foreach ($reader->setOffset(1)->fetchAssoc($head) as $row) {
                if ($input->getOption('skip-afstemt') || (!$input->getOption('skip-afstemt') && strtolower($row['Afstemt']) == 'nej')) {
                    fputcsv($out, array(
                        $reader->getDate($row['Dato'], 'd/m/Y'),
                        $reader->getPayee($row['Tekst']),
                        '',
                        $reader->getMemo($row['Tekst']),
                        $reader->getOutflow($row['Beløb']),
                        $reader->getInflow($row['Beløb'])
                    ), ',', '"');
                }
            }
            fclose($out);

        }
    }
}
