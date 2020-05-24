<?php
/**
 * Created by PhpStorm.
 * User: Лука
 * Date: 15.04.2020
 * Time: 13:00
 */
function getAcceptRulesKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Я ознакомился и готов приступить',
			'callback_data' => 'AcceptRules'
		]
	]);
	return $ikb;
}

function getStartFirstStageKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Посмотрел и готов приступить',
			'callback_data' => 'StartFirst'
		]
	]);
	return $ikb;
}
function getStartSecondStageKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Посмотрел и готов приступить',
			'callback_data' => 'StartSecond'
		]
	]);
	return $ikb;
}
function getStartThirdStageKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Посмотрел и готов приступить',
			'callback_data' => 'StartThird'
		]
	]);
	return $ikb;
}
function moveToSecondStageKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Перейти во второй этап',
			'callback_data' => 'MoveToSecondStage'
		]

	]);
	$ikb->addRow([
		'callback_data' => 'TellAboutStageOne',
		'text'          => 'Рассказать о выполнении дз'
	]);
	$ikb->addRow([
		'callback_data' => 'TellIdeaOne',
		'text'          => 'У меня инсайт/есть идея задания'
	]);
	return $ikb;
}

function moveToThirdStageKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'text'          => 'Перейти в третий этап',
			'callback_data' => 'MoveToThirdStage'
		]

	]);
	$ikb->addRow([
		'callback_data' => 'TellAboutStageTwo',
		'text'          => 'Рассказать о выполнении дз'
	]);
	$ikb->addRow([
		'callback_data' => 'TellIdeaTwo',
		'text'          => 'У меня инсайт/есть идея задания'
	]);
	return $ikb;
}

function finishMarafonKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		//[
		//	'text'          => 'Получить задание для смельчаков',
		//	'callback_data' => 'GetExpertTask'
		//]
        [
            'url'  => 'https://t.me/joinchat/M6iWrhXwa8bBYGNNKyP_YQ',
            'text' => 'Перейти в VIP чат'
        ]
	]);
	//$ikb->addRow([
	//	'url'  => 'https://t.me/joinchat/M6iWrhXwa8bBYGNNKyP_YQ',
	//	'text' => 'Перейти в VIP чат'
	//]);
	$ikb->addRow([
		'callback_data' => 'TellAboutStageThree',
		'text'          => 'Рассказать о выполнении дз'
	]);
	$ikb->addRow([
		'callback_data' => 'TellIdeaThree',
		'text'          => 'У меня инсайт/есть идея задания'
	]);
	return $ikb;
}

function joinFirstStageChatKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'url'  => 'https://t.me/joinchat/M6iWrhnpERSYG9B4mLc0vA',
			'text' => 'Перейти в чат первого этапа'
		]
	]);

	return $ikb;
}
function joinSecondStageChatKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'url'  => 'https://t.me/joinchat/M6iWrhjxBFfsLdHoi1bXPg',
			'text' => 'Перейти в чат второго этапа'
		]
	]);

	return $ikb;
}
function joinThirdStageChatKeyBoard()
{
	$ikb = new Longman\TelegramBot\Entities\InlineKeyboard([
		[
			'url'  => 'https://t.me/joinchat/M6iWrhZQQyDsg9-Ij6UdIA',
			'text' => 'Перейти в чат третьего этапа'
		]
	]);
	return $ikb;
}