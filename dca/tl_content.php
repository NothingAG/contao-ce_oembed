<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  Nothing Interactive 2013 <https://www.nothing.ch/>
 * @author     Weyert de Boer <sprog@nothing.ch>
 * @author     Lukas Walliser <xari@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Add a palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['oembed'] = '{title_legend},name,type,headline;{config_legend},oembed_url,oembed_maxwidth,oembed_maxheight;{expert_legend:hide},cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['oembed_url'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['oembed_url'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'decodeEntities' => true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);