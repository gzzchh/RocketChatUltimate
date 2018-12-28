<?php

namespace WHMCS\Module\Notification\RocketChat;

require_once(dirname(__FILE__) . '/vendor/autoload.php');
use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client as Guzzle;
use WHMCS\Config\Setting;
use WHMCS\Exception;
use WHMCS\Module\Notification\DescriptionTrait;
use WHMCS\Module\Contracts\NotificationModuleInterface;
use WHMCS\Notification\Contracts\NotificationInterface;

class RocketChat implements NotificationModuleInterface
{
    use DescriptionTrait;

    public function __construct()
    {
        $this->setDisplayName('RocketChat')
            ->setLogoFileName('logo.svg');
    }

    public function settings()
    {
        // return [
        //     'baseURL' => [
        //         'FriendlyName' => 'RocketChat Base URL',
        //         'Type' => 'text',
        //         'Description' => 'The base URL for your RocketChat instance (ie: https://chat.example.com)',
        //         'Placeholder' => "",
        //     ],
        // ];
        // return [];
    }

    public function testConnection($settings)
    {
        return true;
    }

    public function notificationSettings()
    {
        return [
            'notificationToken' => [
                'FriendlyName' => '传入WebHook Token (｡◕ˇ∀ˇ◕)',
                'Type' => 'text',
                'Description' => '选择一个传入WebHook所用的Token (如果有多个,用逗号分隔) (´･ᴗ･`)',
                'Required' => true,
            ],
        ];

    }

    public function getDynamicField($fieldName, $settings)
    {
        return [];
    }

    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
        $to = explode(',', $notificationSettings['notificationToken']);
        $to = array_filter(array_unique($to));
        if (!$to) {
            throw new Exception('没有找到通知Token !!!∑(ﾟДﾟノ)ノ');
        }
        $postData = [
            'text' => sprintf("Ticket %s %s (%s)", $notification->getTitle(), $notification->getMessage(), $notification->getUrl()),
        ];
        foreach ($notification->getAttributes() as $attribute) {
            $postData['attachments'][] = [
                'title' => $attribute->getLabel(),
                'text' => $attribute->getValue(),
                'title_link' => $attribute->getUrl(),
            ];
        }
        foreach ($to as $k => $notificationToken) {
            // $notificationURL = sprintf("%s/hooks/%s", $moduleSettings['baseURL'], $notificationToken);
            $baseURL = Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'RocketChatUltimate')->WHERE('setting', '=', 'rocketchat-url')->pluck('value');
            $notificationURL = sprintf("%s/hooks/%s", $baseURL, $notificationToken);
            var_dump($baseURL);
            var_dump($moduleSettings['baseURL']);
            $client = new Guzzle();
            $response = $client->request('POST', $notificationURL, ['json' => $postData]);
            if (array_key_exists('error', $response)) {
                throw new Exception($response['error']);
            }
        }
    }
}
