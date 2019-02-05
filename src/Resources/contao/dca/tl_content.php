<?php
/*******************************************************************
 *
 * (c) 2019 Stephan Preßl, www.prestep.at <development@prestep.at>
 * All rights reserved
 *
 * Modification, distribution or any other action on or with
 * this file is permitted unless explicitly granted by IIDO
 * www.iido.at <development@iido.at>
 *
 *******************************************************************/

if( Input::get("do") === "prestepProducts" )
{
//    $objTable->addTableConfig('ptable', 'tl_prestep_product');
//    $objTable->addTableConfig('ctable', array('tl_content'));

    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_prestep_product_article';
}