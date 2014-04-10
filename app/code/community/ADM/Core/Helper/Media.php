<?php

class ADM_Core_Helper_Media extends Mage_Core_Helper_Abstract
{

	function resizeImage($path,$size)
	{
		$sizeTab = explode('x',$size);
		
		list($width,$height) = $sizeTab;
		list($widthCurrent, $heightCurrent, $type, $attr) = getimagesize($path);
		
	  if($widthCurrent<=$width and $heightCurrent<=$height){
	    return;
	  }

		$imageObj = new Varien_Image($path);
	
		$imageObj->constrainOnly(TRUE);
	
		$imageObj->keepAspectRatio(TRUE);
	
		$imageObj->keepFrame(false);
	
		$imageObj->keepTransparency(True);
		$imageObj->setImageBackgroundColor(false);
	
		$imageObj->backgroundColor(false);
	
		$imageObj->quality(100);
	
		$imageObj->setWatermarkImageOpacity(0);
	
		$imageObj->resize($width, $height);
	
		$imageObj->save($path);
	
	}
}