<?php

namespace App;

use App\Services\ProviderService;

class ProviderFilter extends QueryFilter
{

    public $statusProvider = ['authorised' => [1, 100], 'decline' => [2, 200], 'refunded' => [3, 300]];

    public function provider($provider = 'DataProviderX')
    {
        return $this->providerService->readJsonFile($this->providerService->listOfFiles([$provider]));
    }
    function statusCode($status = 'authorised')
    {
        $condition = (array_key_exists($status, $this->statusProvider)) ? $this->statusProvider[$status] : $this->statusProvider['authorised'];
        for ($i = 0; $i < count($this->providerService->data); $i++) {
            $filterDataByStatus = $this->providerService->data[$i]->filter(function ($value, $key) use ($condition, $i) {
                if (isset($value["statusCode"])) {
                    return $value["statusCode"] == $condition[0];
                }
                if (isset($value["status"])) {
                    return $value["status"] == $condition[1];
                }
            });
      
            $this->providerService->data[$i] = $filterDataByStatus->values();
        }
        return $this->providerService->data;
    }

    public function currency($currencyType = "AED")
    {
        $currencyType = strtoupper($currencyType);
        for ($i = 0; $i < count($this->providerService->data); $i++) {
            $filterDataByCurrency = $this->providerService->data[$i]->filter(function ($value, $key) use ($currencyType) {
                if (isset($value["Currency"])) {
                    return $value["Currency"] == $currencyType;
                }
                if (isset($value["currency"])) {
                    return $value["currency"] == $currencyType;
                }
            });
            if (count($filterDataByCurrency) > 0) {
                $this->providerService->data[$i] = $filterDataByCurrency->values();
            }
        }
        return $this->providerService->data;
    }

    public function balanceMin($balanceValue)
    {
        
        for ($i = 0; $i < count($this->providerService->data); $i++) {
            $filterDataByBalance = $this->providerService->data[$i]->filter(function ($value, $key) use ($balanceValue) {
                if (isset($value["balance"])) {
                    return $value["balance"] >= $balanceValue;
                }
            });
            if (count($filterDataByBalance) > 0) {
                $this->providerService->data = [];
                 array_push($this->providerService->data,$filterDataByBalance->values());
            }
         }
        return $this->providerService->data;
    }

    public function balanceMax($balanceValue)
    {
        for ($i = 0; $i < count($this->providerService->data); $i++) {
            $filterDataByBalance = $this->providerService->data[$i]->filter(function ($value, $key) use ($balanceValue) {
                if (isset($value["balance"])) {
                    return $value["balance"] <= $balanceValue;
                }
            });
            if (count($filterDataByBalance) > 0) {
                $this->providerService->data = [];
                array_push($this->providerService->data,$filterDataByBalance->values());
            }
        }
        return $this->providerService->data;
    }
}
