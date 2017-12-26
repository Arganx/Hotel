<?php

namespace AppBundle\Controller;

use AppBundle\Entity\room;
use AppBundle\Entity\visit;
//use Doctrine\DBAL\Types\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\users1;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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


    /**
     * @Route("/addRoom", name="roomAdd")
     */
    public function addRoomAction(Request $request)
    {
        $room=new room();

        $form = $this->createFormBuilder($room)
            ->add('name',TextType::class)
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'single' => 'single',
                    'double' => 'double',
                    'luxury' => 'luxury',
                    'wedding' => 'wedding',
                ),
            ))
            ->add('price',IntegerType::class)
            ->add('extraBed',IntegerType::class)
            ->add('description',TextareaType::class)
            ->add('save',SubmitType::class,array('label'=>'Dodaj pokoj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $type = $form['type']->getData();
            $price = $form['price']->getData();
            $extraBed= $form['extraBed']->getData();
            $description = $form['description']->getData();


            $room->setName($name);
            $room->setType($type);
            $room->setExtraBed($extraBed);
            $room->setPrice($price);
            $room->setDescription($description);

            $em=$this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();



            return $this->redirect('/');
        }

        return $this->render('my/addRoom.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/addVisitSingle", name="roomSingle")
     */
    public function addVistSingle(Request $request)
    {
        $visit=new visit();

        $form = $this->createFormBuilder($visit)
            ->add('startDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('endDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('save',SubmitType::class,array('label'=>'Dodaj pokoj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guest = $this->getUser()->getId();
            $startDate = $form['startDate']->getData();
            $endDate = $form['endDate']->getData();

            $rooms = $this->getDoctrine()
                ->getRepository('AppBundle:room')
                ->findAll();
            $visits = $this->getDoctrine()
                ->getRepository('AppBundle:visit')
                ->findAll();

            $free = 1;
            $help=null;
            foreach ($rooms as &$value) {
                if($value->getType()=="single")
                {
                    foreach ($visits as &$vis)
                    {
                        if($vis->getRoom()==$value->getId())    //ten sam pokoj w wizycie
                        {
                            if($vis->getStartDate()<$startDate)//sprawdzam daty poczatkowe
                            {
                                if($vis->getEndDate()<$startDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else if($vis->getStartDate()>$startDate)
                            {
                                if($vis->getStartDate()>$endDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else
                            {
                                $free=0;
                            }
                        }
                    }
                if($free==1)
                {
                    $help=$value;
                }
                $free=1;
                }
            }
            $extraBed= 0;
            if($help!=null) {
                $room = $help->getId();
                $diff = $startDate->diff($endDate)->format("%a");
                $price = $help->getPrice()*$diff+$extraBed*$help->getExtraBed();




                $visit->setGuest($guest);
                $visit->setRoom($room);
                $visit->setExtraBeds($extraBed);
                $visit->setPrice($price);
                $visit->setstartDate($startDate);
                $visit->setendDate($endDate);

                $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();


                //return $this->redirect('/successfulVisit');
            }
            else
            {
                return $this->redirect('/failedVisit');
            }
        }

        return $this->render('my/addVisitSingle.html.twig',array('form'=> $form->createView()));
    }
    /**
     * @Route("/successfulVisit", name="succes")
     */
    public function succesVisit(Request $request)
    {

        return $this->render('my/successfulVisit.html.twig');
    }

    /**
     * @Route("/failedVisit", name="fail")
     */
    public function failedVisit(Request $request)
    {

        return $this->render('my/failedVisit.html.twig');
    }


    /**
     * @Route("/addVisitDouble", name="roomDouble")
     */
    public function addVistDouble(Request $request)
    {
        $visit=new visit();

        $form = $this->createFormBuilder($visit)
            ->add('startDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('endDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('extraBeds',ChoiceType::class, array(
                'choices'  => array(
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                ),
            ))
            ->add('save',SubmitType::class,array('label'=>'Dodaj pokoj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guest = $this->getUser()->getId();
            $startDate = $form['startDate']->getData();
            $endDate = $form['endDate']->getData();
            $extraBed= $form['extraBeds']->getData();

            $rooms = $this->getDoctrine()
                ->getRepository('AppBundle:room')
                ->findAll();
            $visits = $this->getDoctrine()
                ->getRepository('AppBundle:visit')
                ->findAll();

            $free = 1;
            $help=null;
            foreach ($rooms as &$value) {
                if($value->getType()=="double")
                {
                    foreach ($visits as &$vis)
                    {
                        if($vis->getRoom()==$value->getId())    //ten sam pokoj w wizycie
                        {
                            if($vis->getStartDate()<$startDate)//sprawdzam daty poczatkowe
                            {
                                if($vis->getEndDate()<$startDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else if($vis->getStartDate()>$startDate)
                            {
                                if($vis->getStartDate()>$endDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else
                            {
                                $free=0;
                            }
                        }
                    }
                    if($free==1)
                    {
                        $help=$value;
                    }
                    $free=1;
                }
            }


            if($help!=null) {
                $room = $help->getId();
                $price = $help->getPrice();


                $visit->setGuest($guest);
                $visit->setRoom($room);
                $visit->setExtraBeds($extraBed);
                $visit->setPrice($price);
                $visit->setstartDate($startDate);
                $visit->setendDate($endDate);

                $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();


                return $this->redirect('/successfulVisit');
            }
            else
            {
                return $this->redirect('/failedVisit');
            }
        }

        return $this->render('my/addVisitSingle.html.twig',array('form'=> $form->createView()));
    }


    /**
     * @Route("/addVisitLuxury", name="roomLuxury")
     */
    public function addVistLuxury(Request $request)
    {
        $visit=new visit();

        $form = $this->createFormBuilder($visit)
            ->add('startDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('endDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('extraBeds',ChoiceType::class, array(
                'choices'  => array(
                    '0' => '0',
                    '1' => '1',
                ),
            ))
            ->add('save',SubmitType::class,array('label'=>'Dodaj pokoj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guest = $this->getUser()->getId();
            $startDate = $form['startDate']->getData();
            $endDate = $form['endDate']->getData();
            $extraBed= $form['extraBeds']->getData();

            $rooms = $this->getDoctrine()
                ->getRepository('AppBundle:room')
                ->findAll();
            $visits = $this->getDoctrine()
                ->getRepository('AppBundle:visit')
                ->findAll();

            $free = 1;
            $help=null;
            foreach ($rooms as &$value) {
                if($value->getType()=="luxury")
                {
                    foreach ($visits as &$vis)
                    {
                        if($vis->getRoom()==$value->getId())    //ten sam pokoj w wizycie
                        {
                            if($vis->getStartDate()<$startDate)//sprawdzam daty poczatkowe
                            {
                                if($vis->getEndDate()<$startDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else if($vis->getStartDate()>$startDate)
                            {
                                if($vis->getStartDate()>$endDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else
                            {
                                $free=0;
                            }
                        }
                    }
                    if($free==1)
                    {
                        $help=$value;
                    }
                    $free=1;
                }
            }


            if($help!=null) {
                $room = $help->getId();
                $price = $help->getPrice();


                $visit->setGuest($guest);
                $visit->setRoom($room);
                $visit->setExtraBeds($extraBed);
                $visit->setPrice($price);
                $visit->setstartDate($startDate);
                $visit->setendDate($endDate);

                $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();


                return $this->redirect('/successfulVisit');
            }
            else
            {
                return $this->redirect('/failedVisit');
            }
        }

        return $this->render('my/addVisitLuxury.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/addVisitWedding", name="roomWedding")
     */
    public function addVistWedding(Request $request)
    {
        $visit=new visit();

        $form = $this->createFormBuilder($visit)
            ->add('startDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('endDate',DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('save',SubmitType::class,array('label'=>'Dodaj pokoj'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guest = $this->getUser()->getId();
            $startDate = $form['startDate']->getData();
            $endDate = $form['endDate']->getData();
            $extraBed= 0;

            $rooms = $this->getDoctrine()
                ->getRepository('AppBundle:room')
                ->findAll();
            $visits = $this->getDoctrine()
                ->getRepository('AppBundle:visit')
                ->findAll();

            $free = 1;
            $help=null;
            foreach ($rooms as &$value) {
                if($value->getType()=="wedding")
                {
                    foreach ($visits as &$vis)
                    {
                        if($vis->getRoom()==$value->getId())    //ten sam pokoj w wizycie
                        {
                            if($vis->getStartDate()<$startDate)//sprawdzam daty poczatkowe
                            {
                                if($vis->getEndDate()<$startDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else if($vis->getStartDate()>$startDate)
                            {
                                if($vis->getStartDate()>$endDate)
                                {

                                }
                                else
                                {
                                    $free=0;
                                }
                            }
                            else
                            {
                                $free=0;
                            }
                        }
                    }
                    if($free==1)
                    {
                        $help=$value;
                    }
                    $free=1;
                }
            }


            if($help!=null) {
                $room = $help->getId();
                $price = $help->getPrice();


                $visit->setGuest($guest);
                $visit->setRoom($room);
                $visit->setExtraBeds($extraBed);
                $visit->setPrice($price);
                $visit->setstartDate($startDate);
                $visit->setendDate($endDate);

                $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();


                return $this->redirect('/successfulVisit');
            }
            else
            {
                return $this->redirect('/failedVisit');
            }
        }

        return $this->render('my/addVisitWedding.html.twig',array('form'=> $form->createView()));
    }
}
