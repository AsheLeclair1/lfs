<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = (int)$_GET['id'];
    $qry = $conn->query("SELECT i.*, c.name as category_name 
                         FROM item_list i 
                         LEFT JOIN category_list c ON i.category_id = c.id 
                         WHERE i.id = '{$id}'");
    if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
        foreach($row as $k => $v){
            $$k = $v;
        }
    }else{
        echo '<script>alert("ID предмета не действителен."); location.replace("./?page=items")</script>';
        exit;
    }
}else{
    echo '<script>alert("ID предмета обязателен."); location.replace("./?page=items")</script>';
    exit;
}

$creator_name = '—';
if(isset($created_by) && !empty($created_by)){
    $creator = $conn->query("SELECT username FROM users WHERE id = '{$created_by}'");
    if($creator && $creator->num_rows > 0){
        $creator_data = $creator->fetch_assoc();
        $creator_name = $creator_data['username'];
    }
}
?>
<style>
    .item-detail-card {
        max-width: 900px;
        margin: 0 auto;
    }
    .item-image {
        width: 100%;
        max-height: 350px;
        object-fit: contain;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
    }
    .image-placeholder {
        width: 100%;
        min-height: 250px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #adb5bd;
        border: 2px dashed #dee2e6;
    }
    .detail-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        width: 160px;
        font-weight: 600;
        color: #495057;
    }
    .detail-value {
        flex: 1;
        color: #212529;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 500;
    }
    .status-published { background: #0d6efd; color: white; }
    .status-claimed { background: #198754; color: white; }
    .status-written-off { background: #dc3545; color: white; }
    .status-pending { background: #6c757d; color: white; }
    .description-text {
        background: white;
        padding: 16px;
        border-radius: 8px;
        line-height: 1.6;
        color: #495057;
    }
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
    }
    .item-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }
    .item-category {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 8px;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding: 20px;
        background: white;
        border-top: 1px solid #e9ecef;
        border-radius: 0 0 12px 12px;
    }
    .btn-action {
        padding: 8px 24px;
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        .detail-row {
            flex-direction: column;
        }
        .detail-label {
            width: 100%;
            margin-bottom: 8px;
        }
    }
</style>

<div class="item-detail-card">
    <div class="card rounded-4 shadow-sm border-0 overflow-hidden">
        <div class="card-header-custom">
            <h3 class="item-title"><?= htmlspecialchars($title ?? 'Без названия') ?></h3>
            <div class="item-category">
                <i class="bi bi-tag"></i> <?= htmlspecialchars($category_name ?? 'Без категории') ?>
            </div>
        </div>
        
        <div class="card-body p-0">
            <!-- Изображение -->
            <div class="text-center p-4 bg-light">
                <?php 
                $image_path_val = isset($image_path) ? $image_path : '';
                $has_image = !empty($image_path_val) && is_file(base_app . $image_path_val);
                ?>
                <?php if($has_image): ?>
                    <img src="<?= validate_image($image_path_val) ?>" alt="<?= htmlspecialchars($title ?? '') ?>" class="item-image">
                <?php else: ?>
                    <div class="image-placeholder">
                        <i class="bi bi-image"></i>
                        <p>Нет изображения</p>
                        <small class="text-muted">Загрузите фото предмета при редактировании</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Основная информация -->
            <div class="detail-section m-4">
                <h5 class="mb-3"><i class="bi bi-info-circle"></i> Основная информация</h5>
                <div class="info-grid">
                    <div class="detail-row">
                        <div class="detail-label">Номер комнаты</div>
                        <div class="detail-value"><?= !empty($room_number) ? htmlspecialchars($room_number) : '<span class="text-muted">—</span>' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Место находки</div>
                        <div class="detail-value"><?= !empty($location_found) ? htmlspecialchars($location_found) : '<span class="text-muted">—</span>' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Дата находки</div>
                        <div class="detail-value"><?= date("d.m.Y H:i", strtotime($found_date ?? $created_at)) ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Информация о сотруднике -->
            <div class="detail-section m-4">
                <h5 class="mb-3"><i class="bi bi-person-badge"></i> Информация о сотруднике</h5>
                <div class="info-grid">
                    <div class="detail-row">
                        <div class="detail-label">Создал запись</div>
                        <div class="detail-value"><?= $creator_name ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Описание -->
            <?php if(!empty($description)): ?>
            <div class="detail-section m-4">
                <h5 class="mb-3"><i class="bi bi-file-text"></i> Описание</h5>
                <div class="description-text">
                    <?= nl2br(htmlspecialchars($description)) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Статус -->
            <div class="detail-section m-4">
                <h5 class="mb-3"><i class="bi bi-flag"></i> Статус</h5>
                <div>
                    <?php if($status == 1): ?>
                        <span class="status-badge status-published"><i class="bi bi-check-circle"></i> Опубликовано</span>
                    <?php elseif($status == 2): ?>
                        <span class="status-badge status-claimed"><i class="bi bi-arrow-return-left"></i> Возвращено</span>
                    <?php elseif($status == 3): ?>
                        <span class="status-badge status-written-off"><i class="bi bi-trash"></i> Списано</span>
                    <?php else: ?>
                        <span class="status-badge status-pending"><i class="bi bi-clock"></i> На рассмотрении</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="./?page=items/manage_item&id=<?= isset($id) ? $id : '' ?>" class="btn btn-primary btn-action">
                <i class="bi bi-pencil-square"></i> Редактировать
            </a>
            <button type="button" class="btn btn-danger btn-action" id="delete_data">
                <i class="bi bi-trash"></i> Удалить
            </button>
            <a href="./?page=items" class="btn btn-secondary btn-action">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#delete_data').click(function(){
            _conf("Вы уверены, что хотите удалить этот предмет навсегда?", "delete_item", ["<?= isset($id) ? $id :'' ?>"]);
        });
    });
    
    function delete_item($id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_item",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("Произошла ошибка.", 'error');
                end_loader();
            },
            success: function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    location.replace("./?page=items");
                } else {
                    alert_toast("Произошла ошибка.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
