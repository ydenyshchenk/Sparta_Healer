<?php

namespace Sparta\Healer\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerAttributes extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sparta:heal:customer_attributes');
        $this->setDescription('Heal customer attribute<>attribute_set links');
        parent::configure();
    }
    
    protected function healCustomerAttributeAttributeSetLinks()
    {
        $connection = $this->getConnection();
        $moduleResource = $this->getModuleResource();
        $timeStart = microtime(true);

        $eavAttributeTable = $moduleResource->getTable('eav_attribute');
        $eavEntityAttributeTable = $moduleResource->getTable('eav_entity_attribute');
        $customerEavAttributeTable = $moduleResource->getTable('customer_eav_attribute');

        $select = $connection->select();
        $select->from(array('eea' => $eavEntityAttributeTable))
            ->join(array('ea' => $eavAttributeTable), 'eea.attribute_id = ea.attribute_id', '')
            ->where('ea.attribute_code = ?', 'firstname')
            ->where('eea.entity_type_id = ?', 1);
        $eeaTemplate = $connection->query($select)->fetch();

        if (empty($eeaTemplate)) {
            $this->output->write("FATAL!!!! Customer attribute 'firstname' doesn`t exist", true);
            exit();
        }

        $eeaTemplate['entity_attribute_id'] = null;
        $eeaTemplate['attribute_id'] = null;
        $eeaTemplate['sort_order'] = null;

        $select->reset();
        $select->from(array('ea' => $eavAttributeTable), array('attribute_id', 'attribute_code'))
            ->joinLeft(array('eea' => $eavEntityAttributeTable), 'ea.attribute_id = eea.attribute_id', '')
            ->join(array('cea' => $customerEavAttributeTable), 'ea.attribute_id = cea.attribute_id', 'cea.sort_order')
            ->where('eea.attribute_id is null')
            ->where('ea.entity_type_id = ?', 1);
        $missedCustomerAttributes = $connection->query($select)->fetchAll();

        if (count($missedCustomerAttributes) == 0) {
            $this->output->write('There is no missed attribute<>attribute_set links', true);
            exit();
        }

        $eeaRepairRows = array();
        foreach ($missedCustomerAttributes as $a) {
            $data = $eeaTemplate;
            $attributeCode = $a['attribute_code'];
            $data['attribute_id'] = $a['attribute_id'];
            $data['sort_order'] = $a['sort_order'];
            $eeaRepairRows[$attributeCode] = $data;
        }

        $connection->insertMultiple($eavEntityAttributeTable, $eeaRepairRows);

        $timeEnd = microtime(true);
        $timeDiff = $timeEnd - $timeStart;
        $this->output->write('Repaired customer attribute<>attribute_set links in ' . $this->formatTime($timeDiff), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->setupProgress();

        try {
            $this->healCustomerAttributeAttributeSetLinks();
        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}