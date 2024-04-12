<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        /*
        * 1. Create a fake client (browser) that will point to a URL
        * 2. Complete the fields of my registration form
        * 3. See if on my page there is the following message (alert): "Your account has been created successfully! You can login now." (rendered by RegisterController if the form is submitted and valid).
        */

        // 1.
        $client = static::createClient();
        $client->request('GET', '/register'); //

        //2. (firstname, lastname, email, password, password confirmation)
        $client->submitForm('Register', [
            'register[firstname]' => 'Roberts',
            'register[lastname]' => 'Olga',
            'register[email]' => 'roberts@example.com',
            'register[password][first]' => '123456',
            'register[password][second]' => '123456'
        ]);

        //FOLLOW the redirection to the login page

        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        //3.
        $this->assertSelectorExists('div:contains("Your account has been created successfully! You can login now.")');
    }
}
