<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;


class CategoriesNewsValidation extends Controller {

    public function validates($entity)
    {
        global $kernel;

        $validator = Validation::createValidator();
        $violations = [];
        $violations[] = $validator->validate($entity->getName(),
            [
                new NotNull([
                    'message' => 'Category name is not null'
                ]),
                new Length([
                    'min'        => 6,
                    'max'        => 100,
                    'minMessage' => 'Category name must be at least {{ limit }} characters long',
                    'maxMessage' => 'Category name cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        return $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($violations);

    }
}
