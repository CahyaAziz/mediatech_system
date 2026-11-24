<?php

namespace App\Http\Controllers;

use App\Services\MasterData\MasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Dasar bersama untuk controller tiap tab Master Data (Customers, Suppliers,
 * Products, Kategori, Warehouses, Rekening). Controller turunan hanya
 * menentukan query, kolom pencarian, dan data tambahan form.
 */
abstract class MasterDataController extends Controller
{
    public const PER_PAGE_OPTIONS = [10, 25, 50];

    public function __construct(protected readonly MasterDataService $masterData)
    {
    }

    /** Kunci tab sesuai MasterDataService, misalnya `customers`. */
    abstract protected function tab(): string;

    protected function indexView(Request $request, Builder $query, array $searchColumns): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, self::PER_PAGE_OPTIONS, true) ? $perPage : 10;
        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($searchColumns, $search) {
                foreach ($searchColumns as $column) {
                    $builder->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return view('master-data.index', [
            'tab' => $this->masterData->definition($this->tab()),
            'tabs' => $this->masterData->tabs(),
            'records' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
        ]);
    }

    protected function formView(Model $record, array $extra = []): View
    {
        abort_if($record->exists && $record->deleted_at !== null, 404);

        $tab = $this->masterData->definition($this->tab());

        return view('master-data.form', [
            'tab' => $tab,
            'record' => $record,
            'code' => $record->exists ? $record->{$tab['code_column']} : $this->masterData->nextCode($this->tab()),
            ...$extra,
        ]);
    }

    protected function storeRecord(Request $request, array $data): RedirectResponse
    {
        $record = $this->masterData->create($this->tab(), $data, $request->user());

        return $this->backToIndex($this->displayName($record) . ' berhasil ditambahkan.');
    }

    protected function updateRecord(Request $request, Model $record, array $data): RedirectResponse
    {
        $this->masterData->update($this->tab(), $record, $data, $request->user());

        return $this->backToIndex($this->displayName($record) . ' berhasil diperbarui.');
    }

    protected function destroyRecord(Request $request, Model $record): RedirectResponse
    {
        $validated = $request->validate([
            'deletion_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [], ['deletion_reason' => 'alasan penghapusan']);

        try {
            $this->masterData->softDelete($this->tab(), $record, $validated['deletion_reason'], $request->user());
        } catch (ValidationException $exception) {
            return redirect()->route("master-data.{$this->tab()}.index")
                ->with('error', collect($exception->errors())->flatten()->first());
        }

        return $this->backToIndex($this->displayName($record) . ' berhasil dihapus dan masuk ke Sampah.');
    }

    protected function backToIndex(string $message): RedirectResponse
    {
        return redirect()->route("master-data.{$this->tab()}.index")->with('success', $message);
    }

    private function displayName(Model $record): string
    {
        return $this->masterData->displayName($this->masterData->definition($this->tab()), $record);
    }
}
