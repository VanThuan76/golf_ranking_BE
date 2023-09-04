<?php

namespace App\Http\Controllers;

use App\Http\Models\Organiser;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\ResponseFormattingTrait;

class OrganiserController extends Controller
{
    use ResponseFormattingTrait;
    public function getList(UtilsCommonHelper $commonController)
    {
        $organisers = Organiser::all();

        $transformedOrganisers = $organisers->map(function ($organiser) use ($commonController) {
            $organiser->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $organiser->status);
            return $organiser;
        });

        $response = $this->_formatBaseResponse(200, $transformedOrganisers, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
