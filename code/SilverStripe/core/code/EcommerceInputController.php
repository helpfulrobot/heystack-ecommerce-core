<?php

use Heystack\Subsystem\Core\ServiceStore;

class EcommerceInputController extends Controller
{

    public static $url_segment = 'ecommerce/input';

    private $stateService;
    private $handlerService;

    public function __construct()
    {

        parent::__construct();

        $this->stateService = ServiceStore::getService('state');
        $this->handlerService = ServiceStore::getService('processor_handler');

    }

    public function process()
    {

        $request = $this->getRequest();

        if ($this->handlerService->hasProcessor($request->param('ID'))) {

            $this->handlerService->getProcessor($request->param('ID'))->process($request);

        }

    }

}