<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
	$qry = $conn->query("SELECT * FROM `category_list` WHERE id = '{$_GET['id']}'");
	if($qry->num_rows > 0){
		foreach($qry->fetch_assoc() as $k => $v){
			$$k = $v;
		}
	} else {
		echo '<script>alert("ID категории не действителен."); location.replace("./?page=categories")</script>';
	}
} else {
	echo '<script>alert("ID категории обязателен."); location.replace("./?page=categories")</script>';
}
?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header py-0">
		<div class="card-title py-1"><b>Просмотр категории</b></div>
	</div>
	<div class="card-body">
		<div class="container-fluid mt-3">
			<dl>
				<dt class="text-muted">Название категории</dt>
				<dd class="ps-4"><?= $name ?? "—" ?></dd>
				<dt class="text-muted">Статус</dt>
				<dd class="ps-4">
					<?php if($status == 1): ?>
						<span class="badge bg-primary px-3 rounded-pill">Активна</span>
					<?php else: ?>
						<span class="badge bg-secondary px-3 rounded-pill">Неактивна</span>
					<?php endif; ?>
				</dd>
				<dt class="text-muted">Дата создания</dt>
				<dd class="ps-4"><?= date("d.m.Y H:i", strtotime($created_at ?? 'now')) ?></dd>
			</dl>
		</div>
	</div>
	<div class="card-footer py-1 text-center">
		<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=categories/manage_category&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Редактировать</a>
		<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=categories"><i class="fa fa-angle-left"></i> Назад к списку</a>
	</div>
</div>
