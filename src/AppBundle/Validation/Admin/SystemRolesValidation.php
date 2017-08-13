<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;


class SystemRolesValidation extends Controller {

    public function validates($entity)
    {
        global $kernel;

        $validator = Validation::createValidator();
        $violations = [];
        $violations[] = $validator->validate($entity->getRoleName(),
            [
                new NotNull([
                    'message' => 'Role name is not null'
                ]),
                new Length([
                    'min'        => 3,
                    'max'        => 100,
                    'minMessage' => 'Role name must be at least {{ limit }} characters long',
                    'maxMessage' => 'Role name cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        return $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($violations);

    }
}
