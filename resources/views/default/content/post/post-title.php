<?php if ($post['post_is_deleted'] == 1) : ?><span class="label label-orange"><?= __('app.remote'); ?></span><?php endif; ?>
<?php if ($post['post_closed'] == 1) : ?><span class="gray-600" title="<?= __('app.close'); ?>"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#lock"></use></svg></span><?php endif; ?>
<?php if ($post['post_top'] == 1) : ?> <span class="label label-red"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#arrow-up"></use></svg></span> <?php endif; ?>
<?php if ($post['post_lo']) : ?><span class="red"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#selected"></use></svg></span><?php endif; ?>
<?php if ($post['post_feature'] == 1) : ?> <span class="label label-green"><?= __('app.question'); ?></span> <?php endif; ?>
<?php if ($post['post_translation'] == 1) : ?><span class="label label-grey"><?= __('app.translation'); ?></span><?php endif; ?>
<?php if ($post['post_tl']) : ?> <span class="brown italic text-sm ml5">tl<?= $post['post_tl']; ?></span> <?php endif; ?>
<?php if ($post['post_merged_id']) : ?>
  <span class="red">
    <svg class="icons">
      <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
    </svg>
    <?php if (UserData::checkAdmin()) : ?>
      <a href="/post/<?= $post['post_merged_id']; ?>">id <?= $post['post_merged_id']; ?></a>
    <?php endif; ?>
  </span>
<?php endif; ?>