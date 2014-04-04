<?php
class ADM_Core_Model_System_Config_Backend_Integer extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if (!is_numeric($this->getValue()) && $this->getValue()!='' || $this->getValue()<0) {
            throw new Exception(Mage::helper('adm_core')->__('"%s" must be an integer positive or null.', $this->getFieldConfig()->label));
        }
        return $this;
    }
}
