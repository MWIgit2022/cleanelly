<?php
	class RestApi
	{
		private $batch = array();
		private $batch_list = array();
		private $hook = '';
		public $saveData = array();
		
		private function clearBatch()
		{
			$this->batch = array();
			$this->batch_list = array();
		}
		
		public function __construct($hook = '')
		{
			$this->hook = $hook;
			$this->clearBatch();
			$this->initConfig();
		}

		public function initConfig()
		{
			if (!defined('MARKET_SAVEAUTH') || MARKET_SAVEAUTH != 'Y') return false;
			if (empty($_REQUEST['DOMAIN']) || empty($_REQUEST['member_id'])) return false;
			
			$saveData = false;

			$configFile = RestApi::marketInstallFile('config');
			if (is_file($configFile)) {
				include $configFile;
				
				if ($_REQUEST['member_id'] == $saveData['MEMBER_ID']) {
					$_REQUEST['AUTH_ID'] = $saveData['AUTH_ID'];
					$_REQUEST['REFRESH_ID'] = $saveData['REFRESH_ID'];
					$_REQUEST['DOMAIN'] = $saveData['DOMAIN'];
					$_REQUEST['member_id'] = $saveData['MEMBER_ID'];
					$_REQUEST['USER_ID'] = $saveData['USER_ID'];
				} else {
					$saveData = false;
				}
			}

			if (!$saveData && !empty($_REQUEST['AUTH_ID']) && !empty($_REQUEST['REFRESH_ID'])) {
				$res = $this->call('profile');
				if (!empty($res) && !empty($res['result']) & $res['result']['ADMIN']) {
					$saveData = self::saveConfig();
				}
			}

			$this->saveData = $saveData;
		}
		
		public static function saveConfig()
		{
			if (!defined('MARKET_SAVEAUTH') || MARKET_SAVEAUTH != 'Y') return false;
			
			$saveData = false;

			$configFile = RestApi::marketInstallFile('config');
			if (is_file($configFile)) {
				include $configFile;
			}
			
			$saveData['AUTH_ID'] = $_REQUEST['AUTH_ID'];
			$saveData['REFRESH_ID'] = $_REQUEST['REFRESH_ID'];
			if (!empty($_REQUEST['DOMAIN'])) {
				$saveData['DOMAIN'] = $_REQUEST['DOMAIN'];
			}
			if (!empty($_REQUEST['member_id'])) {
				$saveData['MEMBER_ID'] = $_REQUEST['member_id'];
			}
			if (!empty($_REQUEST['USER_ID'])) {
				$saveData['USER_ID'] = $_REQUEST['USER_ID'];
			}

			file_put_contents($configFile, '<?php $saveData = ' . var_export($saveData, 1) . '; ?>');
			
			return $saveData;
		}
		
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
			
			self::saveConfig();
			
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

			if (defined('MARKET_LOG_DIR')) {
				$logData = $queryData;
				if (isset($logData['auth'])) {
					unset($logData['auth']);
				}
				file_put_contents(MARKET_LOG_DIR . '/calls24.log', date('Y-m-d H:i:s: ') . $url . "\n" . print_r($logData, 1) . "\n" . print_r($result, 1) . "\n\n", FILE_APPEND);
			}

			if ($pause) {
				sleep(1);
			}

			return $result;
		}

		public function getAllList($op, $arParams, $pause = true)
		{
			$res = $this->call($op, $arParams, $pause);

			if ($res['total'] > 50) {
				$batch = array();
				for ($page = 2, $pages = ceil($res['total'] / 50); $page <= $pages; ++$page) {
					if (isset($arParams['PARAMS']['NAV_PARAMS']['iNumPage'])) {
						$arParams['PARAMS']['NAV_PARAMS']['iNumPage'] = $page;
					} else {
						if (!isset($arParams['start'])) {
							$arParams['start'] = 0;
						}
						$arParams['start'] += 50;
					}

					$batch[$page] = $op . '?' . http_build_query($arParams);
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

			return $res;
		}
		
		public function batchAdd($op, $params, $name = '')
		{
			if ($name == '') {
				$name = $op;
			}
			
			$this->batch[$name] = $op . '?' . http_build_query($params);
			$this->batch_list[$name] = array(
				'op'     => $op,
				'params' => $params,
			);
		}

		public function batchCall($pause = true)
		{
			$return = array();
			
			if (count($this->batch) > 0) {
				foreach (array_chunk($this->batch, 50, true) as $partBatch) {
					$params = array('halt' => 0, 'cmd' => $partBatch, 'hook' => $this->hook);
					$res = $this->call('batch', $params, $pause);
					if (!empty($res) && !empty($res['result']) && !empty($res['result']['result'])) {
						foreach ($res['result']['result'] as $name => $result) {
							$return[$name] = array(
								'result' => $result,
								'total'  => isset($res['result']['result_total'][$name]) ? $res['result']['result_total'][$name] : false,
								'next'   => isset($res['result']['result_next'][$name])  ? $res['result']['result_next'][$name]  : false,
								'time'   => isset($res['result']['result_time'][$name])  ? $res['result']['result_time'][$name]  : false,
								'error'  => isset($res['result']['result_error'][$name]) ? $res['result']['result_error'][$name] : false,
								'call'   => $this->batch_list[$name],
							);
						}
						foreach ($res['result']['result_error'] as $name => $result) {
							if (!isset($return[$name])) {
								$return[$name] = array(
									'error'  => isset($res['result']['result_error'][$name]) ? $res['result']['result_error'][$name] : false,
									'call'   => $this->batch_list[$name],
								);
							}
						}
					}
				}
			}

			$this->clearBatch();

			return $return;
		}
		
		public function getAllResult(&$res)
		{
			if (empty($res['call'])) return false;
			
			$op = $res['call']['op'];
			$params = $res['call']['params'];
			
			if ($res['total'] > 50) {
				$batch = array();
				for ($page = 2, $pages = ceil($res['total'] / 50); $page <= $pages; ++$page) {
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
	
	function saveLog($op, $data = '', $result = '')
	{
		$text = date('Y-m-d H:i:s') . ') Event: ' . $op;
		if (!empty($data)) {
			if (is_array($data)) {
				foreach ($data as $k => $d) {
					if (!is_numeric($k)) {
						$text .= ", $k - $d";
					} else {
						$text .= ", $d";
					}
				}
			} else {
				$text .= ", $data";
			}
		}
		if (!empty($result)) {
			if (is_array($result)) {
				$result = implode(', ', $result);
			}
			
			$text .= ": $result";
		}

		if (!defined('DIR_PATH')) {
			define('DIR_PATH', __DIR__);
		}
		
		file_put_contents(DIR_PATH . '/bitrix24.log',  $text . "\n", FILE_APPEND);
	}

	function echoDebug($data, $exit = false)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';

		if (!$exit) return true; else exit;
	}
?>