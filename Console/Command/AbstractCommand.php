<?php

namespace Sparta\Healer\Console\Command;
use Magento\Backend\App\Area\FrontNameResolver;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{
    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progressBar;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Magento\Framework\DB\Adapter\Pdo\Mysql
     */
    protected $connection;

    /**
     * @var \Magento\Framework\Module\ModuleResource
     */
    protected $moduleResource;

    protected function formatTime($time)
    {
        return sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), $time % 60);
    }

    protected function getModuleResource()
    {
        if ($this->moduleResource == null) {
            /** @var \Magento\Framework\Module\ModuleResource $moduleResource */
            $this->moduleResource = $this->getObjectManager()->get('Magento\Framework\Module\ModuleResource');
        }
        return $this->moduleResource;
    }

    protected function getConnection()
    {
        if ($this->connection == null) {
            $this->connection = $this->getModuleResource()->getConnection();
        }

        return $this->connection;
    }

    /**
     * Setup progress bar
     */
    protected function setupProgress()
    {
        $this->progressBar = new \Symfony\Component\Console\Helper\ProgressBar($this->output);
        $this->progressBar->setFormat('<info>%message%</info> %current%/%max% [%bar%] %percent:3s%%');
    }

    /**
     * Gets initialized object manager
     *
     * @return \Magento\Framework\ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (null == $this->objectManager) {
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        }
        return $this->objectManager;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Iterator
     */
    public function getIterator()
    {
        return $this->getObjectManager()->create('\Magento\Framework\Model\ResourceModel\Iterator');
    }

    /**
     * @return  \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->getObjectManager()->get('\Magento\Store\Model\StoreManagerInterface');
    }

    protected function drawImage()
    {
        $this->output->write('', true);
        $this->output->write('', true);
        $this->output->write('                                    `/syhhyyysyyyssoo+//:-.`', true);
        $this->output->write('                                    `syhhhdddddmmNNNNNNNNNmmdys+/-.`', true);
        $this->output->write('                                    .yyyhhhhhdmmmmmmmmmmmNNNNNNNNmdhyo/-.`', true);
        $this->output->write('                                    :yyyhyyddmdddddddddhdmdddddyhdNNNNNNmhyo/-`', true);
        $this->output->write('                                    :yyyyyhddddddddddddddddddddy+oshdhshdmNNmdy+-', true);
        $this->output->write('                                    /yyyyyhhdddddhddddmmmmmmmmmmy+ooydyssyyyhhy-`', true);
        $this->output->write('                                   `/yyyyyyyyyyhddhhdddddmmmmmmmmhooosyyysssss-', true);
        $this->output->write('                                    +yyyyyyyyyyyyhdhhhhhhhhhdhdmmmhooooooosys.', true);
        $this->output->write('                  ..--::::::::::::::oyhhhhhhhhhhhhdddddhhhhyyyyhdmdyoooooosyo', true);
        $this->output->write('                 .shddmmmmmmmmmmmmmmNNNNNNNNNNNmmmmmmmmmmddhhyyyyddhhhyyhhhoo               ``', true);
        $this->output->write('                 `.-/osyhddmmmmmmmNNNNNNMMMMMMMMMMMMMMMMMNNNNNNmmmmmddddddhs+              `', true);
        $this->output->write('                       ``.-:/+osyyhmmmmdddmNNNMMMMMMMMMMMMMMMMMMMMMMMNNNNNmmdyso+/:--.`     ```', true);
        $this->output->write('                                `/yddhysosydmNNMMMNMNMMMMMMMMMMMMMMMMMMMNNNNmmmmmmmmddhyso/:.````', true);
        $this->output->write(' ```   ````                     `hhhhh++/+/ohmNMMMNNMNMNNNNMMMMMMMMMMMMNNmmmmmmmmmmmmmmmmmmddyo````', true);
        $this->output->write('``````````````````           `.:sdhhhy++++++ymNMMNNNMNNNNNMMMMMMMMMMMMMNm/:://+++ooosssssssso+:`````', true);
        $this->output->write('```````````````````````` `.-/sdmmmdhhho++++odmNNNNNNNNNNNNMMMMMMMMMMMMMNh                     ``````', true);
        $this->output->write('``````````````````````.:oyhdmmNNNNNmhhhysyydmmmmmmmNNNNNNNMMMMMMMMMMMMMNo                     ``````', true);
        $this->output->write('```````````````````.:shdmmmmmmmNNNNNmdhhhhhddddmmmmNNNNNNNNNMMMMMMMMMMMNs                      `````', true);
        $this->output->write('`````````````````.+ydddddmmmmmmmNNNNNmmdhhhhhddmmmmNNNNNNNNNNNNMMNNNNNNms                      `````', true);
        $this->output->write('````````````````+yddddddddddmmmmmmmmmmmmdddddddmmNmNNNNNNmmNNNNNNNNNNmmmy                       ````', true);
        $this->output->write('``````````````.shhdddddddddddmmmmmmmmmmmmdddmmmmNNNNNNNNNmmNNNNNNNmmmmdms                        ```', true);
        $this->output->write('`````````````.syhhhhddddhdddddmmmmmmmmmmmmmmmmmNNNNNNMNNmmmNNNNmmmmmmdmm/                          `', true);
        $this->output->write('`````````````osyhhhhdddddmmmddmmmmmmmmmmmmmmmNNNNNNMMMNmmmmmmmmmmmmmmmmd`', true);
        $this->output->write('````````````:syhdddddhyso++//::/oyhddddmmNNNNNNNNMMMMNmmmmmmmmmmNNNNmmm/', true);
        $this->output->write('````````````shyhhys+:.`...........-/shddmmNNNNNMMMMMNmmmmNNNNMMMMMMMNNd.', true);
        $this->output->write('````````````:-.`````````..........```.:ydmNNNMMNNMMNmmmNNNNMMNNMMMMMMMNh`', true);
        $this->output->write('`````````````````````````.........``````-:dNNNNNMMNmmmNNNNNNNmmmmmmmmNNN+', true);
        $this->output->write('``````````````````````````........````````ommmmNmmmmmNNNNNNmmmmmmmmmmmmmdo-`', true);
        $this->output->write('````````````````````````````......````````.hmmmmmmmmNNNNmmmmmmmNNNNNNNNmmmmy::.', true);
        $this->output->write('````````..````````````````````..```````````+dmmmmmNNNNNNNNNNNNNNNNmmmmmdddddddy-`', true);
        $this->output->write('```````...`````````````````````````````````:dmmNNNNNNNNMNNNNmmmmmmmmmmmdddddddhhyo:`', true);
        $this->output->write('````````..``````````````````````````````./odmNmmNNNNNmmmmmmmmmmmmmmddddddddhhhhhhhhhs:`', true);
        $this->output->write('``````...```````````````````````````-+shhhhdddmmmmmmmmmmmmmmmmmmmmmmmmdddddddhhhhhhhdNh/`` `', true);
        $this->output->write('``````..`````````````````````````-ohdddddhhdddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmddhhhhhhhdNNh+-````', true);
        $this->output->write('`````.`.```````````````````````.sddddddddddddddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmddddhhhhhhdmNmdy/.```', true);
        $this->output->write('', true);
        $this->output->write('', true);
    }
}
