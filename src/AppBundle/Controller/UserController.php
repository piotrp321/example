<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\RowAction;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        // Creates a simple grid based on your entity (ORM)
        $source = new Entity('AppBundle:User');

        // Get a Grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);

        // set page limit
        $grid->setLimits(10);

        // Custom actions column in the wanted position
        $myActionsColumn = new ActionsColumn('actions', 'Actions');
        $grid->addColumn($myActionsColumn);

        $myRowAction = new RowAction('Disable', 'user_disabled');

        $myRowAction->addManipulateRender(
            function ($action, $row) {
                /** @var User $user */
                $user = $row->getEntity();
                if (!$user->isEnabled()) {
                    $action->setEnabled(false);
                }
                return $action;
            }
        );

        $myRowAction->setColumn('actions');
        $grid->addRowAction($myRowAction);

        // Return the response of the grid to the template
        return $grid->getGridResponse('user/index.html.twig');
    }


    /**
     * Finds and disabled a user entity.
     *
     * @Route("/{id}", name="user_disabled")
     * @Method("GET")
     */
    public function disabledAction(User $user)
    {
        $user->setEnabled(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_index');
    }
}
