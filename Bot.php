<?php
/**
 * Created by PhpStorm.
 * User: Лука
 * Date: 16.04.2020
 * Time: 00:54
 */
include('vendor/autoload.php');
include('BotHelper.php');
include('DBConfig.php');
include('keyboards.php');

use Longman\TelegramBot\Request;

class TelegramBot
{
	/**
	 * @var Longman\TelegramBot\Telegram
	 */
	var $telegram;
	/**
	 * @var DBConfig
	 */
	var $db_config;
	var $json;

	private function log()
	{
		file_put_contents('log.txt', file_get_contents('php://input'));
	}

	private function init()
	{
		$this->telegram = new Longman\TelegramBot\Telegram('1032111112:AAGHNnHc3cmEgFIV1EzTtoWfW4TTZxLzAq0', '@Sarafaner_bot');
		$this->telegram->useGetUpdatesWithoutDatabase();
		$this->telegram->handle();
		$this->db_config = new DBConfig();
		$this->json = json_decode(file_get_contents('Texts.json'));
	}

	/**
	 * @param $message \Longman\TelegramBot\Entities\Message
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	private function processTextMessage($message)
	{
		$chat_id = getChatId($message);

		$text = htmlspecialchars( $message->getText(),ENT_QUOTES);
		$step = $this->db_config->getStep($chat_id);
		switch ($step)
		{
			case 1:
				{
					if (preg_match('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/', $text))
					{

						$this->db_config->setPhone($chat_id, $text);
						$this->db_config->setStep($chat_id, 0);
						Request::sendMessage([
							'chat_id'      => $chat_id,
							'text'         => $this->json->greeting,
							'reply_markup' => getAcceptRulesKeyBoard()
						]);
                        $this->db_config->setLastActionType($chat_id,1);
					}
					else
					{
						Request::sendMessage([
							'chat_id' => $chat_id,
							'text'    => "Номер телефона введен в неверном формате. Пожалуйста,введите действительный номер телефона:"
						]);
                        $this->db_config->setLastActionType($chat_id,2);
					}
					break;
				}
			case 2:
			case 3:
				{
					Request::sendMessage([
						'chat_id'      => $chat_id,
						'text'         => $this->json->first_done,
						'reply_markup' => moveToSecondStageKeyBoard()
					]);
					$type = $step == 2 ? 0 : 1;
					$this->db_config->addReview($chat_id, $type, 1, $text);
					$this->db_config->setStep($chat_id, 0);
                    $this->db_config->setLastActionType($chat_id,$step+1);
					break;
				}
			case 4:
			case 5:
				{
					Request::sendMessage([
						'chat_id'      => $chat_id,
						'text'         => $this->json->second_done,
						'reply_markup' => moveToThirdStageKeyBoard()
					]);
					$type = $step == 4 ? 0 : 1;
					$this->db_config->addReview($chat_id, $type, 2, $text);
					$this->db_config->setStep($chat_id, 0);
                    $this->db_config->setLastActionType($chat_id,$step+1);
					break;
				}
			case 6:
			case 7:
				{
					Request::sendMessage([
						'chat_id'      => $chat_id,
						'text'         => $this->json->third_done,
						'reply_markup' => finishMarafonKeyBoard()
					]);
					$type = $step == 6 ? 0 : 1;
					$this->db_config->addReview($chat_id, $type, 3, $text);
					$this->db_config->setStep($chat_id, 0);
                    $this->db_config->setLastActionType($chat_id,$step+1);
					break;
				}
		}
		$this->db_config->setLastActionDate($chat_id);

	}

	/**
	 * @param $message \Longman\TelegramBot\Entities\Message
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	private function processCommand($message)
	{
		$chat_id = getChatId($message);
		$command = $message->getCommand();
		if ($command == 'start')
		{
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'    => $this->json->start
			]);
			if (!$this->db_config->userExists($chat_id))
			{
				$name = $message->getChat()->getFirstName() . ' ' . $message->getChat()->getLastName();
				$username = $message->getChat()->getUsername();
				if($username==null)
                {
                    $username='';
                }
				$this->db_config->addUser($chat_id, $name, $username);
                $this->db_config->setLastActionType($chat_id,9);
			}
			if (strlen($this->db_config->getPhone($chat_id)) == 0)
			{
				Request::sendMessage([
					'chat_id' => $chat_id,
					'text'    => $this->json->AskPhone
				]);
				$this->db_config->setStep($chat_id, 1);
			}
		}
        $this->db_config->setLastActionDate($chat_id);
    }

	/**
	 * @param $callback \Longman\TelegramBot\Entities\CallbackQuery
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	private function processCallBack($callback)
	{
		$chat_id = $callback->getFrom()->getId();
		$data = $callback->getData();
		$user = $this->db_config->getUser($chat_id);
		if ($data == 'AcceptRules' && $user['stage'] == 0 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => $this->json->greeting
			]);
			Request::sendMessage([
				'chat_id'      => $chat_id,
				'text'         => $this->json->first_video,
				'reply_markup' => getStartFirstStageKeyBoard()
			]);
            $this->db_config->setLastActionType($chat_id,10);
		}
		else if ($data == 'StartFirst' && $user['stage'] == 0 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => $this->json->first_video
			]);
			Request::sendMessage([
				'chat_id'                  => $chat_id,
				'disable_web_page_preview' => true,
				'text'                     => $this->json->first,
				'reply_markup'             => joinFirstStageChatKeyBoard()

			]);
			$this->db_config->setStage($chat_id, 1);
			$this->db_config->setValue($chat_id,'first_take_date',getCurrentDate());
            $this->db_config->setLastActionType($chat_id,11);
		}
		else if (($data == 'TellAboutStageOne' || $data == 'TellIdeaOne') && $user['stage'] == 1 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => ($data == 'TellAboutStageOne' ? "Опиши выполнение ДЗ:" : "Опиши инсайт или идею задания:")
			]);
            $this->db_config->setStep($chat_id, ($data == 'TellAboutStageOne' ? 2 : 3));
            $this->db_config->setLastActionType($chat_id,12);
		}
		else if ($data == 'MoveToSecondStage' && $user['stage'] == 1 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'      => $chat_id,
				'message_id'   => $callback->getMessage()->getMessageId(),
				'text'         => $this->json->second_video,
				'reply_markup' => getStartSecondStageKeyBoard()
			]);
            $this->db_config->setLastActionType($chat_id,13);
		}
		else if ($data == 'StartSecond' && $user['stage'] == 1 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => $this->json->second_video
			]);
			Request::sendMessage([
				'chat_id'                  => $chat_id,
				'disable_web_page_preview' => true,
				'text'                     => $this->json->second,
				'reply_markup'             => joinSecondStageChatKeyBoard()

			]);
			$this->db_config->setStage($chat_id, 2);
			$this->db_config->setValue($chat_id,'second_take_date',getCurrentDate());
            $this->db_config->setLastActionType($chat_id,14);

		}
		else if (($data == 'TellAboutStageTwo' || $data == 'TellIdeaTwo') && $user['stage'] == 2 && $user['has_access_next_stage'] == 1)
		{
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => ($data == 'TellAboutStageTwo' ? "Опиши выполнение ДЗ:" : "Опиши инсайт или идею задания:")
			]);
            $this->db_config->setStep($chat_id, ($data == 'TellAboutStageTwo' ? 4 : 5));
            $this->db_config->setLastActionType($chat_id,15);
		}
        else if ($data == 'MoveToThirdStage' && $user['stage'] == 2 && $user['has_access_next_stage'] == 1)
        {
            Request::editMessageText([
                'chat_id'      => $chat_id,
                'message_id'   => $callback->getMessage()->getMessageId(),
                'text'         => $this->json->third_video,
                'reply_markup' => getStartThirdStageKeyBoard()
            ]);
            $this->db_config->setLastActionType($chat_id,16);
        }
        else if ($data == 'StartThird' && $user['stage'] == 2 && $user['has_access_next_stage'] == 1)
        {
            Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $callback->getMessage()->getMessageId(),
                'text'       => $this->json->third_video
            ]);
            Request::sendMessage([
                'chat_id'                  => $chat_id,
                'disable_web_page_preview' => true,
                'text'                     => $this->json->third,
                'reply_markup'             => joinThirdStageChatKeyBoard()

            ]);
            $this->db_config->setStage($chat_id, 3);
            $this->db_config->setValue($chat_id,'third_take_date',getCurrentDate());
            $this->db_config->setLastActionType($chat_id,17);

        }
		else if (($data == 'TellAboutStageThree' || $data == 'TellIdeaThree') && $user['stage'] == 3 && $user['has_access_next_stage'] == 1)
		{
			$this->db_config->setStep($chat_id, ($data == 'TellAboutStageTwo' ? 6 : 7));
			Request::editMessageText([
				'chat_id'    => $chat_id,
				'message_id' => $callback->getMessage()->getMessageId(),
				'text'       => ($data == 'TellAboutStageThree' ? "Опиши выполнение ДЗ:" : "Опиши инсайт или идею задания:")
			]);
            $this->db_config->setLastActionType($chat_id,18);
		}
		else if ($data=='GetExpertTask')
        {
            Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $callback->getMessage()->getMessageId(),
                'text'       => $this->json->TakeExpertTask
            ]);
            Request::sendMessage([
                'chat_id'      => $chat_id,
                'text'         => $this->json->third_done,
                'reply_markup' => finishMarafonKeyBoard()
            ]);
            $this->db_config->setLastActionType($chat_id,19);
        }
        $this->db_config->setLastActionDate($chat_id);
    }

	public function __construct()
	{
		$this->log();
		$this->init();
		$updates = $this->telegram->handleGetUpdates();
		$message_str = $updates->getProperty('message');
		$message = new \Longman\TelegramBot\Entities\Message($message_str);
		if ($message_str != null)
		{
			$type = $message->getType();
			if ($type == 'text')
			{
				$this->processTextMessage($message);
			}
			else if ($type == 'command')
			{
				$this->processCommand($message);
			}
		}
		else
		{
			$callback_str = $updates->getProperty('callback_query');
			if ($callback_str == null)
			{
				exit();
			}
			$callback = new \Longman\TelegramBot\Entities\CallbackQuery($callback_str);
			$this->processCallBack($callback);
		}
	}
}

new TelegramBot();