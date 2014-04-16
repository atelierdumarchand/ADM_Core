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
}