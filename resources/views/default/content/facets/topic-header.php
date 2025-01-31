<div class="flex or-width flex-col box box-fon">
  <div class="flex justify-center gap items-center mb20">
    <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-lg', 'logo', 'max'); ?>
    <div>
      <h1 class="text-2xl mb-text-xl m0">
        <?= $topic['facet_seo_title']; ?>
        <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == UserData::getUserId()) : ?>
          <a class="gray-600" href="<?= url('content.edit', ['type' => 'topic', 'id' => $topic['facet_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
      </h1>
      <div class="text-sm gray-600"><?= $topic['facet_short_description']; ?></div>
    </div>
  </div>

  <div class="flex gray-600 justify-center gap mb20">
    <?= Html::signed([
      'type'            => 'facet',
      'id'              => $topic['facet_id'],
      'content_user_id' => $topic['facet_user_id'],
      'state'           => is_array($data['facet_signed']),
    ]); ?>
  </div>

  <div class="flex justify-center gap text-sm gray-600 lowercase">
    <div>
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#users"></use>
      </svg>
      <?= $topic['facet_focus_count']; ?> <span class="mb-none"><?= __('app.reads'); ?></span>
    </div>

    <div>
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#post"></use>
      </svg>
      <?= $topic['facet_count']; ?> <span class="mb-none"><?= Html::numWord($topic['facet_count'], __('app.num_post'), false); ?></span>
    </div>

    <div>
      <a class="gray-600 ml30" href="<?= url('topic.info', ['slug' => $topic['facet_slug']]); ?>">
        <svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#info"></use>
        </svg>
      </a>
    </div>

    <div data-a11y-dialog-show="id-share">
      <svg class="icons gray-600">
        <use xlink:href="/assets/svg/icons.svg#share"></use>
      </svg>
    </div>

    <div>
      <a class="gray-600" href="/rss-feed/topic/<?= $topic['facet_slug']; ?>">
        <svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#rss"></use>
        </svg>
      </a>
    </div>
  </div>
</div>

<div class="flex justify-between mb20">
  <ul class="nav scroll-menu">
    <?= insert(
      '/_block/navigation/nav',
      [
        'list' =>  [
          [
            'id'      => 'facet.feed',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.feed'),
          ], [
            'id'    => 'facet.posts',
            'url'   => url('topic', ['slug' => $topic['facet_slug']]) . '/posts',
            'title' => __('app.posts'),
          ], [
            'id'    => 'facet.questions',
            'url'   => url('topic', ['slug' => $topic['facet_slug']]) . '/questions',
            'title' => __('app.questions'),
          ], [
            'id'      => 'facet.recommend',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
            'title'   => __('app.recommended'),
          ], [
            'id'      => 'writers',
            'url'     => url('topic.writers', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.writers'),
          ]
        ]
      ]
    ); ?>
  </ul>
  <div title="<?= __('app.post_appearance'); ?>" id="postmenu" class="m5">
    <svg class="icons pointer gray-600">
      <use xlink:href="/assets/svg/icons.svg#grid"></use>
    </svg>
  </div>
</div>

<?= insert('/_block/share', ['title' => __('app.share_topic'), 'url' => config('meta.url') . url('topic', ['slug' => $topic['facet_slug']])]); ?>