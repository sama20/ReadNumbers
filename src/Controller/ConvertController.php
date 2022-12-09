<?php
namespace App\Controller;

use RegisterUserRequest;
use ResourceBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Salarmehr\Cosmopolitan\Cosmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Author;
use App\Validation\ConvertValidator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Json;



use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConvertController
{   
//     private $validator;

// public function __construct(
//     ValidatorInterface $validator
//     ) {
//     // $this->validator = $validator;
// }
public function author(ValidatorInterface $validator)
{
    $author = new Author();

    // ... do something to the $author object

    $errors = $validator->validate($author);

    if (count($errors) > 0) {
        /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */
        $errorsString = (string) $errors;

        return new Response($errorsString);
    }

    return new Response('The author is valid! Yes!');
}
    public function readNumber(Request $request): Response
    {
        if($error = ConvertValidator::readNumber($request->getContent())){
            return $error;
        }
        $payload = json_decode($request->getContent(), false);
        $cosmo = Cosmo::create($payload->language)->spellout($payload->number);

        $response = new Response(json_encode(['spellout' => $cosmo,], JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getLanguages(){
        $languages =ResourceBundle::getLocales('');
        $cosmo = new Cosmo('en');
        $newLangs=[];
        foreach ($languages as $key => $lang) {
            $newLangs[$lang] = $cosmo->language($lang); 
        }
        return new Response(json_encode($newLangs, JSON_UNESCAPED_UNICODE));
    }
}
