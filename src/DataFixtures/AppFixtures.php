<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }


        foreach ($this->getQuestionData() as [$firstName, $lastName, $mail, $text, $status]) {

              $question = new Question();
              $question->setFirstName($firstName);
              $question->setLastName($lastName);
              $question->setMail($mail);
              $question->setText($text);
              $question->setStatus($status);

              $manager->persist($question);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['webmaster', 'webmaster', 'wever123', 'webmaster@mail.com', ['ROLE_USER']],
        ];
    }


    private function getQuestionData(): array
    {
        return [
            // $questionData = [$firstName, $lastName, $mail, $text, $status];
            ['prenomClient1', 'nomClient1', 'nomClient1@mail.fr', 'ceci est une question', "open"],
            ['prenomClient2', 'nomClient2', 'nomClient2@mail.fr', 'ceci est une question2', "open"],
            ['prenomClient3', 'nomClient3', 'nomClient3@mail.fr', 'ceci est une question fermé', "close"],
            ['prenomClient1', 'nomClient1', 'nomClient1@mail.fr', 'ceci est une seconde question du meme client', "open"],
        ];
    }
}
