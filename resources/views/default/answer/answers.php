<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), null,  null, lang('all answers')); ?>
  </div>

  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd-5 mt15 border-box-1 pt5 pr15 pb5 pl15">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <div class="flex size-14">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18'); ?>
            <a class="gray mr5 ml5" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
              <?= $answer['user_login']; ?>
            </a>
            <span class="gray lowercase"><?= $answer['date']; ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
            <?= $answer['post_title']; ?>
          </a>
          <div class="answ-telo">
            <?= $answer['answer_content']; ?>
          </div>

          <div class="hidden gray">
            + <?= $answer['answer_votes']; ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-300">
            <div class="voters"></div>
            ~ <?= lang('Answer deleted'); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'There are no comments']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('answers-desc')]); ?>