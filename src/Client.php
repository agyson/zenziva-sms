<?php

namespace Agyson\ZenzivaSms;

use Requests;

class Client
{
    const TIMEOUT = 60;

    /**
     * Zenziva end point
     *
     * @var string
     */
    protected $url = 'https://console.zenziva.net/{layanan}/api/{method}';

    /**
     * Zenziva username
     *
     * @var string
     */
    protected $username;

    /**
     * Zenziva password
     *
     * @var string
     */
    protected $password;

    /**
     * Phone number
     *
     * @var string
     */
    public $to;

    /**
     * Message
     *
     * @var string
     */
    public $text;

    /**
     * Layanan
     *
     * @var string
     */
    public $layanan;

    /**
     * Method
     *
     * @var string
     */
    public $method;

    /**
     * Create the instance
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Change default URL or get current URL
     *
     * @param string $url
     */
    public function url($url = '')
    {
        if (!$url) {
            return $this->url;
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set destination phone number
     *
     * @param $to  Phone number
     *
     * @return self
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set messages
     *
     * @param $text  Message
     *
     * @return self
     */
    public function text($text)
    {
        if (! is_string($text)) {
            throw new \Exception('Text should be string type.');
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Set masking
     *
     * @param boolean $otp  OTP or Not
     *
     * @return self
     */
    public function masking($otp = false)
    {
        $this->layanan = 'masking';
        $this->method = $otp ? 'sendOTP' : 'sendsms';

        return $this;
    }

    /**
     * Set SMS Reguler
     *
     * @param boolean $otp  OTP or Not
     *
     * @return self
     */

     public function reguler($otp = false)
     {
         $this->layanan = 'reguler';
         $this->method = $otp ? 'sendOTP' : 'sendsms';

         return $this;
     }

    /**
     * Set WA Reguler
     *
     *
     * @return self
     */

     public function waReguler()
     {
         $this->layanan = 'wareguler';
         $this->method = 'sendWA';

         return $this;
     }

    /**
     * Set Text To Voice
     *
     *
     * @return self
     */

     public function textToVoice()
     {
         $this->layanan = 'voice';
         $this->method = 'sendvoice';

         return $this;
     }

    /**
     * @param $to  Phone number
     * @param $text  Message
     *
     * @return \Requests_Response
     * @throws \Exception
     */
    public function send($to = '', $text = '')
    {
        if (! is_string($text)) {
            throw new \Exception('Text should be string type.');
        }

        $this->to   = ! empty($to) ? $to : $this->to;
        $this->text = ! empty($text) ? $text : $this->text;

        if (empty($this->to)) {
            throw new \Exception('Destination phone number is empty.');
        }

        if (is_null($this->text)) {
            throw new \Exception('Text is not set.');
        }

        $url = $this->buildQuery();

        return $this->doRequest($url);
    }

    /**
     * @param  string $url
     * @return \Requests_Response
     */
    private function doRequest($url)
    {
        $options = [
            'timeout' => self::TIMEOUT,
        ];

        $content = [
            'userkey' => $this->username,
            'passkey' => $this->password,
            'to'    => $this->to,
            'message'   => $this->text,
        ];

        if ($this->layanan == 'reguler' && $this->method == "sendOTP") {
          $content['kode_otp'] = $this->text;
          unset($content['message']);
        }

        return Requests::post($url, [], $content);

        // return Requests::get($url, [], $options);
    }

    /**
     * Build query string
     *
     * @return string
     */
    protected function buildQuery()
    {
        $url = str_replace('{layanan}', $this->layanan, $this->url);
        $url = str_replace('{method}', $this->method, $url);

        // $type = [];
        // if ($this->type) {
        //     $type = [
        //         'type' => $this->type,
        //     ];
        // }
        //
        // $params = http_build_query(array_merge([
        //     'userkey' => $this->username,
        //     'passkey' => $this->password,
        //     'to'    => $this->to,
        //     'message'   => $this->text,
        // ], $type));
        //
        // $params = urldecode($params);

        // return $url . '?' . $params;
        return $url;
    }
}
