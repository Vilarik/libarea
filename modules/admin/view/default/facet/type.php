<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => $data['type'],
        'url'   => url('admin.facets.type', ['type' => $data['type']]),
        'name'  => __('admin.all'),
      ],
      [
        'id'    => 'ban.facet',
        'url'   => url('admin.facets.ban.type', ['type' => $data['type']]),
        'name'  => __('admin.deleted'),
      ]
    ]
  ]
);
?>

<?php if (!empty($data['facets'])) : ?>
  <?php foreach ($data['facets'] as $topic) : ?>

    <?php
    switch ($topic['facet_type']):
      case 'category':
        $url    = url('category', ['sort' => 'all', 'slug' => $topic['facet_slug']]);
        break;
      default:
        $url = url($topic['facet_type'], ['slug' => $topic['facet_slug']]);
        break;
    endswitch;
    ?>

    <div class="w-50 mb5">
      <?php $topic['level'] = $topic['level'] ?? null; ?>
      <?php if ($topic['level'] > 0) : ?>
        <?php $color = true; ?>
        <svg class="icons gray ml<?= $topic['level'] * 10; ?>">
          <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
        </svg>
      <?php endif; ?>
      <a class="<?php if ($topic['level'] == 0) : ?>relative pt5 text-xl items-center hidden<?php endif; ?> <?php if ($topic['level'] > 0) : ?> black<?php endif; ?>" href="<?= $url; ?>">
        <?php if ($topic['level'] == 0) : ?>
          <?= Img::image($topic['facet_img'], $topic['facet_title'], 'w20 h20 mr5 br-gray', 'logo', 'max'); ?>
        <?php endif; ?>
        <?= $topic['facet_title']; ?>
      </a>

      <a class="ml15 mr15" href="<?= url('content.edit', ['type' => $data['type'], 'id' => $topic['facet_id']]); ?>">
        <sup><svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg></sup>
      </a>

      <?php if ($topic['facet_is_deleted'] == 1) : ?>
        <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
          <sup><svg class="icons red">
              <use xlink:href="/assets/svg/icons.svg#trash-2"></use>
            </svg></sup>
        </span>
      <?php else : ?>
        <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
          <sup><svg class="icons gray-600">
              <use xlink:href="/assets/svg/icons.svg#trash"></use>
            </svg></sup>
        </span>
      <?php endif; ?>

      <?php if ($topic['matching_list']) : ?>
        <div class="ml<?= $topic['level'] * 10; ?>">
          <svg class="icons gray-600 text-sm mr5 ml5">
            <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
          </svg>
          <?= Html::facets($topic['matching_list'], $topic['facet_type'], 'gray-600 text-sm mr15'); ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if ($data['type'] != 'all') : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if ($data['type'] == 'section') : ?>

  <?php if (!empty($data['pages'])) : ?>
    <h3 class="mb5"><?= __('admin.pages'); ?></h3>
    <?php foreach ($data['pages'] as $page) : ?>
      <div class="mb5">
        <a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => $page['post_slug']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#info"></use>
          </svg> <?= $page['post_title']; ?> <sup class="gray-600">id:<?= $page['post_id']; ?></sup>
        </a>
        <a class="gray-600 ml10" href="<?= url('content.edit', ['type' => $page['post_type'], 'id' => $page['post_id']]) ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a>
        <a data-type="post" data-id="<?= $page['post_id']; ?>" class="type-action gray-600 mr10 ml10">
          <?php if ($page['post_is_deleted'] == 1) : ?>
            <svg class="icons red">
              <use xlink:href="/assets/svg/icons.svg#trash-2"></use>
            </svg>
          <?php else : ?>
            <svg class="icons gray-600">
              <use xlink:href="/assets/svg/icons.svg#trash"></use>
            </svg>
          <?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
<?php endif; ?>

</main>
<?= includeTemplate('/view/default/footer'); ?>