<?php

class  ADM_Core_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
{
    protected $_pager_route = '*/*/*';

    protected function _construct()
    {
        parent::_construct();
        $this->setAjaxAnchor('adm-core-ajax-pager');
    }


    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setChild('js.for.ajax',$this->getLayout()->createBlock('core/template')
                                                        ->setTemplate('adm/core/page/html/pager/jsforajax.phtml')
                       );

        return $this;
    }

    /**
     *
     * @param string $route
     */
    public function setPagerRoute($route = '')
    {
        $this->_pager_route = $route;
    }

    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        $pagerUrl = $this->getUrl($this->_pager_route, $urlParams);

        return $pagerUrl;
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        return $html . $this->getChild('js.for.ajax')
                             ->setAjaxAnchor($this->getAjaxAnchor())
                             ->toHtml();
    }
}