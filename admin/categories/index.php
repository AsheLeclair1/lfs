<?php 
if($_settings->chk_flashdata('success')):
?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
</script>
<?php endif; ?>
<style>
	#list td:nth-child(2),
	#list td:nth-child(3){
		text-align:center !important;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<div class="card-tools d-flex justify-content-end">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary bg-gradient-teal border-0 rounded00"><span class="fas fa-plus"></span> Добавить категорию</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="list">
					<thead>
						<tr>
							<th>#</th>
							<th>Название категории</th>
							<th>Статус</th>
							<th>Действие</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
						$qry = $conn->query("SELECT * FROM `category_list` ORDER BY `name` ASC");
						while($row = $qry->fetch_assoc()):
						?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="align-items-center"><?= $row['name'] ?></td>
							<td class="align-items-center text-center">
								<?php if($row['status'] == 1): ?>
									<span class="badge bg-primary px-3 rounded-pill">Активна</span>
								<?php else: ?>
									<span class="badge bg-secondary px-3 rounded-pill">Неактивна</span>
								<?php endif; ?>
							</td>
							<td class="align-items-center text-center">
								<div class="dropdown">
									<button type="button" class="btn btn-flat p-1 btn-default btn-sm border dropdown-toggle dropdown-icon" data-bs-toggle="dropdown">
										Действие
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-status="<?php echo $row['status'] ?>" id="edit_data"><span class="bi bi-pencil-square text-primary"></span> Редактировать</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="bi bi-trash text-danger"></span> Удалить</a>
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
		$('#create_new').click(function(){
			uni_modal('Добавить категорию', 'categories/manage_category.php', 'modal-md');
		});
		
		$('#edit_data').click(function(){
			uni_modal('Редактировать категорию', 'categories/manage_category.php?id='+$(this).attr('data-id'), 'modal-md');
		});
		
		$('.delete_data').click(function(){
			_conf("Вы уверены, что хотите удалить эту категорию навсегда?", "delete_category", [$(this).attr('data-id')]);
		});
		
		// Инициализация DataTable с русским языком
		if ($.fn.DataTable) {
			if ($.fn.DataTable.isDataTable('#list')) {
				$('#list').DataTable().destroy();
			}
			
			$('#list').DataTable({
				language: {
					processing: "Подождите...",
					search: "Поиск:",
					lengthMenu: "Показать _MENU_ записей",
					info: "Показано с _START_ по _END_ из _TOTAL_ записей",
					infoEmpty: "Показано 0 из 0 записей",
					infoFiltered: "(отфильтровано из _MAX_ записей)",
					loadingRecords: "Загрузка...",
					zeroRecords: "Записи не найдены",
					emptyTable: "Нет данных",
					paginate: {
						first: "Первая",
						previous: "Предыдущая",
						next: "Следующая",
						last: "Последняя"
					},
					aria: {
						sortAscending: ": активировать для сортировки по возрастанию",
						sortDescending: ": активировать для сортировки по убыванию"
					}
				},
				order: [[0, 'asc']]
			});
		}
	});
	
	function delete_category($id){
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_category",
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
					location.reload();
				} else {
					alert_toast("Произошла ошибка.", 'error');
					end_loader();
				}
			}
		});
	}
</script>
