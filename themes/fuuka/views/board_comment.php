<?php
if (!defined('DOCROOT'))
	exit('No direct script access allowed');

?>

<table>
	<tbody>
		<tr>
			<td class="doubledash">&gt;&gt;</td>
			<td class="<?= ($p->subnum > 0) ? 'subreply' : 'reply' ?>" id="<?= $p->num . (($p->subnum > 0) ? '_' . $p->subnum : '') ?>">
				<label>
					<input type="checkbox" name="delete[]" value="<?= $p->doc_id ?>"/>
					<?php if (isset($modifiers['post_show_board_name']) &&  $modifiers['post_show_board_name']): ?><span class="post_show_board">/<?= $p->board->shortname ?>/</span><?php endif; ?>
					<span class="filetitle"><?= $p->title_processed ?></span>
					<span class="postername<?= ($p->capcode == 'M') ? ' mod' : '' ?><?= ($p->capcode == 'A') ? ' admin' : '' ?><?= ($p->capcode == 'D') ? ' developer' : '' ?>"><?= (($p->email && $p->email !== 'noko') ? '<a href="mailto:' . rawurlencode($p->email) . '">' . $p->name_processed . '</a>' : $p->name_processed) ?></span>
					<span class="postertrip<?= ($p->capcode == 'M') ? ' mod' : '' ?><?= ($p->capcode == 'A') ? ' admin' : '' ?><?= ($p->capcode == 'D') ? ' developer' : '' ?>"><?= $p->trip_processed ?></span>
					<span class="poster_hash"><?php if ($p->poster_hash_processed) : ?>ID:<?= $p->poster_hash_processed ?><?php endif; ?></span>
					<?php if ($p->capcode == 'M') : ?>
						<span class="postername mod">## <?= __('Mod') ?></span>
					<?php endif ?>
					<?php if ($p->capcode == 'A') : ?>
						<span class="postername admin">## <?= __('Admin') ?></span>
					<?php endif ?>
					<?php if ($p->capcode == 'D') : ?>
						<span class="postername admin">## <?= __('Developers') ?></span>
					<?php endif ?>
					<?= gmdate('D M d H:i:s Y', $p->original_timestamp) ?>
					<?php if ($p->poster_country !== null) : ?><span class="poster_country"><span title="<?= e($p->poster_country_name) ?>" class="flag flag-<?= strtolower($p->poster_country) ?>"></span></span><?php endif; ?>
				</label>
				<?php if (!isset($thread_id)) : ?>
					<a class="js" href="<?= Uri::create(array($p->board->shortname, $p->_controller_method, $p->thread_num)) . '#' . $p->num . (($p->subnum > 0) ? '_' . $p->subnum : '') ?>">No.<?= $p->num . (($p->subnum > 0) ? ',' . $p->subnum : '') ?></a>
				<?php else : ?>
					<a class="js" href="<?= Uri::create(array($p->board->shortname, $p->_controller_method, $p->thread_num)) . '#' . $p->num . (($p->subnum > 0) ? '_' . $p->subnum : '') ?>">No.</a><a class="js" href="javascript:replyQuote('>><?= $p->num . (($p->subnum > 0) ? ',' . $p->subnum : '') ?>\n')"><?= $p->num . (($p->subnum > 0) ? ',' . $p->subnum : '') ?></a>
				<?php endif; ?>

				<?php if ($p->deleted == 1) : ?><img class="inline" src="<?= Uri::base() . Uri::base() . $this->fallback_asset('images/icons/file-delete-icon.png'); ?>" alt="[DELETED]" title="<?= __('This post was deleted before its lifetime has expired.') ?>"/><?php endif ?>
				<?php if (isset($p->media) && $p->media->spoiler == 1) : ?><img class="inline" src="<?= Uri::base() . $this->fallback_asset('images/icons/spoiler-icon.png'); ?>" alt="[SPOILER]" title="<?= __('The image in this post is marked as spoiler.') ?>"/><?php endif ?>
				<?php if ($p->subnum > 0) : ?><img class="inline" src="<?= Uri::base() . $this->fallback_asset('images/icons/communicate-icon.png'); ?>" alt="[INTERNAL]" title="<?= __('This post is not an archived reply.') ?>"/><?php endif ?>

				<?php if (isset($modifiers['post_show_view_button'])) : ?>[<a class="btnr" href="<?= Uri::create(array($p->board->shortname, 'thread', $p->thread_num)) . '#' . $p->num . (($p->subnum) ? '_' . $p->subnum : '') ?>">View</a>]<?php endif; ?>

				<br/>
				<?php if ($p->media !== null) : ?>
					<?php if ($p->media->media_status != 'banned') : ?>
					<span>
						<?= __('File:') . ' ' . \Num::format_bytes($p->media->media_size, 0) . ', ' . $p->media->media_w . 'x' . $p->media->media_h . ', ' . $p->media->media_filename_processed; ?>
						<?= '<!-- ' . substr($p->media->media_hash, 0, -2) . '-->' ?>
					</span>
					
						<?php if (!$p->board->hide_thumbnails || Auth::has_access('maccess.mod')) : ?>
							[<a href="<?= Uri::create($p->board->shortname . '/search/image/' . $p->media->safe_media_hash) ?>"><?= __('View Same') ?></a>]
							[<a href="http://google.com/searchbyimage?image_url=<?= $p->media->thumb_link ?>">Google</a>]
							[<a href="http://iqdb.org/?url=<?= $p->media->thumb_link ?>">iqdb</a>]
							[<a href="http://saucenao.com/search.php?url=<?= $p->media->thumb_link ?>">SauceNAO</a>]
						<?php endif; ?>
					<br />
					<?php endif; ?>
					<?php if ($p->media->media_status == 'banned') : ?>
						<img src="<?= Uri::base() . $this->fallback_asset('images/banned-image.png') ?>" width="150" height="150" class="thumb"/>
					<?php elseif ($p->media->thumb_link === null): ?>
						<a href="<?= ($p->media->media_link) ? $p->media->media_link : $p->remote_media_link ?>" rel="noreferrer">
							<img src="<?= Uri::base() . $this->fallback_asset('images/missing-image.jpg') ?>" width="150" height="150" class="thumb"/>
						</a>
					<?php else: ?>
					<a href="<?= ($p->media->media_link) ? $p->media->media_link : $p->remote_media_link ?>" rel="noreferrer">
						<img src="<?= $p->media->thumb_link ?>" alt="<?= $p->num ?>" width="<?= $p->preview_w ?>" height="<?= $p->preview_h ?>" class="thumb" />
					</a>
					<?php endif; ?>
				<?php endif; ?>
				<div class="quoted-by" style="display: <?= $p->backlinks ? 'block' : 'none' ?>">
					<?= __('Quoted By:') ?> <?= (isset($p->backlinks)) ? implode(' ', $p->backlinks) : '' ?>
				</div>
				<blockquote><p><?= $p->comment_processed ?></p></blockquote>
			</td>
		</tr>
	</tbody>
</table>
