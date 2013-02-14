<?php
/**
 * Quan MT - Brodev Software
 * www.brodev.com
 */

namespace Brodev\VietIpsumWebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Brodev\VietIpsumWebBundle\Entity\Source;

class DownloadSourceCommand extends ContainerAwareCommand
{
    /**
     * @var simple_html_dom
     */
    protected $simpleHtmlDom;

    protected function configure()
    {
        $this
            ->setName('vietipsum:download')
            ->setDescription('Download food source')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require_once(
            $this->getContainer()->getParameter( 'kernel.root_dir' )
                . '/../src/lib/simple_html_dom.php'
        );

        $sourceUrl = 'http://vi.wikipedia.org/wiki/Danh_s%C3%A1ch_c%C3%A1c_m%C3%B3n_%C4%83n_Vi%E1%BB%87t_Nam';
        $sourceUrl = 'http://localhost/monan.html';

        // get all tables
        $this->simpleHtmlDom = $this->getContainer()->get('simple_html_dom');
        $this->simpleHtmlDom->load(file_get_contents($sourceUrl));

        $result = '';

        // get content
        $tables = $this->simpleHtmlDom->find('#mw-content-text table[class=wikitable]');
        foreach ($tables as $table) {
            $trs = $table->find('tr');

            $i = 0;
            foreach ($trs as $tr) {
                $i++;

                if ($i == 1) {
                    continue;
                }

                $tds = $tr->find('td');

                $name = trim($tds[0]->plaintext);
                $result .= $name . "\n";
            }
        }

        // insert to database
        $source = new Source();
        $source->setActive(true);
        $source->setName('Vietnamese Food');
        $source->setContent($result);
        $source->setOrder(0);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($source);
        $em->flush();
    }

}
