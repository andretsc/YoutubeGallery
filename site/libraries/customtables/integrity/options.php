<?php
/**
 * CustomTables Joomla! 3.x Native Component
 * @package Custom Tables
 * @subpackage integrity/fields.php
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @copyright Copyright (C) 2018-2020. All Rights Reserved
 * @license GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 **/
 
namespace CustomTables\Integrity;
 
defined('_JEXEC') or die('Restricted access');

use CustomTables\Fields;

use \Joomla\CMS\Factory;

class IntegrityOptions extends \CustomTables\IntegrityChecks
{
	public static function checkOptions(&$ct)
	{
		$jinput = Factory::getApplication()->input;
		
		IntegrityOptions::checkOptionsTitleFields($ct);
	}
	
	protected static function checkOptionsTitleFields(&$ct)
    {
		$db = Factory::getDBO();
		
		$column_name_field = 'column_name';
		
        $table_name='#__customtables_options';

        $g_ExistingFields=Fields::getExistingFields($table_name,false);

        $morethanonelang=false;
		foreach($ct->Languages->LanguageList as $lang)
        {
           	$g_fieldname='title';
            if($morethanonelang)
				$g_fieldname.='_'.$lang->sef;

            $g_found=false;

            foreach($g_ExistingFields as $g_existing_field)
            {
				$g_exst_field=$g_existing_field[$column_name_field];
                if($g_exst_field==$g_fieldname)
                {
					$g_found=true;
                    break;
                }
            }

            if(!$g_found)
            {
				Fields::AddMySQLFieldNotExist($table_name, $g_fieldname, 'varchar(100) null', '');
				Factory::getApplication()->enqueueMessage('Options Field "'.$g_fieldname.'" added.','notice');
            }
			$morethanonelang=true;
        }
    }
}