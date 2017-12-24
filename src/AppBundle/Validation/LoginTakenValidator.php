<?php
/**
 * Created by PhpStorm.
 * User: argan
 * Date: 11/18/17
 * Time: 4:11 PM
 */

namespace AppBundle\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class LoginTakenValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {



        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}