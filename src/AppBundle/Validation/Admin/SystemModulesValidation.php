<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;


class SystemModulesValidation extends Controller {

    public function validates($entity)
    {
        global $kernel;

        $validator = Validation::createValidator();
        $violations = [];
        $violations[] = $validator->validate($entity->getModuleName(),
            [
                new NotNull([
                    'message' => 'Module Name is not null'
                ]),
                new Length([
                    'min'        => 6,
                    'max'        => 100,
                    'minMessage' => 'Module Name must be at least {{ limit }} characters long',
                    'maxMessage' => 'Module Name cannot be longer than {{ limit }} characters',
                ]),
            ]
        );

        $violations[] = $validator->validate($entity->getModuleAlias(),
            [
                new NotNull([
                    'message' => 'Module Alias is not null'
                ])
            ]
        );

        $violations[] = $validator->validate($entity->getModuleOrder(),
            [
                new NotNull([
                    'message' => 'Module Order is not null'
                ]),
                new Type([
                    'type'    => 'integer',
                    'message' => 'Module Order {{ value }} is not a valid {{ type }}'
                ])
            ]
        );

        return $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($violations);

    }
}
