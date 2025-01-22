<?php
date_default_timezone_set('UTC');

require dirname(__DIR__) . '/cron/DB.php';
require dirname(__DIR__) . '/cron/adapters/Notifications.php';
$notifications = new Notifications;

$notifications->processedNotifications();