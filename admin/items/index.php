<?php
if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <div class="card-tools d-flex justify-content-end">
            <a href="<?= base_url ?>admin?page=items/manage_item" class="btn btn-flat btn-primary bg-gradient-teal border-0 rounded00">
                <span class="fas fa-plus"></span> Добавить находку
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped table-bordered" id="list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Дата находки</th>
                            <th>Название</th>
                            <th>Номер комнаты</th>
                            <th>Создал</th>
                            <th>Статус</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `item_list` ORDER BY `found_date` DESC");
                        while($row = $qry->fetch_assoc()):
                            $creator_name = '—';
                            if(isset($row['created_by']) && !empty($row['created_by'])){
                                $creator = $conn->query("SELECT username FROM users WHERE id = '{$row['created_by']}'");
                                if($creator && $creator->num_rows > 0){
                                    $creator_data = $creator->fetch_assoc();
                                    $creator_name = $creator_data['username'];
                                }
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("d.m.Y H:i",strtotime($row['found_date'])) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= $row['room_number'] ?? '—' ?></td>
                            <td><?= $creator_name ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge bg-primary px-3 rounded-pill">Опубликовано</span>
                                <?php elseif($row['status'] == 2): ?>
                                    <span class="badge bg-success px-3 rounded-pill">Возвращено</span>
                                <?php elseif($row['status'] == 3): ?>
                                    <span class="badge bg-danger px-3 rounded-pill">Списано</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 rounded-pill">На рассмотрении</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-flat p-1 btn-default btn-sm border dropdown-toggle dropdown-icon" data-bs-toggle="dropdown">
                                        Действие
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="./?page=items/view_item&id=<?php echo $row['id'] ?>">
                                            <span class="bi bi-card-text text-dark"></span> Просмотр
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./?page=items/manage_item&id=<?php echo $row['id'] ?>">
                                            <span class="bi bi-pencil-square text-primary"></span> Редактировать
                                        </a>
                                        <?php if($row['status'] == 0): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./?page=items/write_off&item_id=<?php echo $row['id'] ?>">
                                            <span class="bi bi-file-text text-warning"></span> Списать
                                        </a>
                                        <?php endif; ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                            <span class="bi bi-trash text-danger"></span> Удалить
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.delete_data').click(function(){
        _conf("Вы уверены, что хотите удалить этот предмет навсегда?","delete_item",[$(this).attr('data-id')])
    });
    
    if(typeof $.fn.DataTable !== 'undefined'){
        $('#list').DataTable({
            language: {
                processing: "Подождите...",
                search: "Поиск:",
                lengthMenu: "Показать _MENU_ записей",
                info: "Показано с _START_ по _END_ из _TOTAL_ записей",
                infoEmpty: "Показано 0 из 0 записей",
                infoFiltered: "(отфильтровано из _MAX_ записей)",
                zeroRecords: "Записи не найдены",
                emptyTable: "Нет данных",
                paginate: {
                    first: "Первая",
                    previous: "Предыдущая",
                    next: "Следующая",
                    last: "Последняя"
                }
            }
        });
    }
});

function delete_item($id){
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_item",
        method: "POST",
        data: {id: $id},
        dataType: "json",
        error: function(err){
            console.log(err);
            alert_toast("Произошла ошибка.", 'error');
            end_loader();
        },
        success: function(resp){
            if(typeof resp == 'object' && resp.status == 'success'){
                location.reload();
            } else {
                alert_toast("Произошла ошибка.", 'error');
                end_loader();
            }
        }
    });
}
</script>
