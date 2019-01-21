<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Load tl_content language file
 */
System::loadLanguageFile('tl_content');

$strTableName   = \IIDO\BasicBundle\Config\BundleConfig::getFileTable( __FILE__ );
$tableListener  = 'prestep.products.dca.product';

$objTable       = new \IIDO\BasicBundle\Dca\Table( $strTableName, false, true );



/**
 * Table Config
 */

$objTable->addTableConfig('ptable', 'tl_prestep_products_archive');
$objTable->addTableConfig('ctable', array('tl_article'));

$objTable->addTableConfig('switchToEdit', true);
$objTable->addTableConfig('enableVersioning', true);
$objTable->addTableConfig('enableVersioning', true);

$objTable->addTableConfig('onload_callback', array($tableListener,'checkPermission'));

$objTable->addTableConfig('oncut_callback', array($tableListener,'scheduleUpdate'));

$objTable->addTableConfig('ondelete_callback', array($tableListener,'scheduleUpdate'));

$objTable->addTableConfig('onsubmit_callback', array($tableListener,'adjustTime'));
$objTable->addTableConfig('onsubmit_callback', array($tableListener,'scheduleUpdate'));



/**
 * Table List
 */

$objTable->addSorting(4, array(
    'fields'                  => array('title DESC'),
    'headerFields'            => array('title', 'tstamp'),
    'panelLayout'             => 'filter;sort,search,limit',
    'child_record_callback'   => array($tableListener, 'listProductArticles'),
    'child_record_class'      => 'no_padding'
));

$objTable->addGlobalOperations(true);

$objTable->addOperations('edit,editHeader,copy,cut,delete,toggle,feature,show');



/**
 * Palettes
 */

$objTable->addPalette('default', '{title_legend},title,alias;{text_legend},subheadline,text;{image_legend},addImage;{enclosure_legend:hide},addEnclosure;{expert_legend:hide},cssClass;');



/**
 * Subpalettes
 */

$objTable->addSubpalette('addImage', 'singleSRC,size,floating,imagemargin,fullsize,overwriteMeta');
$objTable->addSubpalette('addEnclosure', 'enclosure');
$objTable->addSubpalette('overwriteMeta', 'alt,imageTitle,imageUrl,caption');



/**
 * Fields
 */

\IIDO\BasicBundle\Dca\Field::create('title')
    ->addEval('mandatory', true)
    ->addConfig('search', true)
    ->addConfig('sorting', true)
    ->addConfig('flag', 1)
    ->addToTable( $objTable );

\IIDO\BasicBundle\Dca\Field::create('alias', 'alias')->addToTable( $objTable );

\IIDO\BasicBundle\Dca\Field::create('subheadline')
    ->addConfig('search', true)
    ->addToTable( $objTable );

\IIDO\BasicBundle\Dca\Field::create('text', 'textarea');

$objTable->addImageFields();
//$objTable->addEnclosureFields();

\IIDO\BasicBundle\Dca\Field::create('cssClass')->addToTable( $objTable );


$objTable->createDca();
/**
 * Table tl_news
 */
