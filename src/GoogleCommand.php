<?php

namespace zsim0n\JyskeBank;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use zsim0n\JyskeBank\CSVReader;


class GoogleCommand extends Command {

    protected function configure() {
        $this->setName("jyske:google")
            ->setDescription("Convert JyskeBank CSV to Google")
            ->setDefinition(array(
                new InputArgument('input', InputArgument::REQUIRED, 'Source location)')
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

                fputcsv($out, array('Valør', 'Betalingsmodtageren', 'Kommentar', 'Udgave', 'Indkomst' , 'Beløb' , 'Saldo'), ',', '"');
                $head = $reader->getHead();
                foreach ($reader->setOffset(1)->fetchAssoc($head) as $row) {
                    if (strtolower($row['Afstemt']) == 'nej') {
                        fputcsv($out, array(
                            $reader->getDate($row['Valør'],'m/d/Y'),
                            $reader->getPayee($row['Tekst']),
                            $reader->getMemo($row['Tekst']),
                            $reader->getCurrency($row['Beløb']),
                            $reader->getInflow($row['Beløb']),
                            $reader->getOutflow($row['Beløb']),
                            $reader->getCurrency($row['Saldo'])
                        ), ',', '"');
                    }

                }
                fclose($out);

        }
    }
}

