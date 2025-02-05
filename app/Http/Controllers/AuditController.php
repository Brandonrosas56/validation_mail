<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Muestra una lista de todas las auditorías almacenadas.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante.
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $audits = Audit::paginate(8);
        return view('audit.audit', compact('audits'));
    }

    /**
     * Realiza una búsqueda en la tabla de auditorías basada en los criterios proporcionados.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante.
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        Audit::validateSearchData($request->all());
      
        $dataType = $request->input('dataType');
        $search = $request->input('search');
        $creationDateFrom = $request->input('creationDateFrom');
        $creationDateTo = $request->input('creationDateTo');
        $updateStartDate = $request->input('updateStartDate');
        $updateDateTo = $request->input('updateDateTo');

        $query = Audit::query();
        $query->filterByDataType($dataType, $search);
        $query->filterByCreationDate($creationDateFrom, $creationDateTo);
        $query->filterByUpdateDate($updateStartDate, $updateDateTo);
        $audits = $query->paginate(8);

        return view('audit.audit', compact('audits'));
    }

}
