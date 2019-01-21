<?php
/*******************************************************************
 * (c) 2019 Stephan Preßl, www.prestep.at <development@prestep.at>
 * All rights reserved
 * Modification, distribution or any other action on or with
 * this file is permitted unless explicitly granted by PRESTEP
 * www.prestep.at <development@prestep.at>
 *******************************************************************/

namespace PRESTEP\ProductsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use PRESTEP\ProductsBundle\DependencyInjection\PRESTEPBookingPlanExtension;


/**
 * Configures the Contao PRESTEP Products Bundle.
 *
 * @author Stephan Preßl <development@prestep.at>
 */
class PRESTEPProductsBundle extends Bundle
{

    /**
     * Register extension
     *
     * @return PRESTEPProductsExtension
     */
    public function getContainerExtension()
    {
        return new PRESTEPProductsExtension();
    }
}
