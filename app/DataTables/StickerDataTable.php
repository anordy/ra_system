<?php

namespace App\DataTables;

use App\Models\StickerApp;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StickerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('user_type', function ($row) {
                return $row->userType() ?? '';
            })
            ->addColumn('duration', function ($row) {
                return $row->duration() ?? '';
            })
            ->addColumn('action', function ($data) {
                return sprintf('<button type="button" class="btn btn-info btn-sm px-3" data-mdb-placement="bottom"
                title="Approve user">
                <i class="fas fa-eye"></i> </button>');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\StickerApp $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StickerApp $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('stickerdatatable-table')
            ->selectClassName('table table-sm')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex'),
            Column::make('vh_reg_no'),
            Column::make('vh_owner_name'),
            Column::make('vh_owner_phone'),
            Column::make('dr_name'),
            Column::computed('duration'),
            Column::computed('user_type'),
            Column::computed('action'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Sticker_' . date('YmdHis');
    }
}
