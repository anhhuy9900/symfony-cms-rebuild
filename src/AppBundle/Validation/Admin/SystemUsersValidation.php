<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;


class SystemUsersValidation extends Controller {

    public function validates($entity)
    {
        global $kernel;

        $validator = Validation::createValidator();
        $violations = [];
        $violations[] = $validator->validate($entity->getUsername(),
            [
                new NotNull([
                    'message' => 'Username is not null'
                ]),
                new Length([
                    'min'        => 6,
                    'max'        => 100,
                    'minMessage' => 'Username must be at least {{ limit }} characters long',
                    'maxMessage' => 'Username cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        $violations[] = $validator->validate($entity->getEmail(),
            [
                new NotNull([
                    'message' => 'Email is not null'
                ]),
                new Email(
                    array(
                        'message' => 'Email invalid',
                    )
                )
            ]
        );

        $violations[] = $validator->validate($entity->getPassword(),
            [
                new NotNull([
                    'message' => 'Password is not null'
                ]),
                new Length([
                    'min'        => 6,
                    'max'        => 100,
                    'minMessage' => 'Password must be at least {{ limit }} characters long',
                    'maxMessage' => 'Password cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        return $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($violations);

    }
}
