<?php

namespace App\Admin\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use \App\Models\Categories;

/**
 * Class Phrases
 *
 * @property \App\Models\Phrases $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Phrases extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-arrow-right');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        //dd($this);
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('name', 'Name', 'created_at')
                ->setSearchCallback(function ($column, $query, $search) {
                    return $query
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('created_at', $direction);
                }),
            AdminColumn::custom('Category', function ($element) {
                return Categories::where('id', $element->category)->first()->name;
            }),
            AdminColumn::boolean('active', 'On'),
            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
                ->setWidth('160px')
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('updated_at', $direction);
                })
                ->setSearchable(false),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover');

        $display->setColumnFilters([
            AdminColumnFilter::select()
                ->setModelForOptions(Categories::class, 'name')
                ->setLoadOptionsQueryPreparer(function ($element, $query) {
                    return $query;
                })
                ->setDisplay('name')
                ->setColumnName('category')
                ->setPlaceholder('All categories'),
        ]);
        $display->getColumnFilters()->setPlacement('card.heading');

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('id', 'ID')->setReadonly(true),
                AdminFormElement::checkbox('active', 'Active'),
                AdminFormElement::text('name', 'Name')->required(),
                AdminFormElement::select('category', 'Category', new \App\Models\Categories())->setDisplay('name')
                    ->setLoadOptionsQueryPreparer(function ($element, $quary) {
                        return $quary->where('active', true);
                    })->required(),
            ])
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return true;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
