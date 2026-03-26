<?php
// admin/print_write_off.php
require_once('../config.php');

// Получаем массив ID выбранных предметов из POST-запроса
$item_ids = isset($_POST['item_ids']) ? $_POST['item_ids'] : array();

// Если ID не переданы или это не массив, перенаправляем обратно
if(empty($item_ids) || !is_array($item_ids)){
    header('Location: write-off.php');
    exit;
}

// Экранируем ID для безопасного SQL-запроса (очень важно!)
$safe_ids = array_map('intval', $item_ids);
$id_list = implode(',', $safe_ids);

// Получаем все данные по выбранным предметам
$items = $conn->query("SELECT * FROM item_list WHERE id IN ($id_list) ORDER BY created_at ASC");

if($items->num_rows == 0){
    echo "Предметы не найдены.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Акт на списание №<?php echo date('Ymd-His'); ?></title>
    <style>
        /* Стили ТОЛЬКО для печати */
        body { font-family: 'Times New Roman', serif; width: 210mm; margin: 20mm auto; background: white; }
        .print-wrapper { padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .header h2 { font-size: 18px; margin: 5px 0 0; }
        .date-number { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .reason { margin: 20px 0; font-style: italic; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; margin-top: 10px; }
        .signatures { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature-line { width: 200px; border-bottom: 1px solid black; margin-top: 30px; text-align: center; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        .no-print { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="print-wrapper">
        <!-- Кнопка печати (не будет видна при печати) -->
        <div class="no-print text-center mb-3">
            <button onclick="window.print();" class="btn btn-primary">Печать</button>
            <button onclick="window.close();" class="btn btn-secondary">Закрыть</button>
        </div>

        <!-- Шапка акта -->
        <div class="header">
            <h1>Общество с ограниченной ответственностью "Ромашка"</h1>
            <h2>АКТ № <?php echo date('Ymd-His'); ?> О СПИСАНИИ ИМУЩЕСТВА</h2>
        </div>

        <!-- Дата и номер -->
        <div class="date-number">
            <span>Дата составления: «___» __________ 20__ г.</span>
            <span>Место составления: г. Москва</span>
        </div>

        <!-- Причина списания -->
        <div class="reason">
            <p>На основании приказа № ___ от «___» __________ 20__ г., комиссия в составе: <br>
            Председателя: Иванова И.И. (главный бухгалтер) <br>
            Членов комиссии: Петрова П.П. (менеджер), Сидорова С.С. (материально-ответственное лицо) <br>
            составила настоящий акт о списании следующего имущества, <strong>невостребованного владельцами в течение длительного времени (более 30 дней с момента регистрации находки)</strong>:</p>
        </div>

        <!-- Таблица с предметами -->
        <table>
            <thead>
                <tr>
                    <th>№ п/п</th>
                    <th>Наименование предмета</th>
                    <th>Дата находки</th>
                    <th>Нашедший (ФИО, контакт)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                while($item = $items->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo date("d.m.Y", strtotime($item['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($item['fullname'] . ' (тел. ' . $item['contact'] . ')'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Заключение комиссии -->
        <p>Комиссия, рассмотрев вышеперечисленное имущество, приняла решение: <strong>списать с баланса организации ввиду истечения срока хранения и отсутствия возможности установить владельца.</strong></p>

        <!-- Подписи -->
        <div class="signatures">
            <div class="signature-line">Председатель комиссии ___________ /Иванов И.И./</div>
            <div class="signature-line">Член комиссии ___________ /Петров П.П./</div>
            <div class="signature-line">Член комиссии ___________ /Сидоров С.С./</div>
        </div>

        <!-- Печать организации (можно добавить картинку) -->
        <div style="margin-top: 20px; text-align: right;">М.П.</div>

        <div class="footer">
            Настоящий акт составлен в двух экземплярах.
        </div>
    </div>

    <script>
        // Автоматически открыть диалог печати при загрузке страницы (опционально)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
