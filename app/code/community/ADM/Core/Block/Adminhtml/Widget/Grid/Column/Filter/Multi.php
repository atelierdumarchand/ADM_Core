<?php

class ADM_Core_Block_Adminhtml_Widget_Grid_Column_Filter_Multi
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{

    public function getCondition()
    {
        if (is_null($this->getValue())) {
            return null;
        }

        $cond = array('like' => '%' . $this->getValue() . '%');

        return $cond;
    }

}
