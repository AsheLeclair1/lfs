<style>
.sidebar-nav .nav-content a i {
    font-size: .9rem;
}
</style>

<aside id="sidebar" class="sidebar">
<ul class="sidebar-nav" id="sidebar-nav">

<li class="nav-item">
    <a class="nav-link <?= $page != 'home' ? 'collapsed' : '' ?>" href="<?= base_url.'admin' ?>">
        <i class="bi bi-grid"></i>
        <span>Главная</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= !in_array($page, ['categories', 'categories/manage_category', 'categories/view_category']) ? 'collapsed' : '' ?>" data-bs-target="#categories-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Категории</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="categories-nav" class="nav-content collapse <?= in_array($page, ['categories', 'categories/manage_category', 'categories/view_category']) ? 'show' : '' ?>">
        <li>
            <a href="<?= base_url.'admin/?page=categories/manage_category' ?>" class="<?= $page == 'categories/manage_category' ? 'active' : '' ?>">
                <i class="bi bi-plus-lg"></i><span>Добавить</span>
            </a>
        </li>
        <li>
            <a href="<?= base_url.'admin/?page=categories' ?>" class="<?= $page == 'categories' ? 'active' : '' ?>">
                <i class="bi bi-circle"></i><span>Список</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a class="nav-link <?= !in_array($page, ['items', 'items/manage_item', 'items/view_item']) ? 'collapsed' : '' ?>" data-bs-target="#items-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box"></i><span>Найденные вещи</span>
        <?php
        $pitem = $conn->query("SELECT * FROM `item_list` where `status` = 0")->num_rows;
        if($pitem > 0): ?>
            <span class="badge rounded-pill bg-danger text-light ms-4"><?= format_num($pitem) ?></span>
        <?php endif; ?>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="items-nav" class="nav-content collapse <?= in_array($page, ['items', 'items/manage_item', 'items/view_item']) ? 'show' : '' ?>">
        <li>
            <a href="<?= base_url.'admin/?page=items/manage_item' ?>" class="<?= $page == 'items/manage_item' ? 'active' : '' ?>">
                <i class="bi bi-plus-lg"></i><span>Добавить находку</span>
            </a>
        </li>
        <li>
            <a href="<?= base_url.'admin/?page=items' ?>" class="<?= $page == 'items' ? 'active' : '' ?>">
                <i class="bi bi-circle"></i><span>Список находок</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a class="nav-link <?= $page != 'inquiries' ? 'collapsed' : '' ?>" href="<?= base_url."admin?page=inquiries" ?>">
        <i class="bi bi-envelope"></i>
        <span>Сообщения</span>
        <?php
        $message = $conn->query("SELECT * FROM `inquiry_list` where `status` = 0")->num_rows;
        if($message > 0): ?>
            <span class="badge rounded-pill bg-danger text-light ms-4"><?= format_num($message) ?></span>
        <?php endif; ?>
    </a>
</li>

<?php if($_settings->userdata('type') == 1): ?>
<li class="nav-heading">Настройки</li>

<li class="nav-item">
    <a class="nav-link <?= $page != 'user/list' ? 'collapsed' : '' ?>" href="<?= base_url."admin?page=user/list" ?>">
        <i class="bi bi-people"></i>
        <span>Сотрудники</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= $page != 'system_info/contact_information' ? 'collapsed' : '' ?>" href="<?= base_url."admin?page=system_info/contact_information" ?>">
        <i class="bi bi-telephone"></i>
        <span>Контактная информация</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= $page != 'system_info' ? 'collapsed' : '' ?>" href="<?= base_url."admin?page=system_info" ?>">
        <i class="bi bi-gear"></i>
        <span>Настройки системы</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= in_array($page, ['items/write-off', 'items/write-off-view', 'items/write-off-list']) ? '' : 'collapsed' ?>" href="#" data-bs-target="#writeoff-nav" data-bs-toggle="collapse">
        <i class="bi bi-file-text"></i>
        <span>Акты списания</span>
        <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="writeoff-nav" class="nav-content collapse <?= in_array($page, ['items/write-off', 'items/write-off-view', 'items/write-off-list']) ? 'show' : '' ?>">
        <li>
            <a href="<?= base_url.'admin/?page=items/write-off-list' ?>" class="<?= $page == 'items/write-off-list' ? 'active' : '' ?>">
                <i class="bi bi-list"></i>
                <span>Список актов</span>
            </a>
        </li>
    </ul>
</li>
<?php endif; ?>

</ul>
</aside>
