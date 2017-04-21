<?php

namespace Sparta\Healer\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AttributeSets extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('heal:attribute_sets');
        $this->setDescription('Heal attribute set incorrect IDs');
        parent::configure();
    }

    protected function healAttributeSets()
    {
        $connection = $this->getConnection();
        $moduleResource = $this->getModuleResource();

        $connection->query('SET `foreign_key_checks` = 0;');

        $eavEntityTypeCodeToId = array(
            'customer' => 1,
            'customer_address' => 2,
            'catalog_category' => 3,
            'catalog_product' => 4,
            'order' => 5,
            'invoice' => 6,
            'creditmemo' => 7,
            'shipment' => 8,
            'rma_item' => 9
        );

        $eavEntityTypeIdToType = array(
            1 => 'customer',
            2 => 'customer_address',
            3 => 'catalog_category',
            4 => 'catalog_product',
            5 => 'order',
            6 => 'invoice',
            7 => 'creditmemo',
            8 => 'shipment',
            9 => 'rma_item'
        );

        $eavEntityTypeTable = $moduleResource->getTable('eav_entity_type');
        $eavAttributeSetTable = $moduleResource->getTable('eav_attribute_set');

        $timeStart = microtime(true);

        $select = $connection->select();
        $select->from(array('et' => $eavEntityTypeTable));
        $eavEntityTypesRaw = $connection->query($select)->fetchAll();

        $eavEntityTypesById = array();
        $eavEntityTypesByCode = array();

        foreach ($eavEntityTypesRaw as $item) {
            $entityTypeId = (int)$item['entity_type_id'];
            $entityTypeCode = $item['entity_type_code'];
            $eavEntityTypesById[$entityTypeId] = $item;
            $eavEntityTypesByCode[$entityTypeCode] = $item;
        }

        $eavEntityTypesRepaired = array();
        $mapOldIdsToNewIds = array();

        foreach ($eavEntityTypeIdToType as $id => $code) {
            $id = (int)$id;
            if (empty($eavEntityTypesByCode[$code])) {
                $this->output->write("FATAL!!!! Entity type with code = $code doesn`t exist", true);
                exit();
            }

            $data = $eavEntityTypesByCode[$code];
            unset($eavEntityTypesByCode[$code]);
            $oldId = (int)$data['entity_type_id'];
            $mapOldIdsToNewIds[$oldId] = $id;
            $data['entity_type_id'] = $id;
            $data['default_attribute_set_id'] = $id;
            $eavEntityTypesRepaired[$id] = $data;
        }

        foreach ($eavEntityTypesByCode as $code => $data) {
            $id++;
            $oldId = (int)$data['entity_type_id'];
            $mapOldIdsToNewIds[$oldId] = $id;
            $data['entity_type_id'] = $id;
            $data['default_attribute_set_id'] = $id;
            $eavEntityTypesRepaired[$id] = $data;
        }

        foreach ($mapOldIdsToNewIds as $oldId => $newId) {
            if ($oldId == $newId) {
                unset($mapOldIdsToNewIds[$oldId]);
            }
        }

        if ($mapOldIdsToNewIds && $eavEntityTypesRepaired) {
            $mapOldIdsToNewIdsMoveStep0 = array();
            $mapOldIdsToNewIdsMoveStep1 = array();

            foreach ($mapOldIdsToNewIds as $oldId => $newId) {
                $tempId = $oldId + 1000;
                $mapOldIdsToNewIdsMoveStep0[$oldId] = $tempId;
                $mapOldIdsToNewIdsMoveStep1[$tempId] = $newId;
            }

            $eavEntityTypeUpdateTables = array(
                'eav_attribute' => $moduleResource->getTable('eav_attribute'),
                'eav_attribute_set' => $moduleResource->getTable('eav_attribute_set'),
                'eav_entity' => $moduleResource->getTable('eav_entity'),
                'eav_entity_attribute' => $moduleResource->getTable('eav_entity_attribute'),
                'eav_entity_datetime' => $moduleResource->getTable('eav_entity_datetime'),
                'eav_entity_decimal' => $moduleResource->getTable('eav_entity_decimal'),
                'eav_entity_int' => $moduleResource->getTable('eav_entity_int'),
                'eav_entity_store' => $moduleResource->getTable('eav_entity_store'),
                'eav_entity_text' => $moduleResource->getTable('eav_entity_text'),
                'eav_entity_varchar' => $moduleResource->getTable('eav_entity_varchar'),
                'eav_form_type_entity' => $moduleResource->getTable('eav_form_type_entity'),
            );

            $connection->truncateTable($eavEntityTypeTable);
            $connection->insertMultiple($eavEntityTypeTable, $eavEntityTypesRepaired);

            foreach ($mapOldIdsToNewIdsMoveStep0 as $oldId => $newId) {
                foreach ($eavEntityTypeUpdateTables as $table) {
                    $connection->update($table, array('entity_type_id' => $newId), array('entity_type_id = ?' => $oldId));
                }
            }

            foreach ($mapOldIdsToNewIdsMoveStep1 as $oldId => $newId) {
                foreach ($eavEntityTypeUpdateTables as $table) {
                    $connection->update($table, array('entity_type_id' => $newId), array('entity_type_id = ?' => $oldId));
                }
            }
        }

        $timeEnd = microtime(true);
        $timeDiff = $timeEnd - $timeStart;
        $this->output->write('Repaired EAV entity type IDs in ' . $this->formatTime($timeDiff), true);


        /* ATTRIBUTE_SET_ID */
        $timeStart = microtime(true);
        $attributeSetIdToTypeId = array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9
        );

        $attributeTypeIdToSetId = array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9
        );

        $multipleAttributeSetsAllowed = array(
            4 => true
        );

        $select->reset();
        $select->from(array('eas' => $eavAttributeSetTable))->order(array('entity_type_id', 'attribute_set_name'));
        $eavAttributeSetRaw = $connection->query($select)->fetchAll();

        $eavAttributeSetById = array();
        $eavAttributeSetByTypeId = array();

        foreach ($eavAttributeSetRaw as $item) {
            $eavAttributeSetId = (int)$item['attribute_set_id'];
            $eavAttributeTypeId = (int)$item['entity_type_id'];
            $eavAttributeSetById[$eavAttributeSetId] = $item;
            $eavAttributeSetByTypeId[$eavAttributeTypeId][] = $item;
        }

        $usedIds = array();
        $eavAttributeSetRepaired = array();
        $eavAttributeSetRepairedOther = array();
        $mapOldIdsToNewIds = array();

        foreach ($attributeTypeIdToSetId as $typeId => $setId) {
            if (empty($eavAttributeSetByTypeId[$typeId])) {
                $this->output->write("FATAL!!!! Attribute set with entity_type_id = $typeId doesn`t exist", true);
                exit();
            }
            $attributeSets = $eavAttributeSetByTypeId[$typeId];
            $insertedCount = 0;
            foreach ($attributeSets as $attributeSet) {
                $data = $attributeSet;
                $oldId = (int)$data['attribute_set_id'];
                if ($insertedCount == 0) {
                    $id = $attributeTypeIdToSetId[$typeId];
                    $usedIds[] = $id;
                    $mapOldIdsToNewIds[$oldId] = $id;
                    $data['attribute_set_id'] = $id;
                    $eavAttributeSetRepaired[$id] = $data;
                } else {
                    if (!empty($multipleAttributeSetsAllowed[$typeId])) {
                        $eavAttributeSetRepairedOther[] = $data;
                    } else {
                        $mapOldIdsToNewIds[$oldId] = $id;
                    }
                }
                $insertedCount++;
            }
            unset($eavAttributeSetByTypeId[$typeId]);
        }

        foreach ($eavAttributeSetByTypeId as $typeId => $attributeSets) {
            foreach ($attributeSets as $attributeSet) {
                $eavAttributeSetRepairedOther[] = $attributeSet;
            }
        }

        $id = max($usedIds);

        foreach ($eavAttributeSetRepairedOther as $data) {
            $id++;
            $oldId = (int)$data['attribute_set_id'];
            $mapOldIdsToNewIds[$oldId] = $id;
            $data['attribute_set_id'] = $id;
            $eavAttributeSetRepaired[$id] = $data;
        }
        unset($eavAttributeSetRepairedOther);

        foreach ($mapOldIdsToNewIds as $oldId => $newId) {
            if ($oldId == $newId) {
                unset($mapOldIdsToNewIds[$oldId]);
            }
        }

        if ($mapOldIdsToNewIds && $eavAttributeSetRepaired) {
            $mapOldIdsToNewIdsMoveStep0 = array();
            $mapOldIdsToNewIdsMoveStep1 = array();

            foreach ($mapOldIdsToNewIds as $oldId => $newId) {
                $tempId = $oldId + 1000;
                $mapOldIdsToNewIdsMoveStep0[$oldId] = $tempId;
                $mapOldIdsToNewIdsMoveStep1[$tempId] = $newId;
            }

            $attributeSetUpdateTables = array(
                'catalog_category_entity' => array('t' => $moduleResource->getTable('catalog_category_entity'), 'c' => 'attribute_set_id'),
                'catalog_product_entity' => array('t' => $moduleResource->getTable('catalog_product_entity'), 'c' => 'attribute_set_id'),
                'eav_attribute_group' => array('t' => $moduleResource->getTable('eav_attribute_group'), 'c' => 'attribute_set_id'),
                'eav_entity' => array('t' => $moduleResource->getTable('eav_entity'), 'c' => 'attribute_set_id'),
                'eav_entity_attribute' => array('t' => $moduleResource->getTable('eav_entity_attribute'), 'c' => 'attribute_set_id'),
                'eav_entity_type' => array('t' => $moduleResource->getTable('eav_entity_type'), 'c' => 'default_attribute_set_id'),
            );

            $connection->truncateTable($eavAttributeSetTable);
            $connection->insertMultiple($eavAttributeSetTable, $eavAttributeSetRepaired);

            foreach ($mapOldIdsToNewIdsMoveStep0 as $oldId => $newId) {
                foreach ($attributeSetUpdateTables as $table) {
                    $connection->update($table['t'], array($table['c'] => $newId), array($table['c'] . ' = ?' => $oldId));
                }
            }

            foreach ($mapOldIdsToNewIdsMoveStep1 as $oldId => $newId) {
                foreach ($attributeSetUpdateTables as $table) {
                    $connection->update($table['t'], array($table['c'] => $newId), array($table['c'] . ' = ?' => $oldId));
                }
            }
        }

        $connection->query('SET `foreign_key_checks` = 1;');
        $timeEnd = microtime(true);
        $timeDiff = $timeEnd - $timeStart;
        $this->output->write('Repaired EAV attribute set IDs in ' . $this->formatTime($timeDiff), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->setupProgress();

        try {
            $this->healAttributeSets();
        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}