<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file access');
defined('_JEXEC') or die('Restricted access');

?>
<?php foreach ($this->items as $i => $item): ?>

	<?php
	
		$link2edit='index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$item->id;
		$link2videolist='index.php?option=com_youtubegallery&view=videolist&listid='.$item->id;
	
		//$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		//$userChkOut = JFactory::getUser($item->checked_out);
	?>
	<tr class="row<?php echo $i % 2; ?>">

	<?php if ($this->canState or $this->canDelete): ?>	
		<td class="text-center">
				<?php /* if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<?php else: ?>
						&#9633;
					<?php endif; ?>
				<?php else: */ ?>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				<?php /* endif; */ ?>
		</td>
		<?php endif; ?>
		
		<td scope="row">
			<div class="name">
				<?php if ($this->canEdit): ?>
					<a href="<?php echo $link2edit; ?>"><?php echo $this->escape($item->es_listname); ?></a>
					<?php /* if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'linkslist.', $canCheckin); ?>
					<?php endif; */ ?>
				<?php else: ?>
					<?php echo $this->escape($item->es_listname); ?>
				<?php endif; ?>
			</div>
		</td>
		
		<td scope="row">
			<?php echo $item->categoryname; ?>
		</td>
                
		<td scope="row">
            <span>
				<?php
				if($item->es_updateperiod>=1)
				{
					echo JText::sprintf(JText::_('COM_YOUTUBEGALLERY_LASTUPDATE'),$item->es_lastplaylistupdate,$item->es_updateperiod);
				}
				else
				{
					$hours=round((24*$item->es_updateperiod),0);
                    echo JText::sprintf(JText::_('COM_YOUTUBEGALLERY_LASTUPDATE_HOURS'),$item->es_lastplaylistupdate,$hours);
                }
                ?>
			</span>
		</td>
                
		<td scope="row" style="text-align:center;">
			<a href="<?php echo $link2videolist; ?>"><?php echo $item->number_of_videos; ?></a>
		</td>
		
		<td class="text-center btns d-none d-md-table-cell">
		<?php if ($this->canState) : ?>
				<?php /* if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'linkslist.', true, 'cb'); ?>
					<?php else: ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'linkslist.', false, 'cb'); ?>
					<?php endif; ?>
				<?php else: */ ?>
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'linkslist.', true, 'cb'); ?>
				<?php /* endif; */ ?>
		<?php else: ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'linkslist.', false, 'cb'); ?>
		<?php endif; ?>

		</td>
		<td class="d-none d-md-table-cell">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
