<?= insert('/_block/add-js-css'); ?>

<main>
  <a href="/"><?= __('app.home'); ?></a> / <span class="gray-600"><?= __('app.edit_answer'); ?>:</span>
  <a class="mb5 block" href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>"><?= $data['post']['post_title']; ?></a>

  <form class="max-w780" action="<?= url('content.change', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $data['answer']['answer_content'], 'type' => 'answer', 'id' => $data['post']['post_id']]); ?>

    <?php if (UserData::checkAdmin()) : ?>
      <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
    <?php endif; ?>

    <input type="hidden" name="answer_id" value="<?= $data['answer']['answer_id']; ?>">
    <?= Html::sumbit(__('app.edit')); ?>
    <a href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>#answer_<?= $data['answer']['answer_id']; ?>" class="text-sm inline ml15 gray"><?= __('app.cancel'); ?></a>
  </form>
</main>