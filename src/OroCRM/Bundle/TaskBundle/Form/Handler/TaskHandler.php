<?php

namespace OroCRM\Bundle\TaskBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;

use OroCRM\Bundle\TaskBundle\Entity\Task;

class TaskHandler
{
    /** @var FormInterface */
    protected $form;

    /** @var Request */
    protected $request;

    /** @var ObjectManager */
    protected $manager;

    /** @var EntityRoutingHelper */
    protected $entityRoutingHelper;

    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param ObjectManager       $manager
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        ObjectManager $manager,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->form                = $form;
        $this->request             = $request;
        $this->manager             = $manager;
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * Process form
     *
     * @param  Task $entity
     *
     * @return bool  True on successful processing, false otherwise
     */
    public function process(Task $entity)
    {
        $targetEntityClass = $this->request->get('entityClass');
        $targetEntityId    = $this->request->get('entityId');

        $options = [];

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                if ($targetEntityClass) {
                    $entity->addActivityTarget(
                        $this->entityRoutingHelper->getEntityReference($targetEntityClass, $targetEntityId)
                    );
                }
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param Task $entity
     */
    protected function onSuccess(Task $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Get form, that build into handler, via handler service
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
