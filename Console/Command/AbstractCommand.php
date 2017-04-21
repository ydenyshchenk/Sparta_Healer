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
            $area = FrontNameResolver::AREA_CODE;
            $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \Magento\Framework\App\State $appState */
            $appState = $this->objectManager->get('Magento\Framework\App\State');
            $appState->setAreaCode($area);
            $configLoader = $this->objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
            $this->objectManager->configure($configLoader->load($area));
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
}
