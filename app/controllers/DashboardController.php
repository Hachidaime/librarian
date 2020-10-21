<?php

use app\controllers\Controller;

/**
 * Class DashboardController
 *
 * This class will handle User controller
 *
 * @author Hachidaime
 */

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setControllerAttribute(__CLASS__);
        $this->smarty->assign('title', $this->title);
    }
    /**
     * function index
     *
     * This method will handle default Dashboard page
     *
     * @access public
     */
    public function index()
    {
        $this->smarty->display('Dashboard/index.tpl');
    }
}
