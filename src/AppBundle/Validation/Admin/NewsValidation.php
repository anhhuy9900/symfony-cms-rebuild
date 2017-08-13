<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


class NewsValidation extends Controller {

    public function validates($entity)
    {
        global $kernel;

        $validator = Validation::createValidator();
        $violations = [];
        $violations[] = $validator->validate($entity->getName(),
            [
                new NotNull([
                    'message' => 'Name is not null'
                ]),
                new Length([
                    'min'        => 6,
                    'max'        => 100,
                    'minMessage' => 'Name must be at least {{ limit }} characters long',
                    'maxMessage' => 'Name cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        $violations[] = $validator->validate($entity->getDescription(),
            [
                new NotNull([
                    'message' => 'Name is not null'
                ]),
                new Length([
                    'min'        => 100,
                    'max'        => 500,
                    'minMessage' => 'Description must be at least {{ limit }} characters long',
                    'maxMessage' => 'Description cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        $violations[] = $validator->validate($entity->getContent(),
            [
                new NotNull([
                    'message' => 'Content is not null'
                ]),
                new Length([
                    'min'        => 100,
                    'minMessage' => 'Content must be at least {{ limit }} characters long',
                ]),
            ]
        );

        return $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($violations);

    }
}
