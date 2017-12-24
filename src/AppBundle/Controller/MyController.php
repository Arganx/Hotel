<?php

namespace AppBundle\Controller;

use AppBundle\Forms\user_form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\users1;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MyController extends Controller
{
    /**
     * @Route("/my", name="my_list")
     */
    public function listAction(Request $request)
    {
        return $this->render('my/my.html.twig');
    }

    /**
     * @Route("/second", name="my_second")
     */
    public function secondAction(Request $request)
    {

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:users1')
            ->findAll();
        return $this->render('my/second.html.twig',array('theUser'=>$user));
    }
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request,AuthenticationUtils $authenticationUtils)
    {

        $errors = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('my/login.html.twig',array(
            'errors' => $errors,
            'username' => $lastUserName,
        ));
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user=new users1();

        $form = $this->createFormBuilder($user)
            ->add('username',TextType::class)
            ->add('password',RepeatedType::class,array(
                'type' => PasswordType::class,
                'invalid_message' => 'Hasla nie sa takie same',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),))
            ->add('email',EmailType::class)
            ->add('save',SubmitType::class,array('label'=>'Register'))
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

            $username = $form['username']->getData();
            $password = $form['password']->getData();
            $email = $form['email']->getData();

            $user->setUsername($username);
            $user->setPassword($passwordEncoder->encodePassword($user,$password));
            $user->setType('normal');
            $user->setEmail($email);

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();



            return $this->redirect('/');
            }

        return $this->render('my/register.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {

    }

    /**
     * @Route("/superRegister", name="superRegister")
     */
    public function superRegister(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user=new users1();

        $form = $this->createFormBuilder($user)
            ->add('username',TextType::class)
            ->add('password',RepeatedType::class,array(
                'type' => PasswordType::class,
                'invalid_message' => 'Hasla nie sa takie same',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),))
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'normal' => 'normal',
                    'admin' => 'admin',
                ),
                ))
            ->add('email',EmailType::class)
            ->add('save',SubmitType::class,array('label'=>'Register'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form['username']->getData();
            $password = $form['password']->getData();
            $type = $form['type']->getData();
            $email = $form['email']->getData();

            $user->setUsername($username);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $user->setType($type);
            $user->setEmail($email);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirect('/');
        }
        return $this->render('my/superRegister.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/deleteUser", name="deleteUser")
     */
    public function deleteUser(Request $request)
    {
        $user=new users1();

        $form = $this->createFormBuilder($user)
            ->add('username',EntityType::class,array(
                'class' => users1::class,
                'choice_label' => 'username',

            ))
            ->add('delete',SubmitType::class,array('label'=>'Usun'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form['username']->getData();

            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(users1::class)->find($username);
            if (!$product) {
                throw $this->createNotFoundException(
                    'No user found for name '
                );
            }
            $em->remove($product);
            $em->flush();

        }

        return $this->render('my/deleteUser.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/editUser", name="editUser")
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user=new users1();

        $form = $this->createFormBuilder($user)
            ->add('username',EntityType::class,array(
                'class' => users1::class,
                'choice_label' => 'username',

            ))
            ->add('password',TextType::class)
            ->add('delete',SubmitType::class,array('label'=>'Zmien haslo'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form['username']->getData();
            $password = $form['password']->getData();

            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(users1::class)->find($username);
            if (!$product) {
                throw $this->createNotFoundException(
                    'No user found for name '
                );
            }
            $product->setPassword($passwordEncoder->encodePassword($user, $password));
            $em->flush();

        }

        return $this->render('my/deleteUser.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/rooms", name="rooms")
     */
    public function showRooms(Request $request)
    {

        return $this->render(':my:rooms.html.twig');
    }
}
