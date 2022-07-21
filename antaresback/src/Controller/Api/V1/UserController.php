<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



/**
 * Class for User
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class UserController extends AbstractController
{
    /**
    *Collection Users
    *
    * @Route("/users", name="users_get_collection", methods={"GET"})
    */
    public function usersGetCollection(UserRepository $userRepository ): Response
    {
        $userList = $userRepository->findAll();
       
        return $this->json(['userList' => $userList], Response::HTTP_OK, [], ['groups'=> 'users_get_item']);
    }

    /**
     * Recovery of a given user
     * 
     * @Route("/users/{id}", name="users_get_item", methods={"GET"})
     */
    public function usersGetItem(User $user = null): Response
    {
       
        // 404 ?
        if ($user === null) {
            return $this->json(['error' => '404 Page non trouvé'], Response::HTTP_NOT_FOUND);
        }

       // $this->denyAccessUnlessGranted('USER_VIEW', $user);
       
        return $this->json($user, Response::HTTP_OK, [], ['groups'=> 'users_get_item']);
       
    }

    /**
     * Add a specific user
     * 
     * @Route("/users/registration", name="users_registration", methods={"POST"})
     */
    public function usersRegistrationPost(
        Request $request, 
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        
        try {       
        // We need to retrieve the JSON content found in the Request
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        $user= $serializer->deserialize($jsonContent, User::class, 'json');
        //dd($user);
        $errors = $validator->validate($user);
        //gestion error
        if (count($errors) > 0) {
            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                // On push tout ça dans un tableau à la clé $property
                $cleanErrors[$property][] = $message;
                // array_push($cleanErrors[$property], $message);
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //dd($user->getPassword());
        //Tranform Password crypted
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        //dd($hashedPassword);
        $user->setPassword($hashedPassword);
        //Created User Role USER default
        $user->setRoles(['ROLE_USER']);
        // Save DataBase
        $em = $doctrine->getManager();
        
        $em->persist($user);

        $em->flush();

        // On retourne une réponse qui contient (REST !)
        // - un status code 201
        // - un en-tête (header) Location: URL_DE_LA_RESSOURCE_CREEE
        // - option perso : le JSON de l'entité créée
        
            return $this->json(
                ['user' => $user],
            // Status code : 201 CREATED
            Response::HTTP_CREATED,
                [
                'Location' => $this->generateUrl('api_v1_users_get_item', ['id' => $user->getId()])
            ],
                ['groups' => 'users_get_item']
            );

        } catch (NotEncodableValueException $e) { 
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    

    /**
     * Editing a given user
     * 
     * @Route("/users/{id}/update", name="users_edit", methods={"PUT"})
     */
    public function usersEditPut(
        Request $request,
        User $user = null,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
         //404
         if($user === null)
         {
             return $this->json(["error" => "Utilisateur non trouvé."], Response::HTTP_NOT_FOUND);
         }

         $this->denyAccessUnlessGranted('USER_EDIT', $user);
         
         try {   
         $jsonContent = $request->getContent();
         //dd($jsonContent);
         $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

         $errors = $validator->validate($user);

         if (count($errors) > 0) {
             $cleanErrors = [];
 
             /** @var ConstraintViolation $error */
             foreach ($errors as $error) {
 
                 // On récupère les infos
                 $property = $error->getPropertyPath(); // 'title'
                 $message = $error->getMessage(); // 'This value is already used.'
 
                 $cleanErrors[$property][] = $message;
        
             }
 
             return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
         }
            //gestion hasher et deshasher mot de passe
            $Passwordmodificated = $passwordHasher->needsRehash($user, $user->getPassword());
            //dd($Passwordmodificated);
            if($Passwordmodificated === true){
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
 
            $em->flush();
 
         return $this->json(
            ['user' => $user],
        // Status code : 201 CREATED
        Response::HTTP_CREATED,
            [
            'Location' => $this->generateUrl('api_v1_users_get_item', ['id' => $user->getId()])
        ],
            ['groups' => 'users_get_item']
        );

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete User
     * 
     * @Route("/users/delete/{id}", name="delete_users", methods={"DELETE"})
     */
    public function deleteUsers(
        ManagerRegistry $doctrine,
        User $user = null
    ): Response
    {
        // 404 
        if ($user === null) {
            return $this->json(['error' => '404 Page non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted('USER_DELETE', $user);

        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé'], Response::HTTP_OK);
    }
}