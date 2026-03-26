<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);

require_once '/opt/lampp/htdocs/Lost-And-Found-main/initialize.php';

if(!$_settings->userdata('id')){
    header('location: ./');
    exit;
}

require_once '/opt/lampp/htdocs/Lost-And-Found-main/classes/WriteOff.php';
$writeOff = new WriteOff();
$acts = $writeOff->getAllWithDetails();
?>

<div class="content">
    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Акты списания</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped table-bordered" id="list">
                        <thead>
                            <tr>
                                <th>№ Акта</th>
                                <th>Предмет</th>
                                <th>Дата создания</th>
                                <th>Причина</th>
                                <th>Статус</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($acts)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Актов списания пока нет</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($acts as $act): ?>
                            <tr>
                                <td><?= htmlspecialchars($act['act_number']) ?></td>
                                <td><?= htmlspecialchars($act['item_name']) ?></td>
                                <td><?= date("d.m.Y H:i", strtotime($act['act_date'])) ?></td>
                                <td><?= htmlspecialchars(mb_substr($act['reason'], 0, 50)) ?>...</td>
                                <td>
                                    <?php 
                                    $badge_color = 'secondary';
                                    if($act['status'] == 'Утилизировано') $badge_color = 'danger';
                                    elseif($act['status'] == 'Передано на хранение') $badge_color = 'warning';
                                    elseif($act['status'] == 'Уничтожено') $badge_color = 'dark';
                                    elseif($act['status'] == 'Передано в благотворительность') $badge_color = 'success';
                                    ?>
                                    <span class="badge bg-<?= $badge_color ?> px-3 rounded-pill"><?= $act['status'] ?></span>
                                </td>
                                <td>
                                    <a href="./?page=items/write_off_view&id=<?= $act['id'] ?>" class="btn btn-sm btn-flat btn-info">
                                        <i class="fa fa-eye"></i> Просмотр
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#list').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ru.json'
        },
        order: [[2, 'desc']] // Сортировка по дате (убывание)
    });
});
</script>
