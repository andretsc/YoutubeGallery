<?php
/**
 * CustomTables Joomla! 3.x Native Component
 * @package Custom Tables
 * @subpackage integrity/tables.php
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @copyright Copyright (C) 2018-2021. All Rights Reserved
 * @license GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 **/

namespace CustomTables\Integrity;
 
defined('_JEXEC') or die('Restricted access');

use CustomTables\Fields;
//use CustomTables\Integrity\IntegrityFields;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;

use \ESTables;

class IntegrityCoreTables extends \CustomTables\IntegrityChecks
{
	public static function checkCoreTables(&$ct)
	{
		IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Tables());
		IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Fields());
		IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Layouts());
		IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Categories());
		IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Log());
		
		if($ct->Env->advancedtagprocessor)
		{
			IntegrityCoreTables::createCoreTableIfNotExists($ct,IntegrityCoreTables::getCoreTableFields_Options());
		}
	}
		
	protected static function createCoreTableIfNotExists(&$ct,$table)
	{
		if(!ESTables::checkIfTableExists($table->realtablename))
			IntegrityCoreTables::createCoreTable($ct,$table);
		else
			IntegrityCoreTables::checkCoreTable($ct,$table->realtablename,$table->fields);
	}
	
	protected static function getCoreTableFields_Tables()
	{
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=[];
		$tables_projected_fields[]=['name'=>'id','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_tables_seq\')'];
		$tables_projected_fields[]=['name'=>'published','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		
		$tables_projected_fields[]=['name'=>'tablename','ct_fieldtype'=>'string','ct_typeparams'=>100,'mysql_type'=>'VARCHAR(100) NOT NULL DEFAULT "tablename"','postgresql_type'=>'VARCHAR(100) NOT NULL DEFAULT \'\''];
		
		$tables_projected_fields[]=['name'=>'tabletitle','mysql_type'=>'VARCHAR(255) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(255) NULL DEFAULT NULL','multilang'=>true];
		$tables_projected_fields[]=['name'=>'description','mysql_type'=>'TEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL','multilang'=>true];

		//Not used inside Custom Tables
		
		$tables_projected_fields[]=['name'=>'tablecategory','mysql_type'=>'INT NULL DEFAULT NULL','postgresql_type'=>'INT NULL DEFAULT NULL'];
		
		$tables_projected_fields[]=['name'=>'customphp','ct_fieldtype'=>'link','mysql_type'=>'VARCHAR(1024) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(1024) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'customtablename','mysql_type'=>'VARCHAR(100) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(100) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'customidfield','mysql_type'=>'VARCHAR(100) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(100) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'allowimportcontent','mysql_type'=>'TINYINT NOT NULL DEFAULT 0','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 0'];
		
		$tables_projected_fields[]=['name'=>'created_by','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'modified_by','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'created','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'modified','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'checked_out','mysql_type'=>'int UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'checked_out_time','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];

		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_published','field'=>'published'];
		$tables_projected_indexes[]=['name'=>'idx_tablename','field'=>'tablename'];
		
		return (object)['realtablename' => $dbprefix . 'customtables_tables',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'List of Custom Tables tables'];
	}
	
	protected static function getCoreTableFields_Fields()
	{
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=array();
		
		$tables_projected_fields[]=['name'=>'id','ct_fieldtype'=>'_id','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_options_seq\')'];
		$tables_projected_fields[]=['name'=>'published','ct_fieldtype'=>'_published','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'tableid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL','postgresql_type'=>'INT NOT NULL'];		
		
		$tables_projected_fields[]=['name'=>'fieldname','ct_fieldtype'=>'string','ct_typeparams'=>100,'mysql_type'=>'VARCHAR(100) NOT NULL','postgresql_type'=>'VARCHAR(100) NOT NULL'];
		$tables_projected_fields[]=['name'=>'fieldtitle','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(255) NULL','postgresql_type'=>'VARCHAR(255) NULL DEFAULT NULL','multilang'=>true];
		$tables_projected_fields[]=['name'=>'description','ct_fieldtype'=>'','mysql_type'=>'TEXT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL','multilang'=>true];
		
		$tables_projected_fields[]=['name'=>'allowordering','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'isrequired','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'isdisabled','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 0','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'alwaysupdatevalue','ct_fieldtype'=>'checkbox','mysql_type'=>'TINYINT NOT NULL DEFAULT 0','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 0','comment'=>'Update default value every time record is edited.'];
		
		$tables_projected_fields[]=['name'=>'parentid','ct_fieldtype'=>'sqljoin','mysql_type'=>'INT NULL','postgresql_type'=>'INT NULL'];		
		$tables_projected_fields[]=['name'=>'ordering','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL','postgresql_type'=>'INT NOT NULL'];		

		$tables_projected_fields[]=['name'=>'defaultvalue','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) NULL','postgresql_type'=>'VARCHAR(1024) NULL'];
		$tables_projected_fields[]=['name'=>'customfieldname','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(100) NULL','postgresql_type'=>'VARCHAR(100) NULL'];
		$tables_projected_fields[]=['name'=>'type','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(50) NULL','postgresql_type'=>'VARCHAR(50) NULL'];
		$tables_projected_fields[]=['name'=>'typeparams','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) NULL','postgresql_type'=>'VARCHAR(1024) NULL'];
		$tables_projected_fields[]=['name'=>'valuerule','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) NULL','postgresql_type'=>'VARCHAR(1024) NULL'];
		$tables_projected_fields[]=['name'=>'valuerulecaption','ct_fieldtype'=>'string','ct_typeparams'=>'1024','mysql_type'=>'VARCHAR(1024) NULL','postgresql_type'=>'VARCHAR(1024) NULL'];

		$tables_projected_fields[]=['name'=>'created_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'modified_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'created','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL','postgresql_type'=>'TIMESTAMP(0) NULL'];
		$tables_projected_fields[]=['name'=>'modified','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL','postgresql_type'=>'TIMESTAMP(0) NULL'];
		$tables_projected_fields[]=['name'=>'checked_out','ct_fieldtype'=>'','mysql_type'=>'int UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'checked_out_time','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		  
		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_published','field'=>'published'];
		$tables_projected_indexes[]=['name'=>'idx_tableid','field'=>'tableid'];
		$tables_projected_indexes[]=['name'=>'idx_fieldname','field'=>'fieldname'];

		return (object)['realtablename' => $dbprefix . 'customtables_fields',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'Custom Tables Fields'];
	}
	
	protected static function getCoreTableFields_Layouts()
	{
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=array();
		
		$tables_projected_fields[]=['name'=>'id','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_options_seq\')'];
		$tables_projected_fields[]=['name'=>'published','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'tableid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL','postgresql_type'=>'INT NOT NULL'];	
		
		$tables_projected_fields[]=['name'=>'layoutname','ct_fieldtype'=>'string','ct_typeparams'=>100,'mysql_type'=>'VARCHAR(100) NOT NULL DEFAULT "tablename"','postgresql_type'=>'VARCHAR(100) NOT NULL DEFAULT \'\''];
		$tables_projected_fields[]=['name'=>'layouttype','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];		
		
		$tables_projected_fields[]=['name'=>'layoutcode','ct_fieldtype'=>'text','mysql_type'=>'MEDIUMTEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'layoutmobile','ct_fieldtype'=>'text','mysql_type'=>'MEDIUMTEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'layoutcss','ct_fieldtype'=>'text','mysql_type'=>'MEDIUMTEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'layoutjs','ct_fieldtype'=>'text','mysql_type'=>'MEDIUMTEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL'];
		
		$tables_projected_fields[]=['name'=>'changetimestamp','ct_fieldtype'=>'','mysql_type'=>'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		
		$tables_projected_fields[]=['name'=>'created_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'modified_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'created','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'modified','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'checked_out','ct_fieldtype'=>'','mysql_type'=>'int UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'checked_out_time','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
				
		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_published','field'=>'published'];
		$tables_projected_indexes[]=['name'=>'idx_tableid','field'=>'tableid'];
		$tables_projected_indexes[]=['name'=>'idx_layoutname','field'=>'layoutname'];

		return (object)['realtablename' => $dbprefix . 'customtables_layouts',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'Custom Tables Layouts'];
	}

	protected static function getCoreTableFields_Categories()
	{		
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=array();
		
		$tables_projected_fields[]=['name'=>'id','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_options_seq\')'];
		$tables_projected_fields[]=['name'=>'published','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		
		$tables_projected_fields[]=['name'=>'categoryname','ct_fieldtype'=>'string','ct_typeparams'=>100,'mysql_type'=>'VARCHAR(100) NOT NULL DEFAULT "tablename"','postgresql_type'=>'VARCHAR(100) NOT NULL DEFAULT \'\''];
		
		$tables_projected_fields[]=['name'=>'created_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'modified_by','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'created','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'modified','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'checked_out','ct_fieldtype'=>'','mysql_type'=>'int UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'checked_out_time','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		
		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_published','field'=>'published'];
		$tables_projected_indexes[]=['name'=>'idx_categoryname','field'=>'categoryname'];

		return (object)['realtablename' => $dbprefix . 'customtables_categories',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'Custom Tables Categories'];
	}
	
	protected static function getCoreTableFields_Log()
	{
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=array();
		
		$tables_projected_fields[]=['name'=>'id','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_options_seq\')'];
		$tables_projected_fields[]=['name'=>'userid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];		
		$tables_projected_fields[]=['name'=>'datetime','ct_fieldtype'=>'','mysql_type'=>'DATETIME NULL DEFAULT NULL','postgresql_type'=>'TIMESTAMP(0) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'tableid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];		
		$tables_projected_fields[]=['name'=>'action','ct_fieldtype'=>'','mysql_type'=>'SMALLINT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'MALLINT NOT NULL DEFAULT 0'];		
		$tables_projected_fields[]=['name'=>'listingid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'Itemid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		  
		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_userid','field'=>'userid'];
		$tables_projected_indexes[]=['name'=>'idx_action','field'=>'action'];
  
		return (object)['realtablename' => $dbprefix . 'customtables_log',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'Custom Tables Action Log'];
	}
	
	protected static function getCoreTableFields_Options()
	{
		$conf = Factory::getConfig();
		$dbprefix = $conf->get('dbprefix');
		
		$tables_projected_fields=array();
		$tables_projected_fields[]=['name'=>'id','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT','postgresql_type'=>'id INT check (id > 0) NOT NULL DEFAULT NEXTVAL (\'#__customtables_options_seq\')'];
		$tables_projected_fields[]=['name'=>'published','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'optionname','ct_fieldtype'=>'string','ct_typeparams'=>50,'mysql_type'=>'VARCHAR(50) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'title','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(100) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(100) NULL DEFAULT NULL','multilang'=>true];
		
		$tables_projected_fields[]=['name'=>'image','ct_fieldtype'=>'','mysql_type'=>'BIGINT NULL','postgresql_type'=>'BIGINT NULL'];
		$tables_projected_fields[]=['name'=>'imageparams','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(100) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(100) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'ordering','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NOT NULL DEFAULT 0','postgresql_type'=>'INT NOT NULL DEFAULT 0'];
		$tables_projected_fields[]=['name'=>'parentid','ct_fieldtype'=>'','mysql_type'=>'INT UNSIGNED NULL','postgresql_type'=>'INT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'sublevel','ct_fieldtype'=>'','mysql_type'=>'INT NULL','postgresql_type'=>'INT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'isselectable','ct_fieldtype'=>'','mysql_type'=>'TINYINT NOT NULL DEFAULT 1','postgresql_type'=>'SMALLINT NOT NULL DEFAULT 1'];
		$tables_projected_fields[]=['name'=>'optionalcode','ct_fieldtype'=>'','mysql_type'=>'TEXT NULL DEFAULT NULL','postgresql_type'=>'TEXT NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'link','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(1024) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'familytree','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(1024) NULL DEFAULT NULL'];
		$tables_projected_fields[]=['name'=>'familytreestr','ct_fieldtype'=>'','mysql_type'=>'VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL','postgresql_type'=>'VARCHAR(1024) NULL DEFAULT NULL'];
  
		$tables_projected_indexes=[];
		$tables_projected_indexes[]=['name'=>'idx_published','field'=>'published'];
		$tables_projected_indexes[]=['name'=>'idx_optionname','field'=>'optionname'];
		//Specified key was too long; max key length is 767 bytes
		//$tables_projected_indexes[]=['name'=>'idx_familytree','field'=>'familytree'];
		//$tables_projected_indexes[]=['name'=>'idx_familytreestr','field'=>'familytreestr'];
  
		return (object)['realtablename' => $dbprefix . 'customtables_options',
			'fields' => $tables_projected_fields,
			'indexes' => $tables_projected_indexes,
			'comment' => 'Hierarchical structure records (Custom Tables field type)'];
	}
	
	protected static function prepareAddFieldQuery(&$ct,$fields,$db_type)
	{
		$db = Factory::getDBO();
		
		$fields_sql=[];
		foreach($fields as $field)
		{
			if(isset($field['multilang']) and $field['multilang'] == true)
			{
				$morethanonelang=false;
				foreach($ct->Languages->LanguageList as $lang)
				{
					$fieldname = $field['name'];
					
					if($morethanonelang)
						$fieldname.='_'.$lang->sef;

					$fields_sql[] = $db->quoteName($fieldname) . ' ' . $field[$db_type];
					
					$morethanonelang=true;
				}
			}
			else
			{
				$fields_sql[] = $db->quoteName($field['name']) . ' ' . $field[$db_type];
			}
		}
		
		return $fields_sql;
	}
	
	protected static function prepareAddIndexQuery($indexes)
	{
		$db = Factory::getDBO();
		
		$indexes_sql=[];
		foreach($indexes as $index)
		{
			$idx = $db->quoteName($index['name']);
			$fld = $db->quoteName($index['field']);
			$indexes_sql[] = 'KEY ' . $idx . ' (' . $fld . ')';
		}
		
		return $indexes_sql;
	}

	protected static function createCoreTable(&$ct,$table)
	{
		//TODO:
		//Add InnoDB Row Formats to config file
		//https://dev.mysql.com/doc/refman/5.7/en/innodb-row-format.html
		
		$conf = Factory::getConfig();
		$database = $conf->get('db');

		$db = Factory::getDBO();
		
		$fields_sql = IntegrityCoreTables::prepareAddFieldQuery($ct,$table->fields,($db->serverType == 'postgresql' ? 'postgresql_type' : 'mysql_type'));
		$indexes_sql = IntegrityCoreTables::prepareAddIndexQuery($table->indexes);

		if($db->serverType == 'postgresql')
		{
			//PostgreSQL

			$fields = Fields::getListOfExistingFields($table->realtablename, false);
			
			if(count($fields)==0)
			{
				//create new table
				$db->setQuery('CREATE SEQUENCE IF NOT EXISTS '.$table->realtablename.'_seq');
				$db->execute();
				
				$query = '
				CREATE TABLE IF NOT EXISTS '.$table->realtablename.'
				(
					'.implode(',',$fields_sql).',
					PRIMARY KEY (id)
				)';

				$db->setQuery( $query );
				$db->execute();
				
				$db->setQuery('ALTER SEQUENCE '.$table->realtablename.'_seq RESTART WITH 1');
				$db->execute();
				
				Factory::getApplication()->enqueueMessage('Table "'.$table->realtablename.'" added.','notice');			
				
				return true;
			}
		}
		else
		{
			//Mysql

			$query = '
			CREATE TABLE IF NOT EXISTS '.$table->realtablename.'
				(
					'.implode(',',$fields_sql).',
					PRIMARY KEY (id)
					
					'.(count($indexes_sql) > 0 ? ','.implode(',',$indexes_sql) : '').'
					
				) ENGINE=InnoDB'.(isset($table->comments) and $table->comments != null ? ' COMMENT='.$db->quoteName($table->comments) : '')
					.' DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;
			';

			$db->setQuery( $query );
			$db->execute();

			Factory::getApplication()->enqueueMessage('Table "'.$table->realtablename.'" added.','notice');			
			
			return true;
		}
		
		return false;
	}
	
	public static function checkCoreTableFields(&$ct,$realtablename, $ExistingFields, $realfieldname, $ct_fieldtype, $ct_typeparams = '')
	{
		$exst_field=null;
		foreach($ExistingFields as $existing_field)
		{
			if($existing_field['column_name']==$realfieldname)
			{
				$exst_field = $existing_field;
				break;
			}
		}
		
		if($exst_field == null)
		{
			die('field not created '.$realfieldname);
			return false;
		}
		
		if($ct_fieldtype != null and $ct_fieldtype != '')
		{
			$projected_data_type = Fields::getProjectedFieldType($ct_fieldtype, $ct_typeparams);
		
			if(!IntegrityFields::compareFieldTypes($existing_field,$projected_data_type))
			{
				$PureFieldType = Fields::makeProjectedFieldType($projected_data_type);

				if(!Fields::fixMYSQLField($realtablename,$realfieldname,$PureFieldType,$msg))
				{
					Factory::getApplication()->enqueueMessage($msg,'error');
					return false;
				}
			}
		}

		return true;
	}
	
	public static function checkCoreTable(&$ct,$realtablename, $projected_fields)
	{
		$db = Factory::getDBO();
		
		$ExistingFields=Fields::getExistingFields($realtablename, false);
		
		foreach($projected_fields as $projected_field)
		{
			
			if(isset($projected_field['ct_fieldtype']) and $projected_field['ct_fieldtype']!='')
			{
				$projected_realfieldname=$projected_field['name'];
				$fieldtype=$projected_field['ct_fieldtype'];
				
				$typeparams = '';

				if(IntegrityFields::addFieldIfNotExists($ct,$realtablename,$ExistingFields,$projected_realfieldname,$fieldtype,$typeparams))
					$ExistingFields=Fields::getExistingFields($realtablename, false);//reload list of existing fields if one field has been added.
        
				if(isset($projected_field['ct_fieldtype']) and $projected_field['ct_fieldtype']!='')
				{
					$ct_fieldtype = $projected_field['ct_fieldtype'];
					
					$typeparams='';
					if(isset($projected_field['ct_typeparams']) and $projected_field['ct_typeparams']!='')
						$typeparams = $projected_field['ct_typeparams'];
				
					IntegrityCoreTables::checkCoreTableFields($ct,$realtablename, $ExistingFields, $projected_realfieldname, $ct_fieldtype ,$typeparams);
				}
			}
		}
	}
}
