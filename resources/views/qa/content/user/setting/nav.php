<div class="box-flex-white bg-violet-50">
  <ul class="nav">
    <?= tabs_nav(
      'nav',
      $data['sheet'],
      1,
      $pages = Config::get('menu.settings'),
    ); ?>
  </ul>
</div>