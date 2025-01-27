<?php
  class smsUnisender {
    private $token;

/**
 * $token - API ключ
 */
    public function __construct($token) {
      $this->token = $token;
    }

/**
 * Отправляет команду на sms.unisender.by/api/v1/.
 *   Если команда обработана успешно, возвращает ответ от API в виде объекта.
 *   Если команда обработана неуспешно - передаёт ошибку методу error() и возвращает false.
 * $command - команда API
 * $params - ассоциативный массив, ключи которого являются названиями параметров команды кроме token, значения - их значениями.
 *   token в $params передавать не нужно.
 *   Необязательный параметр, так как для таких команд, как getLimit, getMessagesList, getPasswordObjects никаких параметров передавать не нужно.
 */
    private function sendRequest($command, $params=array()) {
      $url = 'http://sms.unisender.by/api/v1/'.$command.'?token='.$this->token;
      if (!empty($params)) {
        foreach ($params as $k => $v)
          $url .= '&'.$k.'='.urlencode($v);
      }
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
      $result = curl_exec($ch);
      curl_close($ch);

      $result = json_decode($result);
      if (isset($result->error)) {
        $this->error($result->error);
        return false;
      }
      else
        return $result;
    }

/**
 * Обрабатывает ошибки.
 *   Здесь может быть любой код, обрабатывающий пришедшую по API ошибку, соответствующий вашему приложению.
 * $error - текст ошибки
 */
    private function error($error) {
      trigger_error("<b>smsUnisender error:</b> $error");
    }

/**
 * Метод-обёртка для команды getLimit
 */
    public function getLimit() {
      return $this->sendRequest('getLimit');
    }

/**
 * Метод-обёртка для команды createSMSMessage
 * $message - текст создаваемого сообщения
 * $alphaname_id - ID альфа-имени, необязательный параметр
 */
    public function createSMSMessage($message, $alphaname_id=0) {
      $params['message'] = $message;
      if (!empty($alphaname_id))
        $params['alphaname_id'] = (integer)$alphaname_id;
      return $this->sendRequest('createSmsMessage', $params);
    }

/**
 * Метод-обёртка для команды checkSMSMessageStatus
 * $message_id - ID созданного сообщения
 */
    public function checkSMSMessageStatus($message_id) {
      $params['message_id'] = (integer)$message_id;
      return $this->sendRequest('checkSMSMessageStatus', $params);
    }

/**
 * Метод-обёртка для команды getMessagesList
 */
    public function getMessagesList() {
      return $this->sendRequest('getMessagesList');
    }

/**
 * Метод-обёртка для команды sendSms
 * $message_id - ID созданного сообщения
 * $phone - номер телефона в формате 375291234567
 */
    public function sendSms($message_id, $phone) {
      $params['message_id'] = (integer)$message_id;
      $params['phone'] = $phone;
      return $this->sendRequest('sendSms', $params);
    }

/**
 * Метод-обёртка для команды checkSMS
 * $sms_id - ID отправленного SMS
 */
    public function checkSMS($sms_id) {
      $params['sms_id'] = (integer)$sms_id;
      return $this->sendRequest('checkSMS', $params);
    }

/**
 * Метод-обёртка для команды createPasswordObject
 * $type_id - тип создаваемого объекта пароля, может принимать значения letters, numbers и both
 * $len - длина создаваемого объекта пароля, целое число от 1 до 16
 */
    public function createPasswordObject($type_id, $len) {
      $params['type_id'] = $type_id;
      $params['len'] = (integer)$len;
      return $this->sendRequest('createPasswordObject', $params);
    }

/**
 * Метод-обёртка для команды editPasswordObject
 * $password_object_id - ID созданного объекта пароля
 * $type_id - тип создаваемого объекта пароля, может принимать значения letters, numbers и both
 * $len - длина создаваемого объекта пароля, целое число от 1 до 16
 */
    public function editPasswordObject($password_object_id, $type_id, $len) {
      $params['id'] = (integer)$password_object_id;
      $params['type_id'] = $type_id;
      $params['len'] = (integer)$len;
      return $this->sendRequest('editPasswordObject', $params);
    }

/**
 * Метод-обёртка для команды deletePasswordObject
 * $password_object_id - ID созданного объекта пароля
 */
    public function deletePasswordObject($password_object_id) {
      $params['id'] = (integer)$password_object_id;
      return $this->sendRequest('deletePasswordObject', $params);
    }

/**
 * Метод-обёртка для команды getPasswordObjects
 */
    public function getPasswordObjects() {
      return $this->sendRequest('getPasswordObjects');
    }

/**
 * Метод-обёртка для команды getPasswordObject
 * $password_object_id - ID созданного объекта пароля
 */
    public function getPasswordObject($password_object_id) {
      $params['id'] = (integer)$password_object_id;
      return $this->sendRequest('getPasswordObject', $params);
    }

/**
 * Метод-обёртка для команды sendSmsMessageWithCode
 * $message - текст создаваемого сообщения
 * $password_object_id - ID созданного объекта пароля
 * $phone - номер телефона в формате 375291234567
 * $alphaname_id - ID альфа-имени, необязательный параметр
 */
    public function sendSmsMessageWithCode($message, $password_object_id, $phone, $alphaname_id=0) {
      $params['message'] = $message;
      $params['password_object_id'] = (integer)$password_object_id;
      $params['phone'] = $phone;
      if (!empty($alphaname_id))
        $params['alphaname_id'] = (integer)$alphaname_id;
      return $this->sendRequest('sendSmsMessageWithCode', $params);
    }

  }
?>
