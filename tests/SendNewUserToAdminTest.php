<?php

namespace App\Tests;

use App\Entity\User;
use App\Utils\Sender;
use PHPUnit\Framework\TestCase;

class SendNewUserToAdminTest extends TestCase
{
    public function testSendNewUser(): void
    {
        $user = new User();
        $user->setEmail("test@gmail.com");

        $sender = new Sender();
        $sender->sendNewUserNotificationToAdmin($user);

        $this->assertContains(file_get_contents('notif.txt'), [$user->getEmail()], "File must contain " . $user->getEmail());
    }
}
