<?php
/**
 * YoutubeGallery
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use YouTubeGallery\Helper;

class VideoSource_Ustream
{
	public static function extractUstreamID($theLink)
	{
		//http://www.ustream.tv/channel/nasa-tv-wallops
		//http://www.ustream.tv/recorded/40925310 - recorded
		$l=explode('/',$theLink);
		if(count($l)>4)
			return $l[4];

		return '';

	}

	public static function renderUstreamPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		//http://www.dailymotion.com/doc/api/player.html

		$videoidkeyword='****youtubegallery-video-id****';

		$playerid='youtubegalleryplayerid_'.$videolist_row->id;

		$settings=array();


		if($options['es_colorone']!='')
		{
			$settings[]=array('ub',$options['es_colorone']);
			$settings[]=array('lc',$options['es_colorone']);
		}

		if($options['es_colortwo']!='')
		{
			$settings[]=array('oc',$options['es_colortwo']);
			$settings[]=array('uc',$options['es_colortwo']);
		}

		//$settings[]=array('info',$options['es_showinfo']);
		$settings[]=array('wmode','direct');

		Helper::ApplyPlayerParameters($settings,$options['es_youtubeparams']);
		$settingline=Helper::CreateParamLine($settings);

		$result='';

		$result.='<iframe '
			.' id="'.$playerid.'"';

		if(isset($options['es_title']))
			$result.=' alt="'.$options['es_title'].'"';

		$result.=' frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.ustream.tv/embed/recorded/'.$videoidkeyword.'?'.$settingline.'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.' scrolling="no" style="border: 0px none transparent;"></iframe>';

		return $result;
	}
}
