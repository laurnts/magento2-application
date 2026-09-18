<?php

declare(strict_types=1);

namespace Opengento\Application\App;

use Magento\Framework\App\AreaList;

class AgnosticAreaList extends AreaList
{
    public function getCodeByFrontName($frontName)
    {
        foreach ($this->_areas as $areaCode => $areaInfo) {
            if (!isset($areaInfo['frontName']) && isset($areaInfo['frontNameResolver'])) {
                $resolver = $this->_resolverFactory->create($areaInfo['frontNameResolver']);
                $areaInfo['frontName'] = $resolver->getFrontName(true);
            }
            if (isset($areaInfo['frontName']) && $areaInfo['frontName'] === $frontName) {
                return $areaCode;
            }
        }
        return $this->_defaultAreaCode;
    }
}
