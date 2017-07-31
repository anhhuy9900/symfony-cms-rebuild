<?php
namespace AppBundle\Validation\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class AdminSystemRolesValidation extends Controller {

    public $roleName;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('roleName', new Assert\NotNull(
            array(
                'message' => 'Role Name is not null',
            )
        ));
        $metadata->addPropertyConstraint('roleName', new Assert\Length(array(
            'min'        => 6,
            'max'        => 100,
            'minMessage' => 'Role Name must be at least {{ limit }} characters long',
            'maxMessage' => 'Role Name cannot be longer than {{ limit }} characters',
        )));


        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    public function validate(ExecutionContextInterface $context)
    {
        global $kernel;

        $admincp_service = $kernel->getContainer()->get('app.admincp_service');

    }
}
