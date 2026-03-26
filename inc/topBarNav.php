<!-- ======= Шапка сайта ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="container-lg d-flex justify-content-between px-4">
    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= base_url ?>" class="logo d-flex align-items-center">
        <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Логотип системы">
        <span class="d-none d-lg-block"><?= $_settings->info('short_name') ?></span>
      </a>
    </div><!-- Конец логотипа -->
    <nav class="header-nav me-auto">
      <ul class="d-flex align-items-center h-100">
        <li class="nav-item pe-3">
            <a href="<?= base_url ?>" class="nav-link">Главная</a>
        </li>
        <li class="nav-item pe-3">
            <a href="<?= base_url.'?page=items' ?>" class="nav-link">Потеряно и найдено</a>
        </li>
        <li class="nav-item pe-3">
            <a href="<?= base_url.'?page=found' ?>" class="nav-link">Сообщить о находке</a>
        </li>
        <li class="nav-item pe-3">
            <a href="<?= base_url."?page=about" ?>" class="nav-link">О нас</a>
        </li>
        <li class="nav-item pe-3">
            <a href="<?= base_url.'?page=contact' ?>" class="nav-link">Контакты</a>
        </li>
      </ul>
    </nav><!-- Конец навигации -->
    <div class="d-flex align-items-center justify-content-between">
            <a href="<?= base_url.'admin' ?>" class="btn btn-primary">Вход</a>
    </div>
  </div>
</header><!-- Конец шапки -->
