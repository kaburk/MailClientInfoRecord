<?php

/**
 * [ControllerEventListener] MailClientInfoRecord
 *
 * @link https://blog.kaburk.com/
 * @author kaburk
 * @package MailClientInfoRecord
 * @license MIT
 */
class MailClientInfoRecordControllerEventListener extends BcControllerEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = [
		'Mail.Mail.initialize',
		'Mail.Mail.startup',
	];
	public function mailMailInitialize(CakeEvent $event) {

		$Controller = $event->subject();

		$Controller->Security->unlockedFields = array_merge(
			$Controller->Security->unlockedFields,
			[
				'MailMessage.ip_address',
				'MailMessage.user_agent',
			]
		);

	}

	/**
	 * メール送信
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function mailMailStartup(CakeEvent $event) {

		$Controller = $event->subject();

		// 送信完了時
		if ($Controller->action == 'submit') {
			foreach ($Controller->dbDatas['mailFields'] as $item) {
				if ($item['MailField']['field_name'] == 'ip_address') {
					$Controller->request->data['MailMessage']['ip_address'] = $Controller->request->clientIp(false);
				}
				if ($item['MailField']['field_name'] == 'user_agent') {
					$Controller->request->data['MailMessage']['user_agent'] = $Controller->request->header('User-Agent');
				}
			}
		}

	}
}
