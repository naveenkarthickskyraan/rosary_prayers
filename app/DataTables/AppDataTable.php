<?php

namespace App\DataTables;

use App\Models\App;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AppDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('action', function ($row) {
                return "<a href='/admin/view_app/{$row->app_id}'> <button type='button' data-toggle='tooltip' title='App Info' class='btn btn-link btn-info' data-original-title='App Info'><i class='fa fa-info'></i></button></a>";            
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\App $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(App $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('app-table')
                    ->columns($this->getColumns())
                    ->parameters([
                        'dom'          => 'Bfrtip',
                        'buttons'=> [
                            'excel',
                            'csv',
                            'print',
                        ]
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
       
        ini_set('memory_limit', '-1');
        $columns = [
            'app_id',
            'app_name',
            'app_type_version',
            'global_app_version_id',
            'app_table_name',
            'push_notification_app',
            'app_type_android',
            'app_type_ios',
            'android_api_key',
            'android_sender_id',
            'android_package_name',
            'ios_key_id',
            'ios_team_id',
            'ios_bundle_id',
            'ios_apple_id',
            'add_audio',
            'audio_app_id',
            'audio_base_path',
            'audio_base_path_type',
            'is_commentry_enabled',
            'commentry_appid',
            'calendar_app_id',
            'image_app_id',
            'quote_app_id',
            'video_app_id',
            'quiz_cat_id',
            'wallpaper_cat_id',
            'verse_editor_app_id',
            'language_code',
            'language_name',
            'shareapp_code',
            'app_mapping_permission',
            'mapped_app_list',
            'bibleContentURL',
            'copyrights',
            'bibleVersion',
            'country_name',
            'country_code',
            'app_theme_color',
            'app_status_theme_color',
            'copyright_name',
            'copyright_url',
            'bibleImage',
            'bibleImageUploadType',
            'bibleShortDescription',
            'ios_bible_active_status',
            'android_bible_active_status',
            'isPasswordProtected',
            'is_left_to_right',
            'is_reading_plan_enabled',
            'is_subscribtion_available',
            'is_voice_speech_audio_available',
            'is_text_to_speech_available_ios',
            'text_to_speech_id_ios',
            'is_text_to_speech_available_android',
            'text_to_speech_id_android',
            'action'
        ];
    
        $columnDefinitions = [];
    
        foreach ($columns as $column) {
            $columnDefinitions[] = Column::make($column)->title($column);
        }
    
        return $columnDefinitions;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'App_' . date('YmdHis');
    }
}
