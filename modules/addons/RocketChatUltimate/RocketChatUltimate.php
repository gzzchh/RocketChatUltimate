<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function RocketChatUltimate_config()
{
    return array(
        'name' => 'RocketChatUltimate(终极RocketChat模块)',
        'description' => '该模块提供了在线聊天与消息通知（づ￣3￣）づ╭❤～',
        'author' => 'MisakaCloud',
        'language' => 'chinese',
        'version' => '1.0',
        'fields' => array(
            'rocketchat-url' => array(
                'FriendlyName' => 'RocketChat安装地址 (^_−)☆',
                'Type' => 'text',
                'Size' => '25',
                'Default' => 'https://rocket.chat',
                'Description' => '请输入您的RocketChat实例地址 (例如: https://chat.domain.tld/)',
            ),
            'rocketchat-enable' => array(
                'FriendlyName' => '是否启用?',
                'Type' => 'yesno',
                'Description' => '勾上就启用了 (｀・ω・´)',
            ),
        )
    );
}