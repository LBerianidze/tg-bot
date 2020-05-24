<?php
/**
 * Created by PhpStorm.
 * User: Лука
 * Date: 18.05.2020
 * Time: 19:38
 */
include 'check_auth.php';

include "DBConfig.php";
$chat_id = $_REQUEST['chat_id'];
$type = $_REQUEST['type'];
$db_config = new DBConfig();
if ($type == 0)
{
	$user = $db_config->getUserByTelegramID($chat_id);
}
else
{
	$user = $db_config->getUserByUsername($chat_id);
}
if($user==false)
{
	echo 0;
	exit();
}
$dt = new DateTime('now');
$dt1 = new DateTime('now');
$count = 0;
if ($user['first_done_date'] != null)
{
	$start = $user['first_take_date'];
	$end = $user['first_done_date'];
	$diff = (new DateTime($end))->diff((new DateTime($start)));
	$user['first_time'] = $diff->format("%d дней %h часов %i минут %s секунд");
	$dt->add($diff);
	$count++;
}
else
{
	$user['first_time'] = 'Не завершен';
	$user['first_done_date'] = 'Не завершен';
}
if ($user['second_done_date'] != null)
{
	$start = $user['second_take_date'];
	$end = $user['second_done_date'];
	$diff = (new DateTime($end))->diff((new DateTime($start)));
	$user['second_time'] = $diff->format("%d дней %h часов %i минут %s секунд");
	$dt->add($diff);
	$count++;
}
else
{
	$user['second_time'] = 'Не завершен';
	$user['second_done_date'] = 'Не завершен';
}
if ($user['third_done_date'] != null)
{
	$start = $user['third_take_date'];
	$end = $user['third_done_date'];
	$diff = (new DateTime($end))->diff((new DateTime($start)));
	$user['third_time'] = $diff->format("%d дней %h часов %i минут %s секунд");
	$dt->add($diff);
	$count++;
}
else
{
	$user['third_time'] = 'Не завершен';
	$user['third_done_date'] = 'Не завершен';
}
$sum = $dt->diff($dt1);
if ($count == 3)
{
	$user['full_time'] = $sum->format("%d дней %h часов %i минут %s секунд");
}
else
{
	$user['full_time'] = 'Не все этапы завершены';
}
if ($user['first_take_date'] == null)
{
	$user['first_take_date'] = "Не начат";
}
if ($user['second_take_date'] == null)
{
	$user['second_take_date'] = "Не начат";
}
if ($user['third_take_date'] == null)
{
	$user['third_take_date'] = "Не начат";
}
$user['has_access_next_stage'] = $user['has_access_next_stage'] == 1 ? 'Да' : 'Нет';
$user['last_action_type'] = getActionTypeByID($user['last_action_type'] );
echo json_encode($user);

function getActionTypeByID($id)
{
    switch ($id)
    {
        case 1:
            return 'Подтвердил телефон';
        case 2:
            return 'Выдано сообщени о вводе неверного телефона';
        case 3:
            return 'Выдано меню после отправки идеи этап 1';
        case 4:
            return 'Выдано меню после отправки инсайта этап 1';
        case 5:
            return 'Выдано меню после отправки идеи этап 2';
        case 6:
            return 'Выдано меню после отправки инсайта этап 2';
        case 7:
            return 'Выдано меню после отправки идеи этап 3';
        case 8:
            return 'Выдано меню после отправки инсайта этап 3';
        case 9:
            return 'Только что вошел в бота,спросили телефон';
        case 10:
            return 'Принял условия,отправили видео первого этапа';
        case 11:
            return 'Начал первый этап';
        case 12:
            return 'Перешел к описанию ДЗ или инсайта первого этапа';
        case 13:
            return 'Перешел к видео второго этапа';
        case 14:
            return 'Начал второй этап';
        case 15:
            return 'Перешел к описанию ДЗ или инсайта второго этапа';
        case 16:
            return 'Перешел к видео третьего этапа';
        case 17:
            return 'Начал третий этап';
        case 18:
            return 'Перешел к описанию ДЗ или инсайта третьего этапа';
        case 19:
            return 'Перешел к задачам для экспертов';
    }
}