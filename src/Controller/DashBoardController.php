<?php
/**
 * Created by PhpStorm.
 * User: korman
 * Date: 10.01.18
 * Time: 2:17
 */

namespace App\Controller;

use App\Utils\Xbet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashBoardController
 * @package App\Controller
 */
class DashBoardController extends Controller
{
    /**
     *
     * @Route("/dashboards/bets", name="xbet_dashboard_bets")
     */
    public function betsAction()
    {
        $xbet = new Xbet();
        $data = $xbet->loadData()->parse();

        return new JsonResponse($data);
    }
}
