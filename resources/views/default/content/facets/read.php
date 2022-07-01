<div class="w-100">
  <?= insert('/content/facets/blog-header', ['data' => $data]); ?>

  <div class="flex gap mb-block">
    <main>
      <?php if (!empty($data['read'])) : ?>
        <div class="flex gap items-center">
          <svg class="icons red icon-base">
            <use xlink:href="/assets/svg/icons.svg#users"></use>
          </svg>
          <h2 class="gray-600 m0"><?= __('app.reads'); ?></h2>
        </div>
        <hr class="mb20">
        <?php foreach ($data['read'] as $row) : ?>
          <div class="flex flex-auto items-center mb20">
            <a class="flex items-center hidden gray-600" href="<?= url('profile', ['login' => $row['login']]); ?>">
              <?= Html::image($row['avatar'], $row['login'], 'img-base', 'avatar', 'max'); ?>
              <div class="ml5">
                <div class="gray-600"><?= $row['login']; ?></div>
                <?php if ($row['about']) : ?>
                  <div class="gray-600 mb-none text-sm">
                    <?= Content::fragment(Content::text($row['about'], 'line'), 80); ?>
                  </div>
                <?php endif; ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url('blog.read', ['slug' => $data['facet']['facet_slug']])); ?>
    </main>
    <aside>
      <?php if ($data['info']) : ?>
        <div class="sticky top-sm">
          <div class="box bg-lightgray content-body">
            <?= $data['info']; ?>
          </div>
        </div>
      <?php endif; ?>
    </aside>
  </div>
</div>