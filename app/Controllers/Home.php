<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProductModel;
use App\Models\QuotationModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'companyCount' => (new CompanyModel())->countAllResults(),
            'productCount' => (new ProductModel())->countAllResults(),
            'quotationCount' => (new QuotationModel())->countAllResults(),
            'recentQuotations' => (new QuotationModel())->orderBy('created_at', 'DESC')->findAll(5),
        ]);
    }
}