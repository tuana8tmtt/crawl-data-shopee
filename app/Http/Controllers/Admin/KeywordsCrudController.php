<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Crawler_sp;
use App\Http\Requests\KeywordsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Request;

/**
 * Class KeywordsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class KeywordsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Keywords::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/keywords');
        CRUD::setEntityNameStrings('keywords', 'keywords');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn(
            [
                'name'  => 'Keyword',
                'type'  => 'string',
                'label' => 'Keyword',
            ]
        );
        CRUD::addColumn(
            [
                'name'  => 'quantity',
                'type'  => 'string',
                'label' => 'Quantity',
            ]
        );
        CRUD::addColumn(
            [
                'name'  => 'is_crawl',
                'type'  => 'boolean',
                'label' => 'Is Crawl',
            ]
        );
        $this->crud->enableDetailsRow();
    }
    public function store()
    {
        $response = $this->traitStore();
        $keyword = $this->crud->getRequest()->get('Keyword');
        Crawler_sp::crawl_shopee($keyword);

        return $response;
    }



    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(KeywordsRequest::class);
        CRUD::setFromDb(); // fields
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


}
