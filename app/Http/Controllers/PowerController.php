<?php

namespace App\Http\Controllers;

use App\Models\Power;
use Illuminate\Http\Request;

class PowerController extends ImpController
{
	public function __construct(Request $request, Power $model) {
		parent::__construct($request, $model);

		parent::orderBy('updated_at', 'DESC');
	}
}
