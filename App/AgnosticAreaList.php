<?php

declare(strict_types=1);

namespace Opengento\Application\App;

use Magento\Framework\App\AreaList;

class AgnosticAreaList extends AreaList
{
    public function getCodeByFrontName($frontName): string
    {
        $frontName = (string)$frontName;
        foreach ($this->_areas as $areaCode => $areaInfo) {
            $areaFrontName = $areaInfo['frontName'] ?? null;
            if ($areaFrontName === null && isset($areaInfo['frontNameResolver'])) {
                $areaFrontName = $this->_resolverFactory->create($areaInfo['frontNameResolver'])->getFrontName(true);
            }
            if ($areaFrontName === $frontName) {
                return $areaCode;
            }
        }
        return $this->_defaultAreaCode;
    }
}
