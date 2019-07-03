<?php

namespace AppBundle\Command;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputArgument;

class MergeJsonFilesCommand extends Command
{
    /** @var  EntityManager */
    protected $em;

    /** @var  Container */
    protected $container;

    /** @var array */
    private $_idNameMap = array();

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:merge-json-files')
            ->setDescription('Merge json files')
            ->addArgument('list', InputArgument::OPTIONAL, 'Name of json file with list', 'list')
            ->addArgument('tree', InputArgument::OPTIONAL,  'Name of json file with tree', 'tree')
            ->addArgument('save', InputArgument::OPTIONAL, 'Name of json file to save merged file', 'save');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonDir = __DIR__ . '/jsons/';

        $listFile = $jsonDir . $input->getArgument('list'). '.json';
        $treeFile = $jsonDir . $input->getArgument('tree'). '.json';
        $saveFile = $jsonDir . $input->getArgument('save') .'.json';


        $listFileContent = file_get_contents($listFile);
        $treeFileContent = file_get_contents($treeFile);

        if ($listFileContent === false || $treeFileContent === false) {
            throw new Exception('Could not read source json files', 500);
        }


        $list = json_decode($listFileContent);
        $tree = json_decode($treeFileContent);

        foreach ($list as $item) {
            $id = intval($item->category_id);
            $this->_idNameMap[$id] = (string)$item->translations->pl_PL->name;
        }

        $this->_appendNameToBranch($tree);


        $state = file_put_contents($saveFile, json_encode($tree));

        if ($state === false) {
            throw new Exception('Could not save output json files', 500);

        }

        $output->writeln(date("Y-m-d H:i:s") . ' json files are merger to file ' . realpath($saveFile));
    }


    private function _appendNameToBranch(&$data)
    {
        foreach ($data as $item) {
            $id = intval($item->id);
            if (isset($this->_idNameMap[$id])) {
                $item->name = $this->_idNameMap[$id];
            }
            if (!empty($item->children)) {
                $this->_appendNameToBranch($item->children);
            }
        }
    }
}