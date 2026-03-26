<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);

require_once '/opt/lampp/htdocs/Lost-And-Found-main/initialize.php';

if(!$_settings->userdata('id')){
    header('location: ./');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0) {
    $_settings->set_flashdata('error', 'Не указан ID акта');
    header('location: ./?page=items/write-off-list');
    exit;
}

require_once '/opt/lampp/htdocs/Lost-And-Found-main/classes/WriteOff.php';
$writeOff = new WriteOff();
$act = $writeOff->getById($id);

if(!$act) {
    echo "Акт не найден. ID: " . $id;
    exit;
}

$user = $conn->query("SELECT * FROM users WHERE id = {$act['created_by']}")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Акт о списании №<?= htmlspecialchars($act['act_number']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .act-container {
                box-shadow: none !important;
                border: none !important;
                padding: 20px !important;
            }
        }
        
        .act-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .act-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .act-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .act-header p {
            font-size: 16px;
            color: #666;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .info-table tr {
            border-bottom: 1px solid #eee;
        }
        
        .info-table td {
            padding: 12px 8px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            font-weight: bold;
            width: 180px;
            background: #f9f9f9;
        }
        
        .reason-box {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #17a2b8;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
        
        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }
        
        .btn-print {
            display: inline-block;
            background: #17a2b8;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
        }
        
        .btn-print:hover {
            background: #138496;
        }
        
        .btn-back {
            background: #6c757d;
            margin-left: 10px;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-4 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="text-center no-print">
    <button onclick="window.print()" class="btn-print">🖨️ Печать</button>
    <a href="./?page=items/write-off-list" class="btn-print btn-back">← Назад к списку</a>
</div>

<div class="act-container" id="print-area">
    <div class="act-header">
        <h1>АКТ О СПИСАНИИ № <?= htmlspecialchars($act['act_number']) ?></h1>
        <p>от <?= date("d.m.Y", strtotime($act['act_date'])) ?> г.</p>
    </div>
    
    <table class="info-table">
         <tr>
            <td>Наименование предмета:</td>
            <td><strong><?= htmlspecialchars($act['item_name']) ?></strong></td>
         </tr>
         <tr>
            <td>Номер комнаты:</td>
            <td><?= htmlspecialchars($act['room_number'] ?? '—') ?></td>
         </tr>
         <tr>
            <td>ФИО гостя:</td>
            <td><?= htmlspecialchars($act['guest_name'] ?? '—') ?></td>
         </tr>
         <tr>
            <td>Место находки:</td>
            <td><?= htmlspecialchars($act['location_found'] ?? '—') ?></td>
         </tr>
         <tr>
            <td>Код сотрудника:</td>
            <td><?= htmlspecialchars($act['finder_code'] ?? '—') ?></td>
         </tr>
         <tr>
            <td>Дата находки:</td>
            <td><?= date("d.m.Y", strtotime($act['date_found'] ?? $act['act_date'])) ?></td>
         </tr>
         <tr>
            <td>Дата составления акта:</td>
            <td><?= date("d.m.Y H:i", strtotime($act['act_date'])) ?></td>
         </tr>
         <tr>
            <td>Причина списания:</td>
            <td>
                <div class="reason-box">
                    <?= nl2br(htmlspecialchars($act['reason'])) ?>
                </div>
            </td>
         </tr>
         <tr>
            <td>Статус списания:</td>
            <td><strong><?= htmlspecialchars($act['status']) ?></strong></td>
         </tr>
         <tr>
            <td>Составил:</td>
            <td>
                <?= htmlspecialchars($user['firstname'] ?? '') ?> <?= htmlspecialchars($user['lastname'] ?? '') ?>
                <br><small>(<?= ($user['type'] ?? 0) == 1 ? 'Администратор' : 'Сотрудник' ?>)</small>
            </td>
         </tr>
    </table>
    
    <div class="signatures">
        <div class="signature">
            <div class="signature-line">_________________________</div>
            <div>Ответственный сотрудник</div>
        </div>
        <div class="signature">
            <div class="signature-line">_________________________</div>
            <div>М.П.</div>
        </div>
    </div>
    
    <div class="footer-note">
        Настоящий акт составлен в двух экземплярах, имеющих равную юридическую силу.<br>
        Один экземпляр передан в бухгалтерию, второй остается в деле.
    </div>
</div>

</body>
</html>
