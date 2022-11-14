<?php

namespace Goldnead\CollapseFieldtype\Fieldtypes;

use Statamic\Support\Arr;
use Statamic\Fields\Fields;
use Statamic\Fields\Fieldtype;

class Collapse extends Fieldtype
{
    public $icon = "section";
    protected $defaultValue = [];
    /**
     * The blank/default value.
     *
     * @return array
     */
    public function defaultValue()
    {
        return null;
    }

    protected function configFieldItems(): array
    {
        return [
            'fields' => [
                'display' => __('Fields'),
                'instructions' => __('statamic::fieldtypes.grid.config.fields'),
                'type' => 'fields',
            ],
        ];
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        return $data;
        return collect($data)->map(function ($group, $i) {
            return $this->fields()->addValues($group)->preProcess()->values()->all();
        })->all();
    }

    public function fields()
    {
        return new Fields($this->config('fields'), $this->field()->parent(), $this->field());
    }

    /**
     * Preload default/existing data on the publish page.
     *
     * @return array|mixed
     */
    public function preload()
    {
        $data = Arr::removeNullValues($this->fields()->all()->map(function ($field) {
            return $field->fieldtype()->preProcess($this->field->value());
        })->all());

        $data['defaults'] = Arr::removeNullValues($this->fields()->all()->map(function ($field) {
            return $field->fieldtype()->preProcess($field->defaultValue());
        })->all());

        return $data;
    }

    /**
     * Process the data before it gets saved.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function process($data)
    {
        return $data;
    }
}
