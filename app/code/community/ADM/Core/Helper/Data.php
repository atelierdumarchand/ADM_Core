<?php

class ADM_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get nodes for the given path and format them to get a array
     * with keys depending of the nodes content
     * - code (corresponding to the node key)
     * - id (extract from the node key if it's formatted as id_*** else code)
     * - label (label if defined in node else code)
     * other keys in node should be preserved
     *
     * @param array $config
     */
    public function getConfigToArray($path, $key='id')
    {
        if(Mage::getConfig()->getNode($path)) {
            $configNodes = Mage::getConfig()->getNode($path)->children();
        } else {
            $configNodes = false;
        }

        $config = array();
        if ($configNodes) {
            foreach($configNodes as $code => $node){
                $configLine = $node->asArray();

                //With an unset key do not take the entire line
                if(!empty($configLine['unset'])) {
                    continue;
                }

                if(empty($configLine['code'])) {
                    $configLine['code']=(string)$code;
                }

                if(empty($configLine['id'])) {
                    if(preg_match('/^id_(\d+)/', (string)$code, $match)) {
                        $configLine['id']= $match[1];
                    } else {
                        $configLine['id']= $configLine['code'];
                    }
                }

                if (empty($configLine['label'])) {
                    $configLine['label']= $configLine['code'];
                }

                $configKey = (!empty($configLine[$key])) ? $configLine[$key] : $configLine['id'];

                $config[$configKey] = new Varien_Object($configLine);
            }
        }

        return $config;
    }


    public function getConfigToOptions($path, $moduleHelper= false)
    {
        $options = array();
        $config = $this->getConfigToArray($path);
        foreach ($config as $configOption) {
            $options[$configOption->getId()] = ($moduleHelper and method_exists($moduleHelper, '__')) ? $moduleHelper->__($configOption->getLabel()) : $configOption->getLabel();
        }

        return $options;
    }


    /**
     *
     * @param string $attributeCode
     * @param int $itemId
     * @param int $storeId
     * @param string $entityType
     */
    public function getAttributeOptionLabel($attributeCode, $itemId, $storeId=0, $entityType='catalog_product')
    {
        $cacheKey = 'ADM_CORE_ATTRIBUTE_' . $attributeCode . '_STORE_' . $storeId;
        if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey)) {
            $options = unserialize($cache);
        } else {
            $options=array();

            $entityType = Mage::getModel('eav/config')->getEntityType($entityType);
            $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, $attributeCode);
            $attributeCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setAttributeFilter($attributeModel->getId())
            ->setStoreFilter(0)
            ->load();

            foreach( $attributeCollection->toOptionArray() as $_cur_option ) {
                $options[$_cur_option['value']]= $_cur_option['label'];
            }


            if (Mage::app()->useCache('config')) {
                Mage::app()->saveCache(serialize($options), $cacheKey, array('config'));
            }
        }

        if (isset($options[$itemId])) {
            return $options[$itemId];
        } else {
            return '';
        }
    }

}