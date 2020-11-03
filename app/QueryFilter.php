<?php

namespace App;

use App\Services\ProviderService;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;
    public $providerService;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(ProviderService $providerService)
    {
        $this->providerService = $providerService;
        foreach ($this->filters() as $name => $value) {
            # ['foo' =>'bar' ,'length' =>'desc']
            if (method_exists($this, $name)) {
                # $this->$name($value);
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        return $providerService->data;
    }


    public function filters()
    {
        return $this->request->all();
    }
}
