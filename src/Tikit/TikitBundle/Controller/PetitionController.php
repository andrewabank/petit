<?php

namespace Tikit\TikitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $entity = new Petition();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('petition_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $form = $this->createForm(new PetitionType(), $entity, array(
            'action' => $this->generateUrl('petition_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Petition entity.
     *
     * @Route("/{id}", name="petition_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TikitTikitBundle:Petition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Petition entity.');
        }

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $deleteForm = $this->createDeleteForm($id);
        $form = $formFactory->createForm();
        //$form = $this->createForm($this->get(new \Acme\UserBundle\Form\Type\RegistrationFormType(), $user));
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $form->setData($user);
        return array(
            'entity'      => $entity,
            'csrf_token' => $csrfToken,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView()
        );
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
}
