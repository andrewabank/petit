<?php

namespace Tikit\TikitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tikit\TikitBundle\Entity\Petition;
use Tikit\TikitBundle\Form\PetitionType;

/**
 * Petition controller.
 *
 * @Route("/petition")
 */
class PetitionController extends Controller
{

    /**
     * Lists all Petition entities.
     *
     * @Route("/", name="petition")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TikitTikitBundle:Petition')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Petition entity.
     *
     * @Route("/", name="petition_create")
     * @Method("POST")
     * @Template("TikitTikitBundle:Petition:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {

            $session = $request->getSession();
            // add flash messages
            $session->getFlashBag()->add(
                'warning',
                'Please login to create petition'
            );
            return $this->redirect($this->generateUrl('mostpopular'));
        } 
        $entity = new Petition();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //$user_id = $form["user"]->getData();
            $user = $this->getUser();
            $user_id = $user->getId();
            //$entity->setUser($user_id);
            $this->get('petition.model.petitionmodel')->addPetition($entity, $user_id);
            return $this->redirect($this->generateUrl('petition_show', array('url' => $entity->getPetitionUrl())));
        }

        return $this->render('TikitTikitBundle:Petition:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Petition entity.
    *
    * @param Petition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Petition $entity)
    {
        //$form = $this->createForm(new EntityType($this->getDoctrine()->getManager()), $entity);
        $form = $this->createForm(new PetitionType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('petition_create'),
            'method' => 'POST',
        ));

        return $form;
    }

     /**
     * @Route("/votepetition/", name="_votepetition")
     * 
     */
    public function votepetitionAction()
    {
        if($this->getRequest()->isMethod('post')){
            //$vote = $this->getRequest()->get('vote',false);
            $petition_id = $this->getRequest()->get('petition_id',false);
            $user_id = $this->getRequest()->get('user_id',false);

            $res = $this->get('petition_model')->votePetition($petition_id,$user_id);
            if($res){
                $json = array('id' => 1);
            } else{
                $json = array('id' => 0);
            }
            $json = json_encode($json);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
    
    /**
     * Finds and displays a Petition entity.
     *
     * @Route("/{url}", name="petition_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($url)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TikitTikitBundle:Petition')->findOneBy(array('petitionUrl' => $url));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Petition entity.');
        } else {
            $id = $entity->getId();
        }

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $deleteForm = $this->createDeleteForm($id);
        $form = $formFactory->createForm();
        $user = $this->getUser();
        //$form = $this->createForm($this->get(new \Acme\UserBundle\Form\Type\RegistrationFormType(), $user));
        $userManager = $this->container->get('fos_user.user_manager');
        if (!$user) {
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $form->setData($user);
        }
        $ent = $em->getRepository('TikitTikitBundle:PetitionScore')->findOneBy(array('petition' => $entity, 'user' => $user));
        //$idPs = $ent->getId();
        if ($ent) {
            $idPs = $ent->getId();
            $scored = true;
        } else {
            $scored = false;
        }
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') == false){
            $admin = false;
        } else {
            $admin = true;
        }
        return $this->render('TikitTikitBundle:Petition:show.html.twig', array(
            'admin' => $admin,
            'entity'      => $entity,
            'csrf_token' => $csrfToken,
            'delete_form' => $deleteForm->createView(),
            'scored' => $scored,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a Petition entity.
     *
     * @Route("/{id}", name="petition_showold")
     * @Method("GET")
     * @Template()
     */
    public function showoldAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TikitTikitBundle:Petition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Petition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Displays a form to edit an existing Petition entity.
     *
     * @Route("/{id}/edit", name="petition_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TikitTikitBundle:Petition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Petition entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Petition entity.
    *
    * @param Petition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Petition $entity)
    {
        $form = $this->createForm(new PetitionType(), $entity, array(
            'action' => $this->generateUrl('petition_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Petition entity.
     *
     * @Route("/{id}", name="petition_update")
     * @Method("PUT")
     * @Template("TikitTikitBundle:Petition:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TikitTikitBundle:Petition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Petition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('petition_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Petition entity.
     *
     * @Route("/{id}", name="petition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)|| !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $session = $request->getSession();
            // add flash messages
            $session->getFlashBag()->add(
                'warning',
                'Please login to create petition'
            );
            return $this->redirect($this->generateUrl('mostpopular'));
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TikitTikitBundle:Petition')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Petition entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('petition'));
    }

    /**
     * Creates a form to delete a Petition entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('petition_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * @Route("/petit/{page}", name="petition_petitions")
     * @Template()
     */
    public function petitionsAction($page)
    {
        $request = $this->get('request');
        //$pageN = $request->get('page');
        $pageN = $page;
        $total_count = $this->get('petition_model')->getTotalPetitions();
        //http://dev.dbl-a.com/symfony-2-0/symfony2-and-twig-pagination/
        $quantity = 2;
        $page = $this->get('util_model')->getPageData($total_count,$pageN,$quantity);
        $petitions = $this->get('petition_model')->getPetitions($page['count_per_page'],$page['offset']);
        return $this->render('TikitTikitBundle:Petition:petitions.html.twig', array(
            'page_title'  => 'Петиції',
            'pathController' => 'petition_petitions',
            'current_page'  => $page['page'],
            'total_pages' => $page['total_pages'],
            'totalmostpop' => 0,
            'petitions' => $petitions,
            'showing_from' => $page['showing_from'],
            'showing_to' => $page['showing_to'],
            'total_number' => $total_count
        ));
    }

    /**
     * @Route("/category/{category}/{page}", name="bycategory")
     * @Template()
     */
    public function bycategoryAction($category, $page)
    {
        $request = $this->get('request');
        $total_count = $this->get('petition_model')->getTotalPetitionsByCategory($category);
        $quantity = 10;
        $page = $this->get('util_model')->getPageData($total_count,$page,$quantity);
        $petitions = $this->get('petition_model')->getPetitionsByCategory($page['count_per_page'],$page['offset'],$category);
        return $this->render('TikitTikitBundle:Petition:petitionsCategory.html.twig', array(
            'page_title'  => 'Петиції',
            'pathController' => 'bycategory',
            'current_page'  => $page['page'],
            'total_pages' => $page['total_pages'],
            'totalmostpop' => 0,
            'category' => $category,
            'petitions' => $petitions,
            'showing_from' => $page['showing_from'],
            'showing_to' => $page['showing_to'],
            'total_number' => $total_count
        ));
    }
    
    public function categoryMenuAction()
    {
        $categories = $this->get('petition_model')->getCategories();
        return $this->render(
            'TikitTikitBundle:Petition:categoryMenu.html.twig',
            array('categories' => $categories)
        );
    }
    
    /**
     * @Route("/", name="mostpopular")
     * @Template()
     */
    public function mostpopularAction()
    {
        $request = $this->get('request');
        $petitions = $this->get('petition_model')->getMostPopularPetitions(50,0);
        return $this->render('TikitTikitBundle:Petition:petitions.html.twig', array(
            'page_title'  => '50 найрейтинговіших петицій',
            'current_page'  => 1,
            'total_pages' => 1,
            'totalmostpop' => 1,
            'petitions' => $petitions
        ));
    }
}
