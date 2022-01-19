<?php

class CAvtoEvents
{
	public static function OnSaleOrderSaved (\Bitrix\Main\Event $event)
	{
		$order  = $event->getParameter("ENTITY");
		$values = $event->getParameter("VALUES");
		$isNew  = $event->getParameter("IS_NEW");

		if ($isNew) return true;

		$data = array(
			'event' => 'ONORDERUPDATE',
			'data' => array('FIELDS' => array('ID' => $order->getField('ID'))),
			'auth' => array('application_token' => 'wh4fb0ys989nm76oq076aa91xwvvb8r4'),
		);

		$opts = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($opts);
		$result = file_get_contents('https://www.cleanelly.ru/local/bitrix24/onOrderAdd.php', false, $context);
	}
}

?>