<?php
namespace App\Validation;

use Exception;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Json;

// ...
use Symfony\Component\Validator\Constraints as Assert;

class ConvertValidator 
{
    public static function readNumber($input){
        $input = json_decode($input, true);
        if(!$input){
            return new Response(json_encode(['error' => 'input json is not valid','value' => ''], JSON_UNESCAPED_UNICODE));
        }
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'number' => [
                new Assert\NotBlank(),
                new Assert\PositiveOrZero(),
                new Assert\Type(['type' => 'integer']),
                new Assert\Length(['min' => 1,'max'=>16]),
            ],
            'language' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2,'max'=>3]),
            ],
        ]);
    
        $validationResult = $validator->validate($input, $constraints);
        if (0 !== count($validationResult)) {
            return new Response(json_encode(['error' => $validationResult[0]->getMessage(),'value' => $validationResult[0]->getPropertyPath()], JSON_UNESCAPED_UNICODE));
        }
    }
}
