<?php
//terataillの丸コピ
class Scraper
{
    private $ch;

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_COOKIEFILE => '',
            CURLOPT_FAILONERROR => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
    }

    public static function createDOMXPath($content)
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML($content);
        return new \DOMXPath($dom);
    }

    public function get($url, array $params = [])
    {
        curl_setopt_array($this->ch, [
            CURLOPT_URL => $url . '?' . http_build_query($params, '', '&'),
            CURLOPT_HTTPGET => true,
        ]);
        $response = curl_exec($this->ch);
        if ($response === false) {
            throw new \RuntimeException(curl_error($this->ch));
        }
        return $response;
    }

    public function post($url, array $params = [])
    {
        curl_setopt_array($this->ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params, '', '&'),
        ]);
        $response = curl_exec($this->ch);
        if ($response === false) {
            throw new \RuntimeException(curl_error($this->ch));
        }
        return $response;
    }

    public function getXPath($url, array $params = [])
    {
        return self::createDOMXPath($this->get($url, $params));
    }

    public function postXPath($url, array $params = [])
    {
        return self::createDOMXPath($this->post($url, $params));
    }

}