//$GLOBALS['TL_DCA']['tl_news'] = array
//(
//
//
//	// Fields
//	'fields' => array
//	(
//
//
//
//
//
//
//
//		'addEnclosure' => array
//		(
//			'label'                   => &$GLOBALS['TL_LANG']['tl_news']['addEnclosure'],
//			'exclude'                 => true,
//			'inputType'               => 'checkbox',
//			'eval'                    => array('submitOnChange'=>true),
//			'sql'                     => "char(1) NOT NULL default ''"
//		),
//		'enclosure' => array
//		(
//			'label'                   => &$GLOBALS['TL_LANG']['tl_news']['enclosure'],
//			'exclude'                 => true,
//			'inputType'               => 'fileTree',
//			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'filesOnly'=>true, 'isDownloads'=>true, 'extensions'=>Config::get('allowedDownload'), 'mandatory'=>true),
//			'sql'                     => "blob NULL"
//		),
//
//
//	)
//);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @property Contao\News $News
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
//class tl_news extends Backend
//{
//
//	/**
//	 * Import the back end user object
//	 */
//	public function __construct()
//	{
//		parent::__construct();
//		$this->import('BackendUser', 'User');
//	}
//
//
//	/**
//	 * Check permissions to edit table tl_news
//	 *
//	 * @throws Contao\CoreBundle\Exception\AccessDeniedException
//	 */
//	public function checkPermission()
//	{
//		$bundles = System::getContainer()->getParameter('kernel.bundles');
//
//		// HOOK: comments extension required
//		if (!isset($bundles['ContaoCommentsBundle']))
//		{
//			$key = array_search('allowComments', $GLOBALS['TL_DCA']['tl_news']['list']['sorting']['headerFields']);
//			unset($GLOBALS['TL_DCA']['tl_news']['list']['sorting']['headerFields'][$key]);
//		}
//
//		if ($this->User->isAdmin)
//		{
//			return;
//		}
//
//		// Set the root IDs
//		if (!\is_array($this->User->news) || empty($this->User->news))
//		{
//			$root = array(0);
//		}
//		else
//		{
//			$root = $this->User->news;
//		}
//
//		$id = \strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;
//
//		// Check current action
//		switch (Input::get('act'))
//		{
//			case 'paste':
//				// Allow
//				break;
//
//			case 'create':
//				if (!\strlen(Input::get('pid')) || !\in_array(Input::get('pid'), $root))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to create news items in news archive ID ' . Input::get('pid') . '.');
//				}
//				break;
//
//			case 'cut':
//			case 'copy':
//				if (!\in_array(Input::get('pid'), $root))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' news item ID ' . $id . ' to news archive ID ' . Input::get('pid') . '.');
//				}
//				// NO BREAK STATEMENT HERE
//
//			case 'edit':
//			case 'show':
//			case 'delete':
//			case 'toggle':
//			case 'feature':
//				$objArchive = $this->Database->prepare("SELECT pid FROM tl_news WHERE id=?")
//											 ->limit(1)
//											 ->execute($id);
//
//				if ($objArchive->numRows < 1)
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid news item ID ' . $id . '.');
//				}
//
//				if (!\in_array($objArchive->pid, $root))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' news item ID ' . $id . ' of news archive ID ' . $objArchive->pid . '.');
//				}
//				break;
//
//			case 'select':
//			case 'editAll':
//			case 'deleteAll':
//			case 'overrideAll':
//			case 'cutAll':
//			case 'copyAll':
//				if (!\in_array($id, $root))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to access news archive ID ' . $id . '.');
//				}
//
//				$objArchive = $this->Database->prepare("SELECT id FROM tl_news WHERE pid=?")
//											 ->execute($id);
//
//				if ($objArchive->numRows < 1)
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid news archive ID ' . $id . '.');
//				}
//
//				/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
//				$objSession = System::getContainer()->get('session');
//
//				$session = $objSession->all();
//				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
//				$objSession->replace($session);
//				break;
//
//			default:
//				if (\strlen(Input::get('act')))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid command "' . Input::get('act') . '".');
//				}
//				elseif (!\in_array($id, $root))
//				{
//					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to access news archive ID ' . $id . '.');
//				}
//				break;
//		}
//	}
//
//
//	/**
//	 * Auto-generate the news alias if it has not been set yet
//	 *
//	 * @param mixed         $varValue
//	 * @param DataContainer $dc
//	 *
//	 * @return string
//	 *
//	 * @throws Exception
//	 */
//	public function generateAlias($varValue, DataContainer $dc)
//	{
//		$autoAlias = false;
//
//		// Generate alias if there is none
//		if ($varValue == '')
//		{
//			$autoAlias = true;
//			$varValue = StringUtil::generateAlias($dc->activeRecord->headline);
//		}
//
//		$objAlias = $this->Database->prepare("SELECT id FROM tl_news WHERE alias=? AND id!=?")
//								   ->execute($varValue, $dc->id);
//
//		// Check whether the news alias exists
//		if ($objAlias->numRows)
//		{
//			if (!$autoAlias)
//			{
//				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
//			}
//
//			$varValue .= '-' . $dc->id;
//		}
//
//		return $varValue;
//	}
//
//
//	/**
//	 * Set the timestamp to 00:00:00 (see #26)
//	 *
//	 * @param integer $value
//	 *
//	 * @return integer
//	 */
//	public function loadDate($value)
//	{
//		return strtotime(date('Y-m-d', $value) . ' 00:00:00');
//	}
//
//
//	/**
//	 * Set the timestamp to 1970-01-01 (see #26)
//	 *
//	 * @param integer $value
//	 *
//	 * @return integer
//	 */
//	public function loadTime($value)
//	{
//		return strtotime('1970-01-01 ' . date('H:i:s', $value));
//	}
//
//
//	/**
//	 * List a news article
//	 *
//	 * @param array $arrRow
//	 *
//	 * @return string
//	 */
//	public function listNewsArticles($arrRow)
//	{
//		return '<div class="tl_content_left">' . $arrRow['headline'] . ' <span style="color:#999;padding-left:3px">[' . Date::parse(Config::get('datimFormat'), $arrRow['date']) . ']</span></div>';
//	}
//
//
//	/**
//	 * Get all articles and return them as array
//	 *
//	 * @param DataContainer $dc
//	 *
//	 * @return array
//	 */
//	public function getArticleAlias(DataContainer $dc)
//	{
//		$arrPids = array();
//		$arrAlias = array();
//
//		if (!$this->User->isAdmin)
//		{
//			foreach ($this->User->pagemounts as $id)
//			{
//				$arrPids[] = $id;
//				$arrPids = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
//			}
//
//			if (empty($arrPids))
//			{
//				return $arrAlias;
//			}
//
//			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") ORDER BY parent, a.sorting")
//									   ->execute($dc->id);
//		}
//		else
//		{
//			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
//									   ->execute($dc->id);
//		}
//
//		if ($objAlias->numRows)
//		{
//			System::loadLanguageFile('tl_article');
//
//			while ($objAlias->next())
//			{
//				$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['COLS'][$objAlias->inColumn] ?: $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
//			}
//		}
//
//		return $arrAlias;
//	}
//
//
//	/**
//	 * Add the source options depending on the allowed fields (see #5498)
//	 *
//	 * @param DataContainer $dc
//	 *
//	 * @return array
//	 */
//	public function getSourceOptions(DataContainer $dc)
//	{
//		if ($this->User->isAdmin)
//		{
//			return array('default', 'internal', 'article', 'external');
//		}
//
//		$arrOptions = array('default');
//
//		// Add the "internal" option
//		if ($this->User->hasAccess('tl_news::jumpTo', 'alexf'))
//		{
//			$arrOptions[] = 'internal';
//		}
//
//		// Add the "article" option
//		if ($this->User->hasAccess('tl_news::articleId', 'alexf'))
//		{
//			$arrOptions[] = 'article';
//		}
//
//		// Add the "external" option
//		if ($this->User->hasAccess('tl_news::url', 'alexf'))
//		{
//			$arrOptions[] = 'external';
//		}
//
//		// Add the option currently set
//		if ($dc->activeRecord && $dc->activeRecord->source != '')
//		{
//			$arrOptions[] = $dc->activeRecord->source;
//			$arrOptions = array_unique($arrOptions);
//		}
//
//		return $arrOptions;
//	}
//
//
//	/**
//	 * Adjust start end end time of the event based on date, span, startTime and endTime
//	 *
//	 * @param DataContainer $dc
//	 */
//	public function adjustTime(DataContainer $dc)
//	{
//		// Return if there is no active record (override all)
//		if (!$dc->activeRecord)
//		{
//			return;
//		}
//
//		$arrSet['date'] = strtotime(date('Y-m-d', $dc->activeRecord->date) . ' ' . date('H:i:s', $dc->activeRecord->time));
//		$arrSet['time'] = $arrSet['date'];
//
//		$this->Database->prepare("UPDATE tl_news %s WHERE id=?")->set($arrSet)->execute($dc->id);
//	}
//
//
//	/**
//	 * Check for modified news feeds and update the XML files if necessary
//	 */
//	public function generateFeed()
//	{
//		/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
//		$objSession = System::getContainer()->get('session');
//
//		$session = $objSession->get('news_feed_updater');
//
//		if (!\is_array($session) || empty($session))
//		{
//			return;
//		}
//
//		$this->import('News');
//
//		foreach ($session as $id)
//		{
//			$this->News->generateFeedsByArchive($id);
//		}
//
//		$this->import('Automator');
//		$this->Automator->generateSitemap();
//
//		$objSession->set('news_feed_updater', null);
//	}
//
//
//	/**
//	 * Schedule a news feed update
//	 *
//	 * This method is triggered when a single news item or multiple news
//	 * items are modified (edit/editAll), moved (cut/cutAll) or deleted
//	 * (delete/deleteAll). Since duplicated items are unpublished by default,
//	 * it is not necessary to schedule updates on copyAll as well.
//	 *
//	 * @param DataContainer $dc
//	 */
//	public function scheduleUpdate(DataContainer $dc)
//	{
//		// Return if there is no ID
//		if (!$dc->activeRecord || !$dc->activeRecord->pid || Input::get('act') == 'copy')
//		{
//			return;
//		}
//
//		/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
//		$objSession = System::getContainer()->get('session');
//
//		// Store the ID in the session
//		$session = $objSession->get('news_feed_updater');
//		$session[] = $dc->activeRecord->pid;
//		$objSession->set('news_feed_updater', array_unique($session));
//	}
//
//
//	/**
//	 * Return the "feature/unfeature element" button
//	 *
//	 * @param array  $row
//	 * @param string $href
//	 * @param string $label
//	 * @param string $title
//	 * @param string $icon
//	 * @param string $attributes
//	 *
//	 * @return string
//	 */
//	public function iconFeatured($row, $href, $label, $title, $icon, $attributes)
//	{
//		if (\strlen(Input::get('fid')))
//		{
//			$this->toggleFeatured(Input::get('fid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
//			$this->redirect($this->getReferer());
//		}
//
//		// Check permissions AFTER checking the fid, so hacking attempts are logged
//		if (!$this->User->hasAccess('tl_news::featured', 'alexf'))
//		{
//			return '';
//		}
//
//		$href .= '&amp;fid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);
//
//		if (!$row['featured'])
//		{
//			$icon = 'featured_.svg';
//		}
//
//		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['featured'] ? 1 : 0) . '"').'</a> ';
//	}
//
//
//	/**
//	 * Feature/unfeature a news item
//	 *
//	 * @param integer       $intId
//	 * @param boolean       $blnVisible
//	 * @param DataContainer $dc
//	 *
//	 * @throws Contao\CoreBundle\Exception\AccessDeniedException
//	 */
//	public function toggleFeatured($intId, $blnVisible, DataContainer $dc=null)
//	{
//		// Check permissions to edit
//		Input::setGet('id', $intId);
//		Input::setGet('act', 'feature');
//		$this->checkPermission();
//
//		// Check permissions to feature
//		if (!$this->User->hasAccess('tl_news::featured', 'alexf'))
//		{
//			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to feature/unfeature news item ID ' . $intId . '.');
//		}
//
//		$objVersions = new Versions('tl_news', $intId);
//		$objVersions->initialize();
//
//		// Trigger the save_callback
//		if (\is_array($GLOBALS['TL_DCA']['tl_news']['fields']['featured']['save_callback']))
//		{
//			foreach ($GLOBALS['TL_DCA']['tl_news']['fields']['featured']['save_callback'] as $callback)
//			{
//				if (\is_array($callback))
//				{
//					$this->import($callback[0]);
//					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
//				}
//				elseif (\is_callable($callback))
//				{
//					$blnVisible = $callback($blnVisible, $this);
//				}
//			}
//		}
//
//		// Update the database
//		$this->Database->prepare("UPDATE tl_news SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
//					   ->execute($intId);
//
//		$objVersions->create();
//	}
//
//
//	/**
//	 * Return the "toggle visibility" button
//	 *
//	 * @param array  $row
//	 * @param string $href
//	 * @param string $label
//	 * @param string $title
//	 * @param string $icon
//	 * @param string $attributes
//	 *
//	 * @return string
//	 */
//	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
//	{
//		if (\strlen(Input::get('tid')))
//		{
//			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
//			$this->redirect($this->getReferer());
//		}
//
//		// Check permissions AFTER checking the tid, so hacking attempts are logged
//		if (!$this->User->hasAccess('tl_news::published', 'alexf'))
//		{
//			return '';
//		}
//
//		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);
//
//		if (!$row['published'])
//		{
//			$icon = 'invisible.svg';
//		}
//
//		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
//	}
//
//
//	/**
//	 * Disable/enable a user group
//	 *
//	 * @param integer       $intId
//	 * @param boolean       $blnVisible
//	 * @param DataContainer $dc
//	 */
//	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
//	{
//		// Set the ID and action
//		Input::setGet('id', $intId);
//		Input::setGet('act', 'toggle');
//
//		if ($dc)
//		{
//			$dc->id = $intId; // see #8043
//		}
//
//		// Trigger the onload_callback
//		if (\is_array($GLOBALS['TL_DCA']['tl_news']['config']['onload_callback']))
//		{
//			foreach ($GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'] as $callback)
//			{
//				if (\is_array($callback))
//				{
//					$this->import($callback[0]);
//					$this->{$callback[0]}->{$callback[1]}($dc);
//				}
//				elseif (\is_callable($callback))
//				{
//					$callback($dc);
//				}
//			}
//		}
//
//		// Check the field access
//		if (!$this->User->hasAccess('tl_news::published', 'alexf'))
//		{
//			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish news item ID ' . $intId . '.');
//		}
//
//		// Set the current record
//		if ($dc)
//		{
//			$objRow = $this->Database->prepare("SELECT * FROM tl_news WHERE id=?")
//									 ->limit(1)
//									 ->execute($intId);
//
//			if ($objRow->numRows)
//			{
//				$dc->activeRecord = $objRow;
//			}
//		}
//
//		$objVersions = new Versions('tl_news', $intId);
//		$objVersions->initialize();
//
//		// Trigger the save_callback
//		if (\is_array($GLOBALS['TL_DCA']['tl_news']['fields']['published']['save_callback']))
//		{
//			foreach ($GLOBALS['TL_DCA']['tl_news']['fields']['published']['save_callback'] as $callback)
//			{
//				if (\is_array($callback))
//				{
//					$this->import($callback[0]);
//					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
//				}
//				elseif (\is_callable($callback))
//				{
//					$blnVisible = $callback($blnVisible, $dc);
//				}
//			}
//		}
//
//		$time = time();
//
//		// Update the database
//		$this->Database->prepare("UPDATE tl_news SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
//					   ->execute($intId);
//
//		if ($dc)
//		{
//			$dc->activeRecord->tstamp = $time;
//			$dc->activeRecord->published = ($blnVisible ? '1' : '');
//		}
//
//		// Trigger the onsubmit_callback
//		if (\is_array($GLOBALS['TL_DCA']['tl_news']['config']['onsubmit_callback']))
//		{
//			foreach ($GLOBALS['TL_DCA']['tl_news']['config']['onsubmit_callback'] as $callback)
//			{
//				if (\is_array($callback))
//				{
//					$this->import($callback[0]);
//					$this->{$callback[0]}->{$callback[1]}($dc);
//				}
//				elseif (\is_callable($callback))
//				{
//					$callback($dc);
//				}
//			}
//		}
//
//		$objVersions->create();
//	}
//}
