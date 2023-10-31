<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RepositoryController extends Controller
{

    protected $reducedColumns = ['id', 'name'];

    /**
     * Obtém a classe da entidade.
     *
     * @return string
     *
     * @throws \Exception
     */
    public function entity(Request $request)
    {
        $name = $request->route()->getName();

        [, $name, $_action] = explode('.', $name);

        $class = '\\App\\Models\\' . Str::studly($name);

        if (!class_exists($class)) {
            throw new \Exception('Classe não encontrada: ' . $class);
        }

        return $class;
    }

    public function getTableName(Request $request)
    {
        $name = $request->route()->getName();

        [, $name, $_action] = explode('.', $name);

        return Str::plural($name);
    }

    /**
     * Mostra a lista de todos os itens.
     *
     * @param \Illuminate\Http\Request $request Requisição injetada pelo Laravel
     * @param string                   $entity  Nome da entidade
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('read ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        $per_page = 15;

        if ($request->has('per_page')) {
            $per_page = $request->per_page;
        }

        $query = $this->beginQuery($request)
            ->whereCurrentUserCan('read');

        if ($request->has('tab')) {
            $query = $query->whereBelongsToTab($request->tab);
        }

        if ($request->has('q') && !empty($request->q)) {
            $query = $query->search($request->q);
        }

        if ($request->has('filters')) {
            $query = $query->whereMatchesFilter(
                json_decode($request->filters, true)
            );
        }

        if ($request->has('order_by')) {
            [$field, $direction] = explode(':', $request->order_by);
            $query->applyOrderBy($field, $direction);
        }

        $columns = $request->has('reducedColumns')
            ? $this->reducedColumns
            : ['*'];

        $query = $query->paginate($per_page, $columns);

        return response()->json($query, 200);

        // return view('users.index')->with('users',$users);
    }

    /**
     * @param mixed $action
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function beginQuery(Request $request)
    {
        if ($request->has('reducedColumns')) {
            $this->entity($request)::$withoutAppends = true;
        }
        $query = $this->entity($request)::beginCmsQuery($request);

        return $query;
    }

    /**
     * Realiza um novo cadastro de item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('create ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        /** @var \App\Contracts\HasCrudSupport */
        $item = $this->createItem();

        if ($form = $item->getFormInstance()) {
            $form->validate($request);
        }

        $this->fill($request, $item);

        $item->save();

        $this->afterModelSaved($request, $item);

        return response()->json($item, 200);

        // return redirect('/users');
    }

    /**
     * Faz a listagem de um item específico.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function item(Request $request, $id)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('read ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $item = $this->beginQuery($request)
            ->whereCurrentUserCan('read')
            ->where('id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        return response()->json($item, 200);
    }

    /**
     * Faz o update dos itens cadastrados.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('update ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        /** @var \App\Contracts\HasCrudSupport */
        $item = $this->beginQuery($request)
            ->whereCurrentUserCan('update')
            ->where('id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        if ($form = $item->getFormInstance()) {
            $form->validate($request);
        }

        $this->fill($request, $item);

        $item->update();

        $this->afterModelSaved($request, $item);

        return response()->json($item, 200);
    }

    /**
     * Deleta um item cadastrado.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('delete ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $item = $this->beginQuery($request)
            ->whereCurrentUserCan('delete')
            ->find($id);

        if (!$item) {
            abort(404);
        }

        $item->delete();

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Deleta forçadamente um item cadastrado.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {

        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('delete ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $item = $this->beginQuery($request)
            ->whereCurrentUserCan('delete')
            ->onlyTrashed()
            ->where('id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        $item->forceDelete();

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Excluir em massa os itens cadastrados.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('delete ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $items = $this->beginQuery($request)
            ->whereCurrentUserCan('delete')
            ->whereIn('id', $request->ids)
            ->get();

        if (!$items) {
            abort(404);
        }

        foreach ($items as $item) {
            $item->delete();
        }

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Recupera um item cadastrado.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('restore ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $item = $this->beginQuery($request)
            ->whereCurrentUserCan('restore')
            ->onlyTrashed()
            ->where('id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        $item->restore();

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Recupera um item cadastrado.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function massRestore(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('delete ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $items = $this->beginQuery($request)
            ->onlyTrashed()
            ->whereCurrentUserCan('delete')
            ->whereIn('id', $request->ids)
            ->get();

        if (!$items) {
            abort(404);
        }

        foreach ($items as $item) {
            $item->restore();
        }

        return response()->json(['message' => 'OK'], 200);
    }

    public function massForceDelete(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $user = auth()->user();

        if (!$user->can('delete ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $items = $this->beginQuery($request)
            ->whereCurrentUserCan('delete')
            ->onlyTrashed()
            ->whereIn('id', $request->ids)
            ->get();

        if (!$items) {
            abort(404);
        }

        foreach ($items as $item) {
            $item->forceDelete();
        }

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Cria uma entity para a controller.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createItem()
    {
        $classname = $this->entity(request());

        return new $classname();
    }

    /**
     * Preenche uma entidade com os dados da requisição.
     *
     * @param \Illuminate\Database\Eloquent\Model $item Item a ser preenchido
     */
    public function fill(Request $request, $item)
    {
        $data = $request->all();
        $item->fill($data);

        foreach ($data as $key => $value)
        {
            if (
                in_array($key, $item->getFillable())
                || !method_exists($item, $key)
                || !is_array($value)
            ) 
            {
                continue;
            }

            // check if is a "BelongsTo" relation
            // and if true, sets `{$key}_id` attribute
            $reflection = new \ReflectionMethod($item, Str::camel($key));
            if ($reflection->hasReturnType() 
                && (
                    $reflection->getReturnType()->getName() == BelongsTo::class 
                    || is_subclass_of($reflection->getReturnType()->getName(), BelongsTo::class)
                )
            ) 
            {
                /** @var BelongsTo */
                $relation = $item->{$key}();
                $foreignKey = $relation->getForeignKeyName();
                $ownerKey = $relation->getOwnerKeyName();
                if (!isset($value[$ownerKey]))
                {
                    continue;
                }
                $item->{$foreignKey} = $value[$ownerKey];
            }
        }
    }

    /**
     * Dispara após o salvamento de um model.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     */
    public function afterModelSaved(Request $request, &$item)
    {
        foreach ($item->getSyncs() as $relation) {
            if ($request->has($relation) && method_exists($item, $relation)) {
                $key = -1;
                $item->{$relation}()->sync(
                    collect($request->{$relation})->mapWithKeys(function ($item) use (&$key) {
                        if (!isset($item['pivot'])) {
                            $key++;
                            return [$key => $item['id']];
                        }
                        $key = $item['id'];
                        return [$item['id'] => $item['pivot']];
                    })
                );
            }
        }
    }

    /**
     * Obtém a listagem de um campo autocomplete
     */
    public function autocomplete(Request $request, AdminService $admin)
    {
        $requestSearch = $request->all();

        [
            'name' => $fieldName,
            'model' => $schema,
            'schema' => $formSchema,
        ] = $requestSearch;

        $text = $request->q;
        $fieldName = $request->name;
        $schema = $request->model;
        $formSchema = $request->schema;

        $models = $admin->getModelsWithCrudSupport();

        $model = $models
            ->map(function ($model) {
                return new $model();
            })
            ->first(function ($model) use ($schema) {
                return $model->getSchemaName() == $schema;
            });

        $form = $model->getFormInstance();

        $fields = $form->{$formSchema}();

        $field = collect($fields)->first(function ($field) use ($fieldName) {
            return $field['name'] == $fieldName;
        });

        return response()->json([
            'data' => $field['list']($text),
        ]);
    }

    /**
     * Faz a importação de dados a partir de uma planilha
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create ' . $this->getTableName($request))) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        $file = $request->file('file')->store('temp');

        $spreadsheet = IOFactory::load(storage_path('app/' . $file)); // Carrega o arquivo Excel

        $Model = $this->entity($request);

        $isHeader = true;
        $header = [];
        $worksheet = $spreadsheet->getActiveSheet();

        $created = 0;
        $skipped = 0;

        foreach ($worksheet->getRowIterator() as $row)
        {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);

            $data = [];

            foreach ($cellIterator as $i => $cell)
            {
                if ($isHeader) {
                    $header[] = $cell->getValue();
                } else {
                    $data[$header[$i]] = $cell->getValue();
                }
            }

            if (!$isHeader) {
                $item = $Model::fromImportFile($data);
                if ($item) {
                    $created++;
                } else {
                    $skipped++;
                }
            }

            $isHeader = false;
        }

        // Deleta arquivo temporário
        Storage::delete($file);

        return response()->json([
            'message' => 'OK',
            'created' => $created,
            'skipped' => $skipped,
        ], 200);
    }

    /**
     * Faz o download de uma planilha modelo, configurada em cada model
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $Model = $this->entity($request);

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->fromArray([$Model::getExportsHeadings()], null, 'A1');

        $query = $this->beginQuery($request)
            ->whereCurrentUserCan('read');

        if ($request->has('tab')) {
            $query = $query->whereBelongsToTab($request->tab);
        }

        if ($request->has('q') && !empty($request->q)) {
            $query = $query->search($request->q);
        }

        if ($request->has('filters')) {
            $query = $query->whereMatchesFilter(
                json_decode($request->filters, true)
            );
        }

        $items = $query->get();

        $data = [];

        foreach ($items as $item) {
            $data[] = $item->getExportsData();
        }

        $worksheet->fromArray($data, null, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = Str::plural((new $Model)->getSchemaName());

        // Prepare headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'.xlsx"');

        // Save to php://output
        $writer->save('php://output');

        return response()->json(['message' => 'OK'], 200);
    }
}
