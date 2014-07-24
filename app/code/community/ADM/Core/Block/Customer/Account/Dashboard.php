<?php

class  ADM_Core_Block_Customer_Account_Dashboard extends Mage_Core_Block_Template
{
    public function hasChilds()
    {
        if(parent::getChild('adm.info1')) {
            return true;
        } else {
            return false;
        }

    }

}
