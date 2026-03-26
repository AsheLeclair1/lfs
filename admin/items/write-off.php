<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);

require_once '/opt/lampp/htdocs/Lost-And-Found-main/initialize.php';

if(!$_settings->userdata('id')){
    header('location: ./');
    exit;
}

// Проверяем, передан ли ID предмета
if(!isset($_GET['item_id']) || $_GET['item_id'] <= 0) {
    $_settings->set_flashdata('error', 'Не выбран предмет для списания');
    header('location: ../?page=items');
    exit;
}

$item_id = (int)$_GET['item_id'];

// Получаем информацию о предмете
$item = $conn->query("SELECT * FROM item_list WHERE id = {$item_id}");
if($item->num_rows == 0) {
    $_settings->set_flashdata('error', 'Предмет не найден');
    header('location: ../?page=items');
    exit;
}
$item_data = $item->fetch_assoc();

// Проверяем, не списан ли уже предмет
if($item_data['status'] == 3) {
    $_settings->set_flashdata('error', 'Этот предмет уже списан');
    header('location: ../?page=items');
    exit;
}

require_once '/opt/lampp/htdocs/Lost-And-Found-main/classes/WriteOff.php';
$writeOff = new WriteOff();

// Обработка POST запроса
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $act_id = $writeOff->createAct([
        'item_id' => $item_id,
        'reason' => $conn->real_escape_string($_POST['reason']),
        'status' => $conn->real_escape_string($_POST['status']),
        'created_by' => $_settings->userdata('id')
    ]);
    
    if($act_id) {
        // Обновляем статус предмета на "Списано" (3)
        $conn->query("UPDATE item_list SET status = 3 WHERE id = {$item_id}");
        $_settings->set_flashdata('success', 'Акт списания успешно создан');
        header("location: ./?page=items/write_off_view&id={$act_id}");
        exit;
    } else {
        $_settings->set_flashdata('error', 'Ошибка при создании акта');
    }
}
?>

<div class="content">
    <div class="card card-outline rounded-0 card-navy">
        <div class="card-header">
            <h3 class="card-title">Создание акта о списании</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="alert alert-info">
                    <h5>Информация о предмете:</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Название:</th>
                            <td><?= htmlspecialchars($item_data['title'] ?? '') ?>\(
                        </tr>
                        <tr>
                            <th>Номер комнаты:</th>
                            <td><?= htmlspecialchars($item_data['room_number'] ?? '') ?: '—' ?></td>
                        </tr>
                        <tr>
                            <th>ФИО гостя:</th>
                            <td><?= htmlspecialchars($item_data['guest_name'] ?? '') ?: '—' ?></td>
                        </tr>
                        <tr>
                            <th>Место находки:</th>
                            <td><?= htmlspecialchars($item_data['location_found'] ?? '') ?: 'Не указано' ?></td>
                        </tr>
                        <tr>
                            <th>Код сотрудника:</th>
                            <td><?= htmlspecialchars($item_data['finder_code'] ?? '') ?: '—' ?></td>
                        </tr>
                        <tr>
                            <th>Дата находки:</th>
                            <td><?= date("d.m.Y H:i", strtotime($item_data['found_date'] ?? $item_data['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
                
                <form action="" method="POST" id="writeoff-form">
                    <div class="form-group mb-3">
                        <label for="reason" class="control-label">Причина списания</label>
                        <textarea name="reason" id="reason" rows="5" class="form-control form-control-sm rounded-0" required placeholder="Укажите причину списания предмета..."></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="status" class="control-label">Статус списания</label>
                        <select name="status" id="status" class="form-control form-control-sm rounded-0" required>
                            <option value="">-- Выберите статус --</option>
                            <option value="Утилизировано">Утилизировано</option>
                            <option value="Передано на хранение">Передано на хранение</option>
                            <option value="Уничтожено">Уничтожено</option>
                            <option value="Передано в благотворительность">Передано в благотворительность</option>
                        </select>
                    </div>
                    
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-teal border-0">
                            <i class="fa fa-save"></i> Создать акт
                        </button>
                        <a href="../?page=items" class="btn btn-light btn-sm border">
                            <i class="fa fa-times"></i> Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#writeoff-form').submit(function(e){
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        
        start_loader();
        $.ajax({
            url: window.location.href,
            method: 'POST',
            data: _this.serialize(),
            dataType: 'json',
            error: function(err) {
                console.log(err);
                alert_toast('Произошла ошибка', 'error');
                end_loader();
            },
            success: function(resp) {
                if(typeof resp == 'object' && resp.status == 'success') {
                    alert_toast('Акт успешно создан', 'success');
                    setTimeout(function() {
                        location.href = resp.redirect;
                    }, 1000);
                } else {
                    alert_toast('Ошибка при создании акта', 'error');
                    end_loader();
                }
            }
        });
    });
});
</script>
