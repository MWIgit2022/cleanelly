<?php

	class RestApi
	{
		private $batch = array();
		private $batch_list = array();
		private $hook = '';

		public static function refreshAuth()
		{
			if (empty($_REQUEST['REFRESH_ID']) || !defined('CLIENT_ID') || !defined('CLIENT_SECRET') || CLIENT_ID == '' || CLIENT_SECRET == '') return false;

			$query = 'https://oauth.bitrix.info/oauth/token/?grant_type=refresh_token&client_id=' . CLIENT_ID . '&client_secret=' . CLIENT_SECRET . '&refresh_token=' . $_REQUEST['REFRESH_ID'];
			$result = file_get_contents($query);
			
			if (empty($result)) return false;
			
			$data = json_decode($result, 1);
			if (empty($data) || empty($data['access_token']) || empty($data['refresh_token'])) return false;
			
			$_REQUEST['AUTH_ID'] = $data['access_token'];
			$_REQUEST['REFRESH_ID'] = $data['refresh_token'];
			$_REQUEST['member_id'] = $data['member_id'];
			$_REQUEST['USER_ID'] = $data['user_id'];
			
			return true;
		}

		public function call($op, $queryData = array(), $pause = true)
		{
			$queryData['hook'] = $this->hook;

			if (!empty($_REQUEST['AUTH_ID'])) {
				$queryData['auth'] = $_REQUEST['AUTH_ID'];
			}

			if (!empty($queryData['hook'])) {
				$url = $queryData['hook'] . $op;
				
				unset ($queryData['auth']);
			} elseif (defined('REST_CALL')) {
				$url = REST_CALL . $op;

				unset ($queryData['auth']);
			} elseif (!empty($_REQUEST['DOMAIN'])) {
				$url = 'https://' . $_REQUEST['DOMAIN'] . '/rest/' . $op;
			} else {
				return false;
			}

			if (isset($queryData['hook'])) {
				unset ($queryData['hook']);
			}

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_POSTFIELDS => http_build_query($queryData),
			));

			$result = curl_exec($curl);
			curl_close($curl);

			$result = json_decode($result, 1);
			
			if (!empty($result) && !empty($result['error'])) {
				if ($result['error'] == 'expired_token') {
					if (self::refreshAuth()) {
						$result = $this->call($op, $queryData, $pause);
						$pause = false;
					}
				}
			}

			if ($pause) {
				sleep(1);
			}

			return $result;
		}

		public function clearBatch($return = false)
		{
			$return = $return ? $this->batch_list : true;
			
			$this->batch = array();
			$this->batch_list = array();
			
			return $return;
		}
		
		public function __construct($hook = '')
		{
			$this->hook = $hook;
			$this->clearBatch();
		}

		public function batchAdd($op, $params, $name = '')
		{
			$callback = false;
			
			if (is_callable($name)) {
				$callback = $name;
				$name = md5($op . http_build_query($params));
			}
			
			if ($name == '') {
				$name = $op;
			}
			
			$this->batch[$name] = $op . '?' . http_build_query($params);
			$this->batch_list[$name] = array(
				'op'     => $op,
				'params' => $params,
				'callback' => $callback,
			);
		}

		public function batchCall($pause = true)
		{
			$return = array();
			
			if (count($this->batch) > 0) {
				foreach (array_chunk($this->batch, 50, true) as $partBatch) {
					$params = array('halt' => 0, 'cmd' => $partBatch, 'hook' => $this->hook);
					$res = $this->call('batch', $params, $pause);
					if (!empty($res) && !empty($res['result'])) {
						foreach ($res['result']['result'] as $name => $result) {
							$return[$name] = array(
								'result' => $result,
								'total'  => isset($res['result']['result_total'][$name]) ? $res['result']['result_total'][$name] : false,
								'next'   => isset($res['result']['result_next'][$name])  ? $res['result']['result_next'][$name]  : false,
								'time'   => isset($res['result']['result_time'][$name])  ? $res['result']['result_time'][$name]  : false,
								'error'  => isset($res['result']['result_error'][$name]) ? $res['result']['result_error'][$name] : false,
								'call'   => $this->batch_list[$name],
							);
							if (isset($return[$name]['call']['params']['ALL']) && $return[$name]['call']['params']['ALL'] == 'Y' || $return[$name]['call']['params']['ALL'] > 0) {
								self::getAllResult($return[$name], $return[$name]['call']['params']['ALL']);
							}
							if ($this->batch_list[$name]['callback']) {
								$this->batch_list[$name]['callback']($return[$name]);
							}
						}
						foreach ($res['result']['result_error'] as $name => $result) {
							if (!isset($return[$name])) {
								$return[$name] = array(
									'error'  => isset($res['result']['result_error'][$name]) ? $res['result']['result_error'][$name] : false,
									'call'   => $this->batch_list[$name],
								);
								if ($this->batch_list[$name]['callback']) {
									$this->batch_list[$name]['callback']($return[$name]);
								}
							}
						}
					}
				}
			}

			$this->clearBatch();

			return $return;
		}
		
		public function getAllResult(&$res, $total = 'Y')
		{
			if (empty($res['call'])) return false;
			
			if ($total == 'Y' || $total > $res['total']) {
				$total = $res['total'];
			}
			
			$op = $res['call']['op'];
			$params = $res['call']['params'];
			
			if ($total > 50) {
				$batch = array();
				for ($page = 2, $pages = ceil($total / 50); $page <= $pages; ++$page) {
					if (isset($params['PARAMS']['NAV_PARAMS']['iNumPage'])) {
						$params['PARAMS']['NAV_PARAMS']['iNumPage'] = $page;
					} else {
						if (!isset($params['start'])) {
							$params['start'] = 0;
						}
						$params['start'] += 50;
					}

					$batch[$page] = $op . '?' . http_build_query($params);
				}

				if (count($batch) > 0) {
					foreach (array_chunk($batch, 50, true) as $partBatch) {
						$arParams = array('halt' => 0, 'cmd' => $partBatch);
						$resBatch = $this->call('batch', $arParams);
						if (!empty($resBatch['result']) && !empty($resBatch['result']['result'])) {
							foreach ($resBatch['result']['result'] as $page => $items) {
								foreach ($items as $item) {
									$res['result'][] = $item;
								}
							}
						} else {
							break;
						}
					}
				} 
			}
		}
	}
?>