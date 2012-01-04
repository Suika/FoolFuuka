<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
if (!isset($modifiers))
	$modifiers = array();

if (isset($thread_id))
	echo form_open_multipart(get_selected_board()->shortname .'/thread/' . $thread_id, array('id' => 'postform'));
?>

<div class="content">

<?php
foreach ($posts as $key => $post) : ?>
	<?php if (isset($post['op'])) :
		$op = $post['op'];
	?>
	<div id="p<?php echo $op->num ?>">
		<?php if ($op->media_filename) : ?>
		<span>File: <?php echo byte_format($op->media_size, 0) . ', ' . $op->media_w . 'x' . $op->media_h . ', ' . $op->media; ?> <?php echo '<!-- ' . substr($op->media_hash, 0, -2) . '-->' ?></span>
		[<a href="<?php echo site_url($this->fu_board . '/image/' . urlencode(substr($op->media_hash, 0, -2))) ?>">View Same</a>] [<a href="http://iqdb.org/?url=<?php echo $op->thumbnail_href ?>">iqdb</a>] [<a href="http://google.com/searchbyimage?image_url=<?php echo $op->thumbnail_href ?>">Google</a>] [<a href="http://saucenao.com/search.php?url=<?php echo $op->thumbnail_href ?>">SauceNAO</a>]
		<br />
		<a href="<?php echo ($op->image_href)?$op->image_href:$op->remote_image_href ?>" rel="noreferrer"><img class="thumb" src="<?php echo $op->thumbnail_href ?>" alt="<?php echo $op->num ?>" width="<?php echo $op->preview_w ?>" height="<?php echo $op->preview_h ?>"/></a>
		<?php endif; ?>

		<label>
			<input type="checkbox" name="delete[]" value="<?php echo $op->doc_id ?>"/>
			<span class="postername"><?php echo $op->name ?></span> <?php echo date('D M d H:i:s Y', $op->timestamp + 18000) ?>
		</label>

		<?php if(!isset($thread_id)) : ?>
		<a class="js" href="<?php echo site_url($this->fu_board . '/thread/' . $op->num) ?>">No.<?php echo $op->num ?></a> [<a href="<?php echo site_url($this->fu_board . '/thread/' . $op->num) ?>">Reply</a>] [<a href="http://boards.4chan.org/<?php echo $this->fu_board . '/res/' . $op->num ?>">Original</a>]
		<?php else : ?>
		<a class="js" href="<?php echo site_url($this->fu_board . '/thread/' . $op->num) ?>">No.</a><a class="js" href="javascript:insert('>><?php echo $op->num ?>\n')"><?php echo $op->num ?></a> [<a href="<?php echo site_url($this->fu_board . '/thread/' . $op->num) ?>">Reply</a>] [<a href="http://boards.4chan.org/<?php echo $this->fu_board . '/res/' . $op->num ?>">Original</a>]
		<?php endif; ?>

		<blockquote><p><?php echo $op->comment_processed ?></p></blockquote>
		<?php echo ((isset($post['omitted']) && $post['omitted'] > 0) ? '<span class="omittedposts">' . $post['omitted'] . ' posts omitted. Click Reply to view.</span>' : '') ?>
	</div>
	<?php endif; ?>

	<?php
	if (isset($post['posts']))
	{
		if (isset($posts_per_thread))
		{
			$limit = count($post['posts']) - $posts_per_thread;
			if ($limit < 0)
				$limit = 0;
		}
		else
		{
			$limit = 0;
		}

		foreach ($post['posts'] as $p)
		{

			if ($p->parent == 0)
				$p->parent = $p->num;

			if (isset($thread_id))
			{
				echo build_board_comment($p, $modifiers, $thread_id);
			}
			else
			{
				echo build_board_comment($p, $modifiers);
			}
		}
	}
	?>
	<?php echo $template['partials']['post_reply']; ?>
	<br class="newthr" />
	<hr />
<?php endforeach; ?>
</div>
<?php
if (isset($thread_id))
{
	echo form_close();
}