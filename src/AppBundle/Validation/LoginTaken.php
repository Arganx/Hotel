<?php
/**
 * Created by PhpStorm.
 * User: argan
 * Date: 11/18/17
 * Time: 4:06 PM
 */

namespace AppBundle\Validation;


use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class LoginTaken extends Constraint
{
    public $message = 'This login is already taken {{ string }} ';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

}