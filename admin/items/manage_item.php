<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="card card-outline rounded-0 card-navy">
    <div class="card-header py-0">
        <div class="card-title py-1"><b><?= isset($id) ? "Редактирование находки" : "Добавление находки" ?></b></div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="items-form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                
                <div class="form-group mb-3">
                    <label for="category_id" class="control-label">Категория</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="" disabled <?= !isset($category_id) ? "selected" : "" ?>></option>
                        <?php
                        $query = $conn->query("SELECT * FROM `category_list` WHERE `status` = 1 ORDER BY `name` ASC");
                        while($row = $query->fetch_assoc()):
                        ?>
                        <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? "selected" : "" ?>><?= $row['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="title" class="control-label">Название предмета</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($title) ? $title : ''; ?>" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="room_number" class="control-label">Номер комнаты</label>
                    <input type="text" name="room_number" id="room_number" class="form-control" value="<?php echo isset($room_number) ? $room_number : ''; ?>" placeholder="Например: 101, 205">
                </div>
                
                <div class="form-group mb-3">
                    <label for="location_found" class="control-label">Место находки</label>
                    <input type="text" name="location_found" id="location_found" class="form-control" value="<?php echo isset($location_found) ? $location_found : ''; ?>" placeholder="Например: коридор, ресторан, бассейн">
                </div>
                
                <div class="form-group mb-3">
                    <label for="description" class="control-label">Описание</label>
                    <textarea rows="5" name="description" id="description" class="form-control" required><?php echo isset($description) ? $description : ''; ?></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="found_date" class="control-label">Дата находки</label>
                    <input type="datetime-local" name="found_date" id="found_date" class="form-control" value="<?php echo isset($found_date) ? date('Y-m-d\TH:i', strtotime($found_date)) : date('Y-m-d\TH:i') ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label class="control-label">Изображение предмета</label>
                    <input type="file" class="form-control" name="image" onchange="displayImg(this)" accept="image/png, image/jpeg">
                </div>
                
                <div class="form-group mb-3 text-center">
                    <img src="<?php echo validate_image(isset($image_path) ? $image_path : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail" style="max-width: 200px;">
                </div>
                
                <div class="form-group mb-3">
                    <label for="status" class="control-label">Статус</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>На рассмотрении</option>
                        <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Опубликовано</option>
                        <option value="2" <?= isset($status) && $status == 2 ? 'selected' : '' ?>>Возвращено</option>
                        <option value="3" <?= isset($status) && $status == 3 ? 'selected' : '' ?>>Списано</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer text-center">
        <button class="btn btn-primary" form="items-form"><i class="fa fa-save"></i> Сохранить</button>
        <a class="btn btn-secondary" href="./?page=items"><i class="fa fa-times"></i> Отмена</a>
    </div>
</div>

<script>
function displayImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#cimg').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function(){
    $('#category_id').select2({
        placeholder: 'Выберите категорию',
        width: '100%'
    });
    
    $('#items-form').submit(function(e){
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_item",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err){
                console.log(err);
                alert_toast("Произошла ошибка", 'error');
                end_loader();
            },
            success: function(resp){
                console.log(resp);
                if(typeof resp == 'object' && resp.status == 'success'){
                    location.replace('./?page=items/view_item&id=' + resp.iid);
                } else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    $("html, body").scrollTop(0);
                    end_loader();
                } else {
                    alert_toast("Произошла ошибка", 'error');
                    end_loader();
                }
            }
        });
    });
});
</script>
