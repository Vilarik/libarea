<?php
  $fs = $data['facet'];
  $url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="flex justify-between mb15">
    <ul class="nav">
      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'        => 'topic',
              'url'       => url('content.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
              'title'     => __('app.edit_' . $data['type']),
            ], [
              'id'        => 'blog',
              'url'       => url('team.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
              'title'     => __('app.team'),
            ]
          ]
        ]
      ); ?> 
    </ul>
    <a class="gray-600" href="<?= $url; ?>"><?= __('app.go_to'); ?></a>
  </div>

  <form class="max-w780" action="<?= url('content.change', ['type' => 'team']); ?>" method="post">
    <?= csrf_field() ?>

    <?= insert('/_block/form/select/users-team', ['users' => []]); ?>

    <p><?= Html::sumbit(__('app.add')); ?></p>
  </form>

  <h2><?= __('app.users'); ?></h2>
  <?php if ($data['users_team']) : ?>
    <?php foreach ($data['users_team'] as $usr) : ?>
      <div class="mb15">
        <?= Img::avatar($usr['avatar'], $usr['login'], 'img-base', 'small'); ?>
        <a href="<?= url('profile', ['login' => $usr['login']]); ?>"><?= $usr['login']; ?></a>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    .....
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-beige">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('app.being_developed'); ?>
  </div>
</aside>