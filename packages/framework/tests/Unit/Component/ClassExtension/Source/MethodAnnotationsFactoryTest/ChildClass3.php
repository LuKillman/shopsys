<?php

declare(strict_types=1);

namespace Tests\FrameworkBundle\Unit\Component\ClassExtension\Source\MethodAnnotationsFactoryTest;

class ChildClass3 extends BaseClass3
{
    /**
     * @return \Shopsys\ShopBundle\Model\Category\CategoryFacade
     */
    public function getCategoryFacade()
    {
    }
}
